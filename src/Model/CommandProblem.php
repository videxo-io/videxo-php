<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * Why an ffmpeg command is not accepted.
 */
final readonly class CommandProblem implements \JsonSerializable
{
    public function __construct(
        /**
         * Stable command validation code, e.g. `filter_forbidden` (docs: command rules).
         */
        public string $code,
        public string $message,
        public ?string $option,
        public ?string $filter,
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
            message: Read::string($data, 'message'),
            option: Read::nullableString($data, 'option'),
            filter: Read::nullableString($data, 'filter'),
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
            'message' => $this->message,
            ...($this->option === null ? [] : ['option' => $this->option]),
            ...($this->filter === null ? [] : ['filter' => $this->filter]),
        ];
    }
}
