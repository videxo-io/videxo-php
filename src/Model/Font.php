<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * A font a text layer can name.
 */
final readonly class Font implements \JsonSerializable
{
    /**
     * @param list<string> $scripts
     */
    public function __construct(
        /**
         * The id a text layer names as `font`.
         */
        public string $id,
        public string $family,
        /**
         * 400 regular, 700 bold. Known values: 400, 700.
         */
        public int $weight,
        /**
         * The scripts the font covers in full.
         */
        public array $scripts,
        /**
         * The SIL Open Font License 1.1. Known values: `OFL-1.1`.
         */
        public string $license,
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
            id: Read::string($data, 'id'),
            family: Read::string($data, 'family'),
            weight: Read::int($data, 'weight'),
            scripts: Read::stringList($data, 'scripts'),
            license: Read::string($data, 'license'),
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
            'id' => $this->id,
            'family' => $this->family,
            'weight' => $this->weight,
            'scripts' => $this->scripts,
            'license' => $this->license,
        ];
    }
}
