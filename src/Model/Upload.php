<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * A one-time upload URL and the input URL a job takes once the bytes arrived.
 */
final readonly class Upload implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $inputUrl,
        public string $uploadUrl,
        public \DateTimeImmutable $uploadUrlExpiresAt,
        public \DateTimeImmutable $expiresAt,
        public int $maxSizeBytes,
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
            inputUrl: Read::string($data, 'input_url'),
            uploadUrl: Read::string($data, 'upload_url'),
            uploadUrlExpiresAt: Read::dateTime($data, 'upload_url_expires_at'),
            expiresAt: Read::dateTime($data, 'expires_at'),
            maxSizeBytes: Read::int($data, 'max_size_bytes'),
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
            'input_url' => $this->inputUrl,
            'upload_url' => $this->uploadUrl,
            'upload_url_expires_at' => Read::format($this->uploadUrlExpiresAt),
            'expires_at' => Read::format($this->expiresAt),
            'max_size_bytes' => $this->maxSizeBytes,
        ];
    }
}
