<?php

declare(strict_types=1);

namespace Videxo\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Videxo\Exception\WaitTimeoutException;
use Videxo\Model\JobState;
use Videxo\TimeoutClientInterface;
use Videxo\Videxo;

final class WaitDeadlineTest extends TestCase
{
    public function testRetryAfterCannotExceedWaitTimeout(): void
    {
        $limited = $this->api->problem('rate_limited', headers: ['Retry-After' => '60']);
        $this->api->queue = [$limited, $limited, FakeApi::json(200, $this->api->fixture('jobs.succeeded'))];
        $start = $this->clock->now();
        try {
            $this->videxo->jobs->wait('job_test', timeout: 1);
            self::fail('wait returned success after ' . ($this->clock->now() - $start) . ' seconds');
        } catch (WaitTimeoutException $error) {
            self::assertSame(1.0, $this->clock->now() - $start);
            self::assertNull($error->job);
            self::assertCount(1, $this->api->requests);
        }
    }

    public function testLateTerminalResponseIsNotAccepted(): void
    {
        $http = new class ($this->clock, $this->api) implements ClientInterface {
            public function __construct(private readonly FakeClock $clock, private readonly FakeApi $api) {}

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->clock->time += 2;
                return FakeApi::json(200, $this->api->fixture('jobs.succeeded'));
            }
        };
        $client = new Videxo(FakeApi::KEY, httpClient: $http, clock: $this->clock);
        $this->expectException(WaitTimeoutException::class);
        $client->jobs->wait('job_test', timeout: 1);
    }

    public function testDeadlineKeepsTheLastTimelyJob(): void
    {
        $this->api->queue = [
            FakeApi::json(200, $this->api->fixture('jobs.running')),
            $this->api->problem('rate_limited', headers: ['Retry-After' => '60']),
        ];
        try {
            $this->videxo->jobs->wait('job_test', timeout: 2, interval: 0.5);
            self::fail('no timeout');
        } catch (WaitTimeoutException $error) {
            self::assertSame(JobState::Running, $error->job?->state);
            self::assertSame([0.5, 1.5], $this->clock->sleeps);
            self::assertCount(2, $this->api->requests);
        }
    }

    public function testNoReadStartsWithoutTimeRemaining(): void
    {
        try {
            $this->videxo->jobs->wait('job_test', timeout: 0);
            self::fail('no timeout');
        } catch (WaitTimeoutException) {
            self::assertSame([], $this->api->requests);
        }
    }

    public function testGuzzleAttemptReceivesTheRemainingBudget(): void
    {
        $timeouts = [];
        $connectTimeouts = [];
        $handler = HandlerStack::create(new MockHandler([
            $this->api->problem('rate_limited', headers: ['Retry-After' => '1']),
            FakeApi::json(200, $this->api->fixture('jobs.succeeded')),
        ]));
        $handler->push(Middleware::tap(function (RequestInterface $request, array $options) use (&$timeouts, &$connectTimeouts): void {
            $timeouts[] = $options['timeout'];
            $connectTimeouts[] = $options['connect_timeout'];
            self::assertFalse($options['allow_redirects']);
            self::assertFalse($options['http_errors']);
        }));
        $http = new Client(['timeout' => 60, 'connect_timeout' => 10, 'handler' => $handler]);
        $client = new Videxo(FakeApi::KEY, httpClient: $http, clock: $this->clock);
        self::assertSame(JobState::Succeeded, $client->jobs->wait('job_test', timeout: 2)->state);
        self::assertSame([2.0, 1.0], $timeouts);
        self::assertSame([2.0, 1.0], $connectTimeouts);
    }

    public function testNetworkRetryIsBoundedAndLateFailureBecomesWaitTimeout(): void
    {
        $http = new class ($this->clock) implements ClientInterface {
            public int $attempts = 0;
            public function __construct(private readonly FakeClock $clock) {}

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->attempts++;
                $this->clock->time += 1;
                throw new \GuzzleHttp\Exception\ConnectException('timed out', $request);
            }
        };
        $client = new Videxo(FakeApi::KEY, httpClient: $http, clock: $this->clock);
        try {
            $client->jobs->wait('job_test', timeout: 1);
            self::fail('no timeout');
        } catch (WaitTimeoutException) {
            self::assertSame(1, $http->attempts);
            self::assertSame([], $this->clock->sleeps);
        }
    }

    public function testCustomTimeoutExtensionReceivesTheRemainingBudget(): void
    {
        $http = new class ($this->api) implements TimeoutClientInterface {
            /** @var list<float> */
            public array $timeouts = [];
            public function __construct(private readonly FakeApi $api) {}

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                throw new \LogicException('The bounded method must be used.');
            }

            public function sendRequestWithTimeout(RequestInterface $request, float $timeout): ResponseInterface
            {
                $this->timeouts[] = $timeout;
                return FakeApi::json(200, $this->api->fixture(count($this->timeouts) === 1 ? 'jobs.running' : 'jobs.succeeded'));
            }
        };
        $client = new Videxo(FakeApi::KEY, httpClient: $http, clock: $this->clock);
        self::assertSame(JobState::Succeeded, $client->jobs->wait('job_test', timeout: 1, interval: 0.25)->state);
        self::assertSame([1.0, 0.75], $http->timeouts);
    }

    public function testGuzzleKeepsAShorterConfiguredTimeout(): void
    {
        $handler = HandlerStack::create(new MockHandler([FakeApi::json(200, $this->api->fixture('jobs.succeeded'))]));
        $handler->push(Middleware::tap(static function (RequestInterface $request, array $options): void {
            self::assertSame(0.25, $options['timeout']);
            self::assertSame(0.1, $options['connect_timeout']);
        }));
        $client = new Videxo(FakeApi::KEY, httpClient: new Client(['timeout' => 0.25, 'connect_timeout' => 0.1, 'handler' => $handler]), clock: $this->clock);
        self::assertSame(JobState::Succeeded, $client->jobs->wait('job_test', timeout: 1)->state);
    }

    public function testKeepsZeroIntervalPollingCompatible(): void
    {
        $this->api->queue = [
            FakeApi::json(200, $this->api->fixture('jobs.running')),
            FakeApi::json(200, $this->api->fixture('jobs.succeeded')),
        ];
        self::assertSame(JobState::Succeeded, $this->videxo->jobs->wait('job_test', timeout: 1, interval: 0)->state);
        self::assertSame([0.0], $this->clock->sleeps);
    }

}
