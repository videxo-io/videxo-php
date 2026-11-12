<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\BodyShape;
use Videxo\Internal\Read;
use Videxo\Types;

/**
 * A named set of output options.
 *
 * @phpstan-import-type PresetOptions from Types
 */
final readonly class Preset implements \JsonSerializable
{
    /**
     * @param PresetOptions $output
     */
    public function __construct(
        public string $name,
        /**
         * Known values: `video`, `audio`, `image`, `storyboard`, `gif`, `metadata`, `scenes`,
         * `transcript`, `adaptive`.
         */
        public string $type,
        public string $description,
        /**
         * A deprecated preset keeps working through /v1.
         */
        public bool $deprecated,
        public array $output,
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
        /** @var PresetOptions $output */
        $output = Read::shape($data, 'output');
        return new self(
            name: Read::string($data, 'name'),
            type: Read::string($data, 'type'),
            description: Read::string($data, 'description'),
            deprecated: Read::bool($data, 'deprecated'),
            output: $output,
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
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'deprecated' => $this->deprecated,
            'output' => BodyShape::apply('PresetOptions', $this->output),
        ];
    }
}
