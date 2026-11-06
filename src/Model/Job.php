<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\BodyShape;
use Videxo\Internal\Read;
use Videxo\Types;

/**
 * A job: what it runs, its state, and once it ended its outputs, error and usage.
 *
 * @phpstan-import-type JobSpec from Types
 */
final readonly class Job implements \JsonSerializable
{
    /**
     * @param JobSpec|null $spec
     * @param array<array-key, string>|null $inputFiles
     * @param array<array-key, string>|null $outputFiles
     * @param array<array-key, string> $metadata
     * @param list<JobOutput> $outputs
     */
    public function __construct(
        /**
         * `job_` and 24 lowercase letters and digits.
         */
        public string $id,
        public JobState $state,
        /**
         * How the job was described: a command body or an operations body. Known values: `command`,
         * `operations`.
         */
        public string $kind,
        /**
         * The per-operation endpoint that made the job (`transcode`, `thumbnails`, ...); null
         * otherwise. Known values: `transcode`, `thumbnails`, `storyboard`, `gif`, `audio`,
         * `concat`, `probe`.
         */
        public ?string $operation,
        /**
         * The operations body as accepted, presets expanded and defaults filled; null for a command
         * job.
         */
        public ?array $spec,
        /**
         * The ffmpeg arguments the job runs; for an operations job, the compiled command without
         * its metadata outputs. Null when every output is metadata (no ffmpeg runs).
         */
        public ?string $command,
        /**
         * The inputs of `command`; an input only metadata outputs read is not listed. Null without
         * a command.
         */
        public ?array $inputFiles,
        /**
         * The outputs of `command`; metadata outputs are not listed. Null without a command.
         */
        public ?array $outputFiles,
        /**
         * One shell line that runs the command with a local ffmpeg: aliases replaced by the input
         * URLs (an upload by its file name) and the output file names. Null without a command.
         */
        public ?string $ffmpegCommand,
        public ?string $webhookUrl,
        public array $metadata,
        /**
         * From 0 to 1 while running; null when unknown.
         */
        public ?float $progress,
        /**
         * Why the job failed; null unless the state is failed.
         */
        public ?JobError $error,
        /**
         * The stored files, once the job succeeded.
         */
        public array $outputs,
        /**
         * What the job used, once it ended.
         */
        public ?JobUsage $usage,
        public \DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $startedAt,
        public ?\DateTimeImmutable $finishedAt,
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
        /** @var JobSpec|null $spec */
        $spec = Read::nullableShape($data, 'spec');
        return new self(
            id: Read::string($data, 'id'),
            state: JobState::tryFrom(Read::string($data, 'state')) ?? JobState::Unknown,
            kind: Read::string($data, 'kind'),
            operation: Read::nullableString($data, 'operation'),
            spec: $spec,
            command: Read::nullableString($data, 'command'),
            inputFiles: Read::nullableStringMap($data, 'input_files'),
            outputFiles: Read::nullableStringMap($data, 'output_files'),
            ffmpegCommand: Read::nullableString($data, 'ffmpeg_command'),
            webhookUrl: Read::nullableString($data, 'webhook_url'),
            metadata: Read::stringMap($data, 'metadata'),
            progress: Read::nullableFloat($data, 'progress'),
            error: Read::nullableModel($data, 'error', JobError::fromArray(...)),
            outputs: Read::list($data, 'outputs', JobOutput::fromArray(...)),
            usage: Read::nullableModel($data, 'usage', JobUsage::fromArray(...)),
            createdAt: Read::dateTime($data, 'created_at'),
            startedAt: Read::nullableDateTime($data, 'started_at'),
            finishedAt: Read::nullableDateTime($data, 'finished_at'),
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
            'state' => $this->state,
            'kind' => $this->kind,
            'operation' => $this->operation,
            'spec' => $this->spec === null ? null : BodyShape::apply('JobSpec', $this->spec),
            'command' => $this->command,
            'input_files' => $this->inputFiles === null ? null : (object) $this->inputFiles,
            'output_files' => $this->outputFiles === null ? null : (object) $this->outputFiles,
            'ffmpeg_command' => $this->ffmpegCommand,
            'webhook_url' => $this->webhookUrl,
            'metadata' => (object) $this->metadata,
            'progress' => $this->progress,
            'error' => $this->error,
            'outputs' => $this->outputs,
            'usage' => $this->usage,
            'created_at' => Read::format($this->createdAt),
            'started_at' => Read::nullableFormat($this->startedAt),
            'finished_at' => Read::nullableFormat($this->finishedAt),
        ];
    }
}
