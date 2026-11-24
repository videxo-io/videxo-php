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
 * @phpstan-import-type JobNotice from Types
 * @phpstan-import-type JobResponseSpec from Types
 * @phpstan-import-type JobStep from Types
 */
final readonly class Job implements \JsonSerializable
{
    /**
     * @param JobResponseSpec|null $spec
     * @param array<array-key, string>|null $inputFiles
     * @param array<array-key, string>|null $outputFiles
     * @param list<JobStep> $steps
     * @param list<JobNotice> $notices
     * @param array<array-key, string> $metadata
     * @param list<JobOutput> $outputs
     * @param array<string, mixed>|null $ai
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
         * otherwise.
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
        /**
         * What the job runs, in order; a command job has one step, `ffmpeg`.
         */
        public array $steps = [],
        /**
         * What Videxo changed in the request: each encoder effort option of a command (`-preset`,
         * `-threads`, `-cpu-used`, ...), replaced by the speed tier's settings. Empty when nothing
         * was replaced.
         */
        public array $notices = [],
        /**
         * An AI job's clip once its input is read: the seconds billed, the credits a success costs
         * before the data used, and the expected wait. Null before then, and for every other job.
         */
        public ?array $ai = null,
        /**
         * Why an active job is waiting, when known. Null or absent otherwise. Does not change
         * state, holds or billing. Known values: `credits`.
         */
        public ?string $waitingReason = null,
        /**
         * When the job's content was erased, 12 months after it was created. Present only then:
         * `spec`, `command`, `input_files`, `output_files`, `ffmpeg_command` and `webhook_url` are
         * null, `steps`, `notices` and `outputs` empty, `metadata` empty, and `error` has no log
         * tail. The id, state, times and usage stay.
         */
        public ?\DateTimeImmutable $contentErasedAt = null,
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
        /** @var JobResponseSpec|null $spec */
        $spec = Read::nullableShape($data, 'spec');
        /** @var list<JobStep> $steps */
        $steps = array_key_exists('steps', $data) ? Read::list($data, 'steps', static fn (array $item): array => $item) : [];
        /** @var list<JobNotice> $notices */
        $notices = array_key_exists('notices', $data) ? Read::list($data, 'notices', static fn (array $item): array => $item) : [];
        return new self(
            id: Read::string($data, 'id'),
            state: JobState::tryFrom(Read::string($data, 'state')) ?? JobState::Unknown,
            waitingReason: array_key_exists('waiting_reason', $data) ? Read::nullableString($data, 'waiting_reason') : null,
            kind: Read::string($data, 'kind'),
            operation: Read::nullableString($data, 'operation'),
            spec: $spec,
            command: Read::nullableString($data, 'command'),
            inputFiles: Read::nullableStringMap($data, 'input_files'),
            outputFiles: Read::nullableStringMap($data, 'output_files'),
            ffmpegCommand: Read::nullableString($data, 'ffmpeg_command'),
            steps: $steps,
            notices: $notices,
            webhookUrl: Read::nullableString($data, 'webhook_url'),
            metadata: Read::stringMap($data, 'metadata'),
            progress: Read::nullableFloat($data, 'progress'),
            error: Read::nullableModel($data, 'error', JobError::fromArray(...)),
            outputs: Read::list($data, 'outputs', JobOutput::fromArray(...)),
            ai: array_key_exists('ai', $data) ? Read::nullableObject($data, 'ai') : null,
            usage: Read::nullableModel($data, 'usage', JobUsage::fromArray(...)),
            contentErasedAt: array_key_exists('content_erased_at', $data) ? Read::nullableDateTime($data, 'content_erased_at') : null,
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
            ...($this->waitingReason === null ? [] : ['waiting_reason' => $this->waitingReason]),
            'kind' => $this->kind,
            'operation' => $this->operation,
            'spec' => $this->spec === null ? null : BodyShape::apply('JobResponseSpec', $this->spec),
            'command' => $this->command,
            'input_files' => $this->inputFiles === null ? null : (object) $this->inputFiles,
            'output_files' => $this->outputFiles === null ? null : (object) $this->outputFiles,
            'ffmpeg_command' => $this->ffmpegCommand,
            'steps' => array_map(static fn (array $item): array|object => BodyShape::apply('JobStep', $item), $this->steps),
            'notices' => array_map(static fn (array $item): array|object => BodyShape::apply('JobNotice', $item), $this->notices),
            'webhook_url' => $this->webhookUrl,
            'metadata' => (object) $this->metadata,
            'progress' => $this->progress,
            'error' => $this->error,
            'outputs' => $this->outputs,
            ...($this->ai === null ? [] : ['ai' => $this->ai]),
            'usage' => $this->usage,
            ...($this->contentErasedAt === null ? [] : ['content_erased_at' => Read::nullableFormat($this->contentErasedAt)]),
            'created_at' => Read::format($this->createdAt),
            'started_at' => Read::nullableFormat($this->startedAt),
            'finished_at' => Read::nullableFormat($this->finishedAt),
        ];
    }
}
