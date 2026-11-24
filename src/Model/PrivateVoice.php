<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * A private voice of the account, from a short recording of one person.
 */
final readonly class PrivateVoice implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $versionId,
        /**
         * Known values: `pl`, `en`.
         */
        public string $locale,
        /**
         * Known values: `uploading`, `ready`, `deleting`, `deleted`.
         */
        public string $state,
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
            versionId: Read::string($data, 'version_id'),
            locale: Read::string($data, 'locale'),
            state: Read::string($data, 'state'),
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
            'version_id' => $this->versionId,
            'locale' => $this->locale,
            'state' => $this->state,
        ];
    }
}
