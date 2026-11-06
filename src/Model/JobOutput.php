<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * A stored file of a job, with a signed download link while it is fresh.
 */
final readonly class JobOutput implements \JsonSerializable
{
    public function __construct(
        public string $alias,
        /**
         * The output name from the request (`thumb` for every file of an image output with
         * `times`); for a command job, the alias without `out_`.
         */
        public string $name,
        public string $filename,
        public int $sizeBytes,
        public string $sha256,
        public string $contentType,
        public \DateTimeImmutable $expiresAt,
        /**
         * Signed download URL; null after deletion or retention.
         */
        public ?string $downloadUrl,
        public ?\DateTimeImmutable $downloadUrlExpiresAt,
        /**
         * When the file was deleted; null while it can be downloaded.
         */
        public ?\DateTimeImmutable $deletedAt,
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
            alias: Read::string($data, 'alias'),
            name: Read::string($data, 'name'),
            filename: Read::string($data, 'filename'),
            sizeBytes: Read::int($data, 'size_bytes'),
            sha256: Read::string($data, 'sha256'),
            contentType: Read::string($data, 'content_type'),
            expiresAt: Read::dateTime($data, 'expires_at'),
            downloadUrl: Read::nullableString($data, 'download_url'),
            downloadUrlExpiresAt: Read::nullableDateTime($data, 'download_url_expires_at'),
            deletedAt: Read::nullableDateTime($data, 'deleted_at'),
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
            'alias' => $this->alias,
            'name' => $this->name,
            'filename' => $this->filename,
            'size_bytes' => $this->sizeBytes,
            'sha256' => $this->sha256,
            'content_type' => $this->contentType,
            'expires_at' => Read::format($this->expiresAt),
            'download_url' => $this->downloadUrl,
            'download_url_expires_at' => Read::nullableFormat($this->downloadUrlExpiresAt),
            'deleted_at' => Read::nullableFormat($this->deletedAt),
        ];
    }
}
