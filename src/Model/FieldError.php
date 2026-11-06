<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * One field that failed: `detail` and exactly one of `pointer`, `parameter` or `header`.
 */
final readonly class FieldError implements \JsonSerializable
{
    public function __construct(
        /**
         * What is wrong with this field.
         */
        public string $detail,
        /**
         * A JSON Pointer into the body, as a URI fragment: `#/outputs/0/resize/fit`; `#` is the
         * whole body.
         */
        public ?string $pointer,
        /**
         * The query or path parameter, such as `cursor`.
         */
        public ?string $parameter,
        /**
         * The request header, such as `Idempotency-Key`.
         */
        public ?string $header,
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
            detail: Read::string($data, 'detail'),
            pointer: Read::nullableString($data, 'pointer'),
            parameter: Read::nullableString($data, 'parameter'),
            header: Read::nullableString($data, 'header'),
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
            'detail' => $this->detail,
            'pointer' => $this->pointer,
            'parameter' => $this->parameter,
            'header' => $this->header,
        ];
    }
}
