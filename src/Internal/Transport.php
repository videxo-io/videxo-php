<?php

declare(strict_types=1);

namespace Videxo\Internal;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Videxo\Clock;
use Videxo\Credentials;
use Videxo\Exception\ApiException;
use Videxo\Exception\ConnectionException;
use Videxo\Exception\UnexpectedResponseException;
use Videxo\TimeoutClientInterface;

/**
 * One request with its retries: backoff with jitter after 429, 502, 503, 504 and a network error or
 * timeout, honouring `Retry-After`, at most `maxRetries` times. A retry sends the same request: the
 * same body and the same Idempotency-Key.
 *
 * The retry policies:
 * - `safe`: may run twice (reads, cancel, compile, deleting files, and creations with an
 *   Idempotency-Key): every retryable failure is retried.
 * - `rate_limit`: not safe to repeat (`POST /v1/uploads` takes no Idempotency-Key): retried only
 *   after a 429, which the API answers before it does anything.
 * - `never`: sent once (the one-time upload URL).
 *
 * @internal
 */
final class Transport
{
    private const RETRY_STATUSES = [429, 502, 503, 504];

    /** A Retry-After longer than this is not waited for: the answer is returned as it is. */
    public const MAX_RETRY_AFTER_SECONDS = 60;

    /** @var \Closure(): float */
    private readonly \Closure $random;

    /** @param (\Closure(): float)|null $random A number from 0 to 1, for the jitter. */
    public function __construct(
        public readonly ClientInterface $http,
        public readonly RequestFactoryInterface $requests,
        public readonly StreamFactoryInterface $streams,
        public readonly string $baseUrl,
        private readonly Credentials $credentials,
        public readonly int $maxRetries,
        public readonly Clock $clock,
        private readonly string $userAgent,
        ?\Closure $random = null,
    ) {
        $this->random = $random ?? static fn (): float => mt_rand() / mt_getrandmax();
    }

    /**
     * A request to the API at `$path`, with its credentials and a JSON body when there is one.
     *
     * @param array<array-key, mixed>|object|null $body
     * @param array<string, string|int> $query
     * @param array<string, string> $headers
     */
    public function apiRequest(string $method, string $path, array|object|null $body = null, array $query = [], array $headers = []): RequestInterface
    {
        $url = $this->baseUrl . $path . ($query === [] ? '' : '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986));
        $request = $this->requests->createRequest($method, $url)
            ->withHeader('Authorization', $this->credentials->authorization())
            ->withHeader('Accept', 'application/json')
            ->withHeader('User-Agent', $this->userAgent);
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        if ($body !== null) {
            $json = json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
            $request = $request->withHeader('Content-Type', 'application/json')->withBody($this->streams->createStream($json));
        }
        return $request;
    }

    /**
     * Sends an API request and answers its JSON object; an error answer throws its ApiException.
     *
     * @param 'safe'|'rate_limit'|'never' $retry
     * @return array<string, mixed>
     */
    public function json(RequestInterface $request, string $retry, ?float $deadline = null): array
    {
        $response = $this->send($request, $retry, $deadline);
        $text = (string) $response->getBody();
        $status = $response->getStatusCode();
        if ($status >= 400) {
            throw ApiException::fromResponse($response, $text);
        }
        if ($status < 200 || $status >= 300) {
            throw new UnexpectedResponseException("The API answered {$status} where it answers with JSON.");
        }
        try {
            $data = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $error) {
            throw new UnexpectedResponseException('The API answered with a body that is not JSON.', 0, $error);
        }
        if (!is_array($data)) {
            throw new UnexpectedResponseException('The API answered with JSON that is not an object.');
        }
        /** @var array<string, mixed> $data */
        return $data;
    }

    /**
     * Sends `$request` with the retries of `$retry` and answers the last response, whatever its
     * status. A request that got no answer throws a ConnectionException.
     *
     * @param 'safe'|'rate_limit'|'never' $retry
     */
    public function send(RequestInterface $request, string $retry, ?float $deadline = null): ResponseInterface
    {
        $maxRetries = $retry === 'never' ? 0 : $this->maxRetries;
        for ($attempt = 0; ; $attempt++) {
            $this->remaining($deadline);
            $last = $attempt >= $maxRetries;
            $body = $request->getBody();
            if ($attempt > 0 && $body->isSeekable()) {
                $body->rewind();
            }
            try {
                $response = $this->attempt($request, $deadline);
                $this->remaining($deadline);
            } catch (ClientExceptionInterface $error) {
                $this->remaining($deadline);
                $failure = $this->connectionError($request, $error);
                if ($last || $retry !== 'safe' || !$error instanceof NetworkExceptionInterface || !$body->isSeekable()) {
                    throw $failure;
                }
                $this->pause($this->backoff($attempt), $deadline);
                continue;
            }
            $status = $response->getStatusCode();
            $retryable = in_array($status, self::RETRY_STATUSES, true) && ($retry === 'safe' || $status === 429);
            if ($last || !$retryable || !$body->isSeekable()) {
                return $response;
            }
            $retryAfter = ApiException::retryAfterSeconds($response->getHeaderLine('Retry-After'), (int) $this->clock->now());
            if ($retryAfter !== null && $retryAfter > self::MAX_RETRY_AFTER_SECONDS) {
                return $response;
            }
            $this->pause($retryAfter ?? $this->backoff($attempt), $deadline);
        }
    }

    private function remaining(?float $deadline): ?float
    {
        if ($deadline === null) {
            return null;
        }
        $left = $deadline - $this->clock->now();
        if ($left <= 0) {
            throw new DeadlineExceeded();
        }
        return $left;
    }

    private function pause(float $seconds, ?float $deadline): void
    {
        $left = $this->remaining($deadline);
        $this->clock->sleep($left === null ? $seconds : min($seconds, $left));
        $this->remaining($deadline);
    }

    private function attempt(RequestInterface $request, ?float $deadline): ResponseInterface
    {
        $left = $this->remaining($deadline);
        if ($left !== null && $this->http instanceof TimeoutClientInterface) {
            return $this->http->sendRequestWithTimeout($request, $left);
        }
        if ($left !== null && $this->http instanceof GuzzleClient) {
            // sendRequest() has no options. Keep its PSR-18 redirect/status semantics when using send().
            $configured = $this->http->getConfig('timeout');
            $timeout = is_int($configured) || is_float($configured) ? $configured : 0;
            $configuredConnect = $this->http->getConfig('connect_timeout');
            $connectTimeout = is_int($configuredConnect) || is_float($configuredConnect) ? $configuredConnect : 0;
            return $this->http->send($request, [
                'timeout' => $timeout > 0 ? min($timeout, $left) : $left,
                'connect_timeout' => $connectTimeout > 0 ? min($connectTimeout, $left) : $left,
                'allow_redirects' => false,
                'http_errors' => false,
            ]);
        }
        // A plain PSR-18 client cannot be interrupted; check its result against the deadline above.
        return $this->http->sendRequest($request);
    }

    /** The wait before retry `$attempt` (0-based) without a Retry-After: 0.5 s doubling to 8 s, with jitter. */
    public function backoff(int $attempt): float
    {
        return min(8.0, 0.5 * 2 ** $attempt) * (0.5 + ($this->random)() / 2);
    }

    private function connectionError(RequestInterface $request, ClientExceptionInterface $error): ConnectionException
    {
        $what = $request->getMethod() . ' ' . $request->getUri()->getPath();
        $message = $this->credentials->redact($error->getMessage());
        // The cause is kept only when its message does not hold the secret.
        $previous = $message === $error->getMessage() ? $error : null;
        if (preg_match('/timed out|timeout|cURL error 28\b/i', $message) === 1) {
            return new ConnectionException('timeout', "{$what} got no answer in time: {$message}", $previous);
        }
        return new ConnectionException('network_error', "{$what} failed: {$message}", $previous);
    }
}
