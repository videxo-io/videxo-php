<?php

declare(strict_types=1);

namespace Videxo\Tests;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use Videxo\Exception\UnexpectedResponseException;
use Videxo\Exception\WebhookVerificationException;
use Videxo\Model\JobState;
use Videxo\Model\WebhookEventType;
use Videxo\Webhook;

final class WebhookTest extends TestCase
{
    private string $secret;

    private string $payload;

    protected function setUp(): void
    {
        parent::setUp();
        // Made at run time, so no file holds anything that looks like a signing secret.
        $this->secret = 'whsec_' . base64_encode(str_repeat('k', 32));
        $this->payload = (string) json_encode($this->api->fixture('webhook'), JSON_UNESCAPED_SLASHES);
    }

    /**
     * The headers of a delivery signed with `$secret` at the clock's time, `$age` seconds ago.
     *
     * @return array{'webhook-id': string, 'webhook-timestamp': string, 'webhook-signature': string}
     */
    private function signed(string $payload, ?string $secret = null, int $age = 0, string $id = 'msg_2k9x0c2mfq8w1n7r5t4b6v2z'): array
    {
        $timestamp = (string) ((int) $this->clock->now() - $age);
        $key = base64_decode(substr($secret ?? $this->secret, 6), true);
        self::assertIsString($key);
        $signature = base64_encode(hash_hmac('sha256', "{$id}.{$timestamp}.{$payload}", $key, true));
        return ['webhook-id' => $id, 'webhook-timestamp' => $timestamp, 'webhook-signature' => "v1,{$signature}"];
    }

    private function webhook(): Webhook
    {
        return new Webhook($this->secret, clock: $this->clock);
    }

    public function testAnswersTheEventOfASignedDelivery(): void
    {
        $event = $this->webhook()->verify($this->payload, $this->signed($this->payload));
        self::assertSame('msg_2k9x0c2mfq8w1n7r5t4b6v2z', $event->id);
        self::assertSame('job.succeeded', $event->type);
        self::assertSame(WebhookEventType::JobSucceeded, $event->eventType());
        self::assertSame(JobState::Succeeded, $event->job?->state);
        self::assertSame('out_video', $event->job->outputs[0]->alias);
        self::assertEquals($this->api->fixture('webhook'), self::decode((string) json_encode($event)));
    }

    public function testTakesHeadersInAnyCaseAndAsLists(): void
    {
        $headers = [];
        foreach ($this->signed($this->payload) as $name => $value) {
            $headers[ucwords($name, '-')] = [$value];
        }
        self::assertSame('job.succeeded', $this->webhook()->verify($this->payload, $headers)->type);
    }

    public function testAcceptsEitherSignatureAfterARotation(): void
    {
        $old = 'whsec_' . base64_encode(str_repeat('o', 32));
        $headers = $this->signed($this->payload);
        $headers['webhook-signature'] = $this->signed($this->payload, $old)['webhook-signature'] . ' ' . $headers['webhook-signature'];
        self::assertSame('job.succeeded', $this->webhook()->verify($this->payload, $headers)->type);
        self::assertSame('job.succeeded', (new Webhook($old, clock: $this->clock))->verify($this->payload, $headers)->type);
    }

    public function testVerifiesAPsr7Request(): void
    {
        $request = new Request('POST', 'https://example.com/webhooks/videxo', $this->signed($this->payload), $this->payload);
        $request->getBody()->getContents();
        self::assertSame('job.succeeded', $this->webhook()->verifyRequest($request)->type);
    }

    /** @return iterable<string, array{string}> */
    public static function badSignatures(): iterable
    {
        yield 'only a version' => ['v1'];
        yield 'garbage' => ['garbage'];
        yield 'an empty signature' => ['v1,'];
        yield 'another version' => ['v2,c2lnbmF0dXJl'];
        yield 'a wrong signature' => ['v1,c2lnbmF0dXJl'];
        yield 'a comma list' => ['v1,a,b,c'];
    }

    #[DataProvider('badSignatures')]
    public function testRefusesABadSignatureHeader(string $signature): void
    {
        $headers = ['webhook-signature' => $signature] + $this->signed($this->payload);
        try {
            $this->webhook()->verify($this->payload, $headers);
            self::fail('no exception');
        } catch (WebhookVerificationException $error) {
            self::assertSame('invalid_signature', $error->getErrorCode());
        }
    }

    public function testRefusesAChangedBody(): void
    {
        $headers = $this->signed($this->payload);
        $this->expectExceptionMessage('No v1 signature matches the body.');
        $this->webhook()->verify($this->payload . ' ', $headers);
    }

    public function testRefusesAnOtherSecret(): void
    {
        $headers = $this->signed($this->payload, 'whsec_' . base64_encode(str_repeat('x', 32)));
        $this->expectException(WebhookVerificationException::class);
        $this->webhook()->verify($this->payload, $headers);
    }

    /** @return iterable<string, array{int, bool}> */
    public static function ages(): iterable
    {
        yield 'now' => [0, true];
        yield 'five minutes ago' => [300, true];
        yield 'five minutes in the future' => [-300, true];
        yield 'too old' => [301, false];
        yield 'too far in the future' => [-301, false];
    }

    #[DataProvider('ages')]
    public function testChecksTheTimestamp(int $age, bool $accepted): void
    {
        try {
            $this->webhook()->verify($this->payload, $this->signed($this->payload, age: $age));
            self::assertTrue($accepted);
        } catch (WebhookVerificationException $error) {
            self::assertFalse($accepted);
            self::assertSame('invalid_timestamp', $error->getErrorCode());
        }
    }

    public function testRefusesATimestampThatIsNotANumber(): void
    {
        $headers = ['webhook-timestamp' => '12e3'] + $this->signed($this->payload);
        $this->expectExceptionMessage('is not a number');
        $this->webhook()->verify($this->payload, $headers);
    }

    /** @return iterable<string, array{string}> */
    public static function headerNames(): iterable
    {
        yield 'webhook-id' => ['webhook-id'];
        yield 'webhook-timestamp' => ['webhook-timestamp'];
        yield 'webhook-signature' => ['webhook-signature'];
    }

    #[DataProvider('headerNames')]
    public function testNeedsEveryHeader(string $name): void
    {
        $headers = $this->signed($this->payload);
        unset($headers[$name]);
        try {
            $this->webhook()->verify($this->payload, $headers);
            self::fail('no exception');
        } catch (WebhookVerificationException $error) {
            self::assertSame('missing_headers', $error->errorCode);
        }
    }

    public function testRefusesASecretThatIsNotOne(): void
    {
        foreach (['', 'whsec_', 'whsec_not base64!', 'whsec_abc'] as $secret) {
            try {
                new Webhook($secret);
                self::fail("no exception for {$secret}");
            } catch (WebhookVerificationException $error) {
                self::assertSame('invalid_secret', $error->getErrorCode());
            }
        }
    }

    public function testRefusesASignedBodyThatIsNotAnEvent(): void
    {
        foreach (['not json', '[]', '{"type":"job.succeeded"}', "\xff"] as $payload) {
            try {
                $this->webhook()->verify($payload, $this->signed($payload));
                self::fail("no exception for {$payload}");
            } catch (WebhookVerificationException $error) {
                self::assertSame('invalid_payload', $error->getErrorCode());
            }
        }
    }

    public function testReadsTheSecretFromTheEnvironment(): void
    {
        $_ENV['VIDEXO_WEBHOOK_SECRET'] = $this->secret;
        try {
            $payload = $this->payload;
            // The system clock: a delivery signed just now.
            $timestamp = (string) time();
            $key = (string) base64_decode(substr($this->secret, 6), true);
            $signature = base64_encode(hash_hmac('sha256', "msg_1.{$timestamp}.{$payload}", $key, true));
            $headers = ['webhook-id' => 'msg_1', 'webhook-timestamp' => $timestamp, 'webhook-signature' => "v1,{$signature}"];
            self::assertSame('msg_1', Webhook::fromEnvironment()->verify($payload, $headers)->id);
            $_ENV['VIDEXO_WEBHOOK_SECRET'] = ' ';
            $this->expectExceptionMessage('Set VIDEXO_WEBHOOK_SECRET');
            Webhook::fromEnvironment();
        } finally {
            unset($_ENV['VIDEXO_WEBHOOK_SECRET']);
        }
    }

    public function testAJobWithNumericMetadataKeysVerifies(): void
    {
        $event = $this->api->fixture('webhook');
        $job = $this->api->fixture('jobs.succeeded');
        $job['metadata'] = (object) ['0' => 'value'];
        $event['data'] = $job;
        $payload = (string) json_encode($event);
        $verified = $this->webhook()->verify($payload, $this->signed($payload));
        self::assertSame('value', $verified->job?->metadata['0']);
    }

    public function testAnEventOfANewKindHasNoJob(): void
    {
        $payload = (string) json_encode(['type' => 'account.updated', 'timestamp' => '2026-11-04T12:00:00.000Z', 'data' => ['id' => 'acc_1']]);
        $event = $this->webhook()->verify($payload, $this->signed($payload));
        self::assertNull($event->eventType());
        self::assertNull($event->job);
        self::assertSame(['id' => 'acc_1'], $event->data);
    }

    public function testAJobThisVersionCannotReadIsNotAVerificationFailure(): void
    {
        $event = $this->api->fixture('webhook');
        self::assertIsArray($event['data']);
        $event['data']['id'] = 42;
        $payload = (string) json_encode($event);
        $this->expectException(UnexpectedResponseException::class);
        $this->webhook()->verify($payload, $this->signed($payload));
    }
}
