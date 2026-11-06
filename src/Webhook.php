<?php

declare(strict_types=1);

namespace Videxo;

use Psr\Http\Message\RequestInterface;
use Videxo\Exception\UnexpectedResponseException;
use Videxo\Exception\WebhookVerificationException;
use Videxo\Internal\Environment;
use Videxo\Model\WebhookEvent;

/**
 * Verifies webhook deliveries (Standard Webhooks). A delivery is signed `v1,` and the base64
 * HMAC-SHA256 of `webhook-id.webhook-timestamp.body` with the key after `whsec_`; after a secret
 * rotation it carries a second signature, space separated, and either may match.
 *
 * ```php
 * $event = Webhook::fromEnvironment()->verify($rawBody, getallheaders());
 * ```
 */
final class Webhook
{
    /** How far a delivery's `webhook-timestamp` may be from now, either way, in seconds. */
    public const TOLERANCE_SECONDS = 300;

    private readonly string $key;

    private readonly Clock $clock;

    /**
     * @param string $secret The `whsec_` signing secret from the dashboard's Webhooks page.
     * @param int $tolerance Seconds the timestamp may differ from now.
     */
    public function __construct(
        #[\SensitiveParameter] string $secret,
        private readonly int $tolerance = self::TOLERANCE_SECONDS,
        ?Clock $clock = null,
    ) {
        $encoded = str_starts_with($secret, 'whsec_') ? substr($secret, strlen('whsec_')) : $secret;
        $key = preg_match('~^[A-Za-z0-9+/]+={0,2}$~D', $encoded) === 1 && strlen($encoded) % 4 === 0 ? base64_decode($encoded, true) : false;
        if ($key === false || $key === '') {
            throw new WebhookVerificationException('invalid_secret', 'The secret is not a whsec_ signing secret.');
        }
        $this->key = $key;
        $this->clock = $clock ?? new SystemClock();
    }

    /** A verifier with the secret in `VIDEXO_WEBHOOK_SECRET`. */
    public static function fromEnvironment(): self
    {
        $secret = Environment::get('VIDEXO_WEBHOOK_SECRET');
        if ($secret === null) {
            throw new \InvalidArgumentException('Set VIDEXO_WEBHOOK_SECRET on the server: the signing secret from the Webhooks page.');
        }
        return new self($secret);
    }

    /**
     * Checks a delivery and answers its event. `$payload` is the raw request body exactly as it
     * arrived (read it before any JSON parser touches it); `$headers` the request's headers, with
     * any case of names and a string or a list of strings each (getallheaders(), or
     * `$request->headers->all()` in Symfony and Laravel). Throws a WebhookVerificationException when
     * the headers are missing, the timestamp is more than five minutes away, no `v1` signature
     * matches or the body is not an event.
     *
     * @param array<array-key, mixed> $headers
     */
    public function verify(string $payload, array $headers): WebhookEvent
    {
        $id = self::header($headers, 'webhook-id');
        $timestamp = self::header($headers, 'webhook-timestamp');
        $signatures = self::header($headers, 'webhook-signature');
        if ($id === null || $timestamp === null || $signatures === null) {
            throw new WebhookVerificationException(
                'missing_headers',
                'The webhook-id, webhook-timestamp and webhook-signature headers are required.',
            );
        }
        if (preg_match('/^\d{1,15}$/D', $timestamp) !== 1) {
            throw new WebhookVerificationException('invalid_timestamp', 'The webhook-timestamp header is not a number.');
        }
        if (abs((int) floor($this->clock->now()) - (int) $timestamp) > $this->tolerance) {
            throw new WebhookVerificationException('invalid_timestamp', 'The webhook-timestamp is too far from the current time.');
        }

        // The signature covers the body's bytes exactly as they arrived; they are decoded only once it matched.
        $expected = base64_encode(hash_hmac('sha256', "{$id}.{$timestamp}.{$payload}", $this->key, true));
        $matched = false;
        foreach (explode(' ', $signatures) as $entry) {
            [$version, $signature] = array_pad(explode(',', $entry, 2), 2, '');
            // hash_equals() compares in constant time; every entry is compared.
            if ($version === 'v1' && $signature !== '' && hash_equals($expected, $signature)) {
                $matched = true;
            }
        }
        if (!$matched) {
            throw new WebhookVerificationException('invalid_signature', 'No v1 signature matches the body.');
        }

        try {
            $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new WebhookVerificationException('invalid_payload', 'The body is not JSON in UTF-8.');
        }
        if (!is_array($data) || !is_string($data['type'] ?? null) || !is_array($data['data'] ?? null) || !is_string($data['timestamp'] ?? null)) {
            throw new WebhookVerificationException('invalid_payload', 'The body is not a webhook event.');
        }
        try {
            return WebhookEvent::fromArray($id, $data);
        } catch (UnexpectedResponseException $error) {
            // Signed by Videxo but not readable by this version: not the sender's fault, so not a 400.
            throw new UnexpectedResponseException('The webhook event could not be read: ' . $error->getMessage(), 0, $error);
        }
    }

    /**
     * Checks a PSR-7 request (a PSR-15 middleware or a framework that gives one) and answers its event.
     * The body is read whole: bound its size before, as a proxy or the server does.
     */
    public function verifyRequest(RequestInterface $request): WebhookEvent
    {
        $body = $request->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        return $this->verify($body->getContents(), $request->getHeaders());
    }

    /** @param array<array-key, mixed> $headers */
    private static function header(array $headers, string $name): ?string
    {
        foreach ($headers as $key => $value) {
            if (strtolower((string) $key) !== $name) {
                continue;
            }
            $values = array_filter(is_array($value) ? $value : [$value], static fn ($item) => is_string($item) && $item !== '');
            return $values === [] ? null : implode(' ', $values);
        }
        return null;
    }
}
