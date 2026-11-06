<?php

declare(strict_types=1);

namespace Videxo\Tests;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Videxo\Exception\ConnectionException;
use Videxo\Exception\RateLimitedException;
use Videxo\Exception\ServiceUnavailableException;

final class RetriesTest extends TestCase
{
    public function testRetriesA503WithTheSameBodyAndIdempotencyKey(): void
    {
        $unavailable = $this->api->problem('service_unavailable');
        $this->api->queue = [$unavailable, $unavailable];
        $job = $this->videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        self::assertCount(3, $this->api->requests);
        [$first, $second, $third] = $this->api->requests;
        self::assertSame($first['headers']['idempotency-key'], $third['headers']['idempotency-key']);
        self::assertSame($first['body'], $second['body']);
        self::assertSame($first['body'], $third['body']);
        self::assertNotSame('', $third['body']);
        self::assertCount(2, $this->clock->sleeps);
        // 0.5 s then 1 s, each with up to half of it taken off as jitter.
        self::assertGreaterThanOrEqual(0.25, $this->clock->sleeps[0]);
        self::assertLessThanOrEqual(0.5, $this->clock->sleeps[0]);
        self::assertGreaterThanOrEqual(0.5, $this->clock->sleeps[1]);
        self::assertLessThanOrEqual(1.0, $this->clock->sleeps[1]);
        self::assertSame('queued', $job->state->value);
    }

    public function testStopsAfterMaxRetries(): void
    {
        $unavailable = $this->api->problem('service_unavailable');
        $this->api->queue = [$unavailable, $unavailable, $unavailable, $unavailable];
        $this->expectException(ServiceUnavailableException::class);
        try {
            $this->videxo->fonts->list();
        } finally {
            self::assertCount(3, $this->api->requests);
        }
    }

    public function testMaxRetriesZeroSendsOnce(): void
    {
        $this->api->queue = [$this->api->problem('service_unavailable', 503)];
        try {
            $this->client(maxRetries: 0)->fonts->list();
        } catch (ServiceUnavailableException) {
        }
        self::assertCount(1, $this->api->requests);
        self::assertSame([], $this->clock->sleeps);
    }

    public function testHonoursRetryAfter(): void
    {
        $this->api->queue = [$this->api->problem('rate_limited', headers: ['Retry-After' => '3'])];
        $this->videxo->fonts->list();
        self::assertSame([3.0], $this->clock->sleeps);
    }

    public function testDoesNotWaitForALongRetryAfter(): void
    {
        $this->api->queue = [$this->api->problem('rate_limited', headers: ['Retry-After' => '120'])];
        try {
            $this->videxo->fonts->list();
            self::fail('no exception');
        } catch (RateLimitedException $error) {
            self::assertSame(120, $error->retryAfter);
            self::assertSame(429, $error->status);
        }
        self::assertCount(1, $this->api->requests);
    }

    public function testRetriesANetworkErrorOfASafeRequest(): void
    {
        $this->api->queue = [new ConnectException('cURL error 7: Failed to connect', new Request('GET', FakeApi::BASE_URL))];
        $this->videxo->fonts->list();
        self::assertCount(2, $this->api->requests);
    }

    public function testATimeoutHasItsOwnCode(): void
    {
        $timeout = new ConnectException('cURL error 28: Operation timed out after 60001 milliseconds', new Request('GET', FakeApi::BASE_URL));
        $this->api->queue = [$timeout, $timeout, $timeout];
        try {
            $this->videxo->fonts->list();
            self::fail('no exception');
        } catch (ConnectionException $error) {
            self::assertSame('timeout', $error->getErrorCode());
            self::assertStringStartsWith('GET /v1/fonts got no answer in time', $error->getMessage());
            self::assertSame($timeout, $error->getPrevious());
        }
        self::assertCount(3, $this->api->requests);
    }

    public function testRemovesTheKeyFromANetworkErrorMessage(): void
    {
        $leak = new ConnectException('Failed with header Bearer ' . FakeApi::KEY, new Request('GET', FakeApi::BASE_URL));
        $this->api->queue = [$leak, $leak, $leak];
        try {
            $this->videxo->fonts->list();
            self::fail('no exception');
        } catch (ConnectionException $error) {
            self::assertSame('network_error', $error->errorCode);
            self::assertStringNotContainsString(FakeApi::KEY, $error->getMessage());
            self::assertStringContainsString('[api key]', $error->getMessage());
            self::assertNull($error->getPrevious());
        }
    }

    public function testRetriesAnUploadCreationOnlyAfterA429(): void
    {
        $this->api->queue = [$this->api->problem('rate_limited', headers: ['Retry-After' => '1'])];
        $this->videxo->uploads->create('talk.mp4', 3, 'video/mp4');
        self::assertCount(2, $this->api->requests);

        $this->api->requests = [];
        $this->api->queue = [$this->api->problem('service_unavailable')];
        try {
            $this->videxo->uploads->create('talk.mp4', 3, 'video/mp4');
            self::fail('no exception');
        } catch (ServiceUnavailableException) {
        }
        self::assertCount(1, $this->api->requests);

        $this->api->requests = [];
        $this->api->queue = [new ConnectException('reset', new Request('POST', FakeApi::BASE_URL))];
        try {
            $this->videxo->uploads->create('talk.mp4', 3, 'video/mp4');
            self::fail('no exception');
        } catch (ConnectionException) {
        }
        self::assertCount(1, $this->api->requests);
    }

    public function testDoesNotRetryOtherStatuses(): void
    {
        $this->api->queue = [new Response(500, [], 'no'), new Response(200)];
        try {
            $this->videxo->fonts->list();
        } catch (\Videxo\Exception\ApiException) {
        }
        self::assertCount(1, $this->api->requests);
    }
}
