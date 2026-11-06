<?php

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/** The answer of an upload URL once every byte arrived; `inputUrl` is what a job takes as an input. */
final readonly class CompletedUpload implements \JsonSerializable
{
    public function __construct(
        /** `up_` and 24 lowercase letters and digits. */
        public string $id,
        /** `videxo://uploads/<id>`: use it as a job's input. */
        public string $inputUrl,
        public int $sizeBytes,
        /** The SHA-256 of the bytes, in hex. */
        public string $sha256,
        public string $contentType,
        /** When the upload is deleted unless a job uses it first. */
        public \DateTimeImmutable $expiresAt,
    ) {
    }

    /** @param array<array-key, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: Read::string($data, 'id'),
            inputUrl: Read::string($data, 'input_url'),
            sizeBytes: Read::int($data, 'size_bytes'),
            sha256: Read::string($data, 'sha256'),
            contentType: Read::string($data, 'content_type'),
            expiresAt: Read::dateTime($data, 'expires_at'),
        );
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'input_url' => $this->inputUrl,
            'size_bytes' => $this->sizeBytes,
            'sha256' => $this->sha256,
            'content_type' => $this->contentType,
            'expires_at' => Read::format($this->expiresAt),
        ];
    }
}
