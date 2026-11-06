<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\BodyShape;
use Videxo\Internal\Read;
use Videxo\Types;

/**
 * What a job would run: the spec, the command and its files; no job is made.
 *
 * @phpstan-import-type JobSpec from Types
 */
final readonly class CompileResult implements \JsonSerializable
{
    /**
     * @param JobSpec $spec
     * @param array<array-key, string>|null $inputFiles
     * @param array<array-key, string>|null $outputFiles
     */
    public function __construct(
        public array $spec,
        /**
         * The compiled ffmpeg command, without metadata outputs; null when every output is
         * metadata.
         */
        public ?string $command,
        public ?array $inputFiles,
        public ?array $outputFiles,
        /**
         * One shell line for a local ffmpeg; an upload input appears as `./<input name>.upload`,
         * since compile never looks uploads up. Null when every output is metadata.
         */
        public ?string $ffmpegCommand,
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
        /** @var JobSpec $spec */
        $spec = Read::shape($data, 'spec');
        return new self(
            spec: $spec,
            command: Read::nullableString($data, 'command'),
            inputFiles: Read::nullableStringMap($data, 'input_files'),
            outputFiles: Read::nullableStringMap($data, 'output_files'),
            ffmpegCommand: Read::nullableString($data, 'ffmpeg_command'),
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
            'spec' => BodyShape::apply('JobSpec', $this->spec),
            'command' => $this->command,
            'input_files' => $this->inputFiles === null ? null : (object) $this->inputFiles,
            'output_files' => $this->outputFiles === null ? null : (object) $this->outputFiles,
            'ffmpeg_command' => $this->ffmpegCommand,
        ];
    }
}
