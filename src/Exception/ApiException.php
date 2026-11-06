<?php

declare(strict_types=1);

namespace Videxo\Exception;

use Psr\Http\Message\ResponseInterface;
use Videxo\Model\CommandProblem;
use Videxo\Model\FieldError;

/**
 * An error answer of the API. A problem details answer (`application/problem+json`) fills every
 * field; an answer that is not one (a proxy's HTML page, for example) has the code `http_error`.
 * Each documented code has its own subclass, so you can catch the case you handle:
 * NotFoundException, RateLimitedException, InvalidRequestException and so on.
 *
 * getCode() is the HTTP status.
 */
class ApiException extends \RuntimeException implements VidexoException
{
    /** The subclass of each documented code. */
    private const CLASSES = [
        'authentication_failed' => AuthenticationException::class,
        'permission_denied' => PermissionDeniedException::class,
        'invalid_request' => InvalidRequestException::class,
        'command_invalid' => CommandInvalidException::class,
        'not_found' => NotFoundException::class,
        'conflict' => ConflictException::class,
        'resource_expired' => ResourceExpiredException::class,
        'limit_exceeded' => LimitExceededException::class,
        'usage_cap_reached' => UsageCapReachedException::class,
        'out_of_credits' => OutOfCreditsException::class,
        'rate_limited' => RateLimitedException::class,
        'service_unavailable' => ServiceUnavailableException::class,
        'internal_error' => InternalErrorException::class,
    ];

    /**
     * @param string $errorCode The API's documented code, or `http_error`.
     * @param string $title The code's fixed sentence.
     * @param string $detail What happened this time, for people; it may change.
     * @param string|null $type `https://videxo.io/docs/errors#<code>`; null without problem details.
     * @param string|null $reason The documented finer case of the code; null without one.
     * @param bool|null $retryable Whether the same request can succeed later; null when unknown.
     * @param string|null $requestId The answer's request id; quote it to support.
     * @param int|null $retryAfter Seconds from the answer's `Retry-After` header; null without one.
     * @param list<FieldError> $errors With `invalid_request`: one entry per field that failed.
     * @param list<CommandProblem> $problems With `command_invalid`: why the command is not accepted.
     * @param mixed $body The answer's body: decoded JSON, or the text when it is not JSON.
     */
    final public function __construct(
        public readonly int $status,
        public readonly string $errorCode,
        public readonly string $title,
        public readonly string $detail,
        public readonly ?string $type = null,
        public readonly ?string $reason = null,
        public readonly ?bool $retryable = null,
        public readonly ?string $requestId = null,
        public readonly ?int $retryAfter = null,
        public readonly array $errors = [],
        public readonly array $problems = [],
        public readonly mixed $body = null,
    ) {
        parent::__construct("{$errorCode} ({$status}): {$detail}", $status);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /** The exception for an error answer whose body was read as `$text`. */
    public static function fromResponse(ResponseInterface $response, string $text): self
    {
        $status = $response->getStatusCode();
        $retryAfter = self::retryAfterSeconds($response->getHeaderLine('Retry-After'));
        $headerId = $response->getHeaderLine('Request-Id') ?: null;
        try {
            $body = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $body = $text;
        }
        if (is_array($body) && is_string($body['code'] ?? null) && is_string($body['title'] ?? null)) {
            $class = self::CLASSES[$body['code']] ?? self::class;
            return new $class(
                status: $status,
                errorCode: $body['code'],
                title: $body['title'],
                detail: is_string($body['detail'] ?? null) ? $body['detail'] : $body['title'],
                type: is_string($body['type'] ?? null) ? $body['type'] : null,
                reason: is_string($body['reason'] ?? null) ? $body['reason'] : null,
                retryable: is_bool($body['retryable'] ?? null) ? $body['retryable'] : null,
                requestId: is_string($body['request_id'] ?? null) ? $body['request_id'] : $headerId,
                retryAfter: $retryAfter,
                errors: self::entries($body, 'errors', FieldError::fromArray(...)),
                problems: self::entries($body, 'problems', CommandProblem::fromArray(...)),
                body: $body,
            );
        }
        $phrase = $response->getReasonPhrase();
        return new self(
            status: $status,
            errorCode: 'http_error',
            title: 'The API answered with an error that is not problem details.',
            detail: "The API answered {$status}" . ($phrase !== '' ? " {$phrase}" : '') . '.',
            requestId: $headerId,
            retryAfter: $retryAfter,
            body: $body,
        );
    }

    /** Seconds of a `Retry-After` header, as seconds or an HTTP date; null when absent or unreadable. */
    public static function retryAfterSeconds(string $value, ?int $now = null): ?int
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }
        if (ctype_digit($value)) {
            return (int) $value;
        }
        $date = \DateTimeImmutable::createFromFormat('D, d M Y H:i:s \G\M\T', $value, new \DateTimeZone('UTC'));
        if ($date === false) {
            return null;
        }
        return max(0, $date->getTimestamp() - ($now ?? time()));
    }

    /**
     * The well-formed entries of a list in the body; others are left out.
     *
     * @template T
     * @param array<array-key, mixed> $body
     * @param callable(array<array-key, mixed>): T $read
     * @return list<T>
     */
    private static function entries(array $body, string $key, callable $read): array
    {
        $entries = [];
        foreach (is_array($body[$key] ?? null) ? $body[$key] : [] as $entry) {
            if (!is_array($entry)) {
                continue;
            }
            try {
                $entries[] = $read($entry);
            } catch (UnexpectedResponseException) {
                continue;
            }
        }
        return $entries;
    }
}
