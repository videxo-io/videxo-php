<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * What a job used, once it ended.
 */
final readonly class JobUsage implements \JsonSerializable
{
    /**
     * @param array<string, mixed>|null $bill
     * @param array<string, mixed>|null $ai
     */
    public function __construct(
        public int $seconds,
        public int $inputBytes,
        public int $outputBytes,
        /**
         * Credits, with one decimal: the sum of the bill rounded up once, and never more than the
         * job held, which can make it less than that sum.
         */
        public float $credits,
        /**
         * What the credits are made of; null for an AI job, a job that used nothing and a job from
         * before this field.
         */
        public ?array $bill = null,
        /**
         * The settled AI charge, from persisted usage. Absent when AI is hidden or the stored
         * breakdown is incomplete.
         */
        public ?array $ai = null,
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
            seconds: Read::int($data, 'seconds'),
            inputBytes: Read::int($data, 'input_bytes'),
            outputBytes: Read::int($data, 'output_bytes'),
            credits: Read::float($data, 'credits'),
            bill: array_key_exists('bill', $data) ? Read::nullableObject($data, 'bill') : null,
            ai: array_key_exists('ai', $data) ? Read::nullableObject($data, 'ai') : null,
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
            'seconds' => $this->seconds,
            'input_bytes' => $this->inputBytes,
            'output_bytes' => $this->outputBytes,
            'credits' => $this->credits,
            'bill' => $this->bill,
            ...($this->ai === null ? [] : ['ai' => $this->ai]),
        ];
    }
}
