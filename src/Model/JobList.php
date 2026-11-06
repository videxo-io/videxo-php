<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * One page of jobs, newest first.
 */
final readonly class JobList implements \JsonSerializable
{
    /**
     * @param list<Job> $data
     */
    public function __construct(
        /**
         * Newest first.
         */
        public array $data,
        /**
         * Pass as `cursor` for the next page; null on the last page.
         */
        public ?string $nextCursor,
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
            data: Read::list($data, 'data', Job::fromArray(...)),
            nextCursor: Read::nullableString($data, 'next_cursor'),
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
            'data' => $this->data,
            'next_cursor' => $this->nextCursor,
        ];
    }
}
