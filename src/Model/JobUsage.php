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
    public function __construct(
        public int $seconds,
        public int $inputBytes,
        public int $outputBytes,
        public int $credits,
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
            credits: Read::int($data, 'credits'),
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
        ];
    }
}
