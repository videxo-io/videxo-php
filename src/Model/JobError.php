<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * Why a job failed.
 */
final readonly class JobError implements \JsonSerializable
{
    public function __construct(
        /**
         * The stable error code (the Errors page lists each one). Known values: `limit_exceeded`,
         * `out_of_credits`, `service_unavailable`, `internal_error`, `input_unreachable`,
         * `processing_failed`.
         */
        public string $code,
        /**
         * The documented finer case of the code, or null.
         */
        public ?string $reason,
        /**
         * What happened, for people; it may change.
         */
        public string $message,
        /**
         * Whether creating the same job again can succeed without a change.
         */
        public bool $retryable = false,
        /**
         * With `processing_failed` `ffmpeg_error`: ffmpeg's last lines about the media, once
         * available; null otherwise.
         */
        public ?string $logTail = null,
    ) {
    }

    /**
     * Reads the API's JSON answer, decoded to an array. Throws an UnexpectedResponseException when a
     * field is missing or has another type.
     *
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: Read::string($data, 'code'),
            reason: Read::nullableString($data, 'reason'),
            message: Read::string($data, 'message'),
            retryable: array_key_exists('retryable', $data) ? Read::bool($data, 'retryable') : false,
            logTail: array_key_exists('log_tail', $data) ? Read::nullableString($data, 'log_tail') : null,
        );
    }

    /**
     * The API's JSON shape, with its snake_case field names.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'reason' => $this->reason,
            'message' => $this->message,
            'retryable' => $this->retryable,
            'log_tail' => $this->logTail,
        ];
    }
}
