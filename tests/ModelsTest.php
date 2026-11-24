<?php

declare(strict_types=1);

namespace Videxo\Tests;

use Videxo\Exception\UnexpectedResponseException;
use Videxo\Model\CompileResult;
use Videxo\Model\Job;
use Videxo\Model\JobState;

/** The models read the API's examples and give back the same JSON. */
final class ModelsTest extends TestCase
{
    public function testJobsInEveryStateRoundTrip(): void
    {
        foreach (['jobs.queued', 'jobs.running', 'jobs.succeeded', 'jobs.failed', 'jobs_job', 'command_job'] as $path) {
            $fixture = $this->api->fixture($path);
            $job = Job::fromArray($fixture);
            self::assertEquals($fixture, self::decode((string) json_encode($job)), $path);
        }
    }

    public function testReadsTypedFields(): void
    {
        $job = Job::fromArray($this->api->fixture('jobs.succeeded'));
        self::assertSame(JobState::Succeeded, $job->state);
        self::assertInstanceOf(\DateTimeImmutable::class, $job->createdAt);
        self::assertSame('2026-11-04T12:00:00.000Z', $job->createdAt->format('Y-m-d\TH:i:s.v\Z'));
        self::assertSame(300000000, $job->outputs[0]->sizeBytes);
        self::assertNotNull($job->usage);
        self::assertIsArray($job->spec);
        self::assertSame('video', $job->spec['outputs'][0]['name'] ?? null);

        $compiled = CompileResult::fromArray($this->api->fixture('compile_result'));
        self::assertEquals($this->api->fixture('compile_result'), self::decode((string) json_encode($compiled)));
    }

    public function testRefusesAFieldOfAnotherType(): void
    {
        $cases = [
            'id' => 42,
            'state' => 42,
            'progress' => 'half',
            'outputs' => [['alias' => 1]],
            'created_at' => 'yesterday',
            'metadata' => ['a' => 1],
            'input_files' => 'a',
        ];
        foreach ($cases as $field => $value) {
            $fixture = $this->api->fixture('jobs.succeeded');
            $fixture[$field] = $value;
            try {
                Job::fromArray($fixture);
                self::fail("no exception for {$field}");
            } catch (UnexpectedResponseException $error) {
                self::assertStringContainsString($field, $error->getMessage());
            }
        }
    }

    public function testAJobWithOnlyMetadataOutputsHasNoCommand(): void
    {
        $fixture = $this->api->fixture('jobs.queued');
        foreach (['command', 'input_files', 'output_files', 'ffmpeg_command'] as $field) {
            $fixture[$field] = null;
        }
        $job = Job::fromArray($fixture);
        self::assertNull($job->command);
        self::assertNull($job->inputFiles);
        self::assertNull($job->outputFiles);
        self::assertNull($job->ffmpegCommand);
        self::assertStringContainsString('"input_files":null', (string) json_encode($job));
    }

    public function testAnEmptyMapStaysAnObjectInJson(): void
    {
        $fixture = $this->api->fixture('jobs.queued');
        $fixture['metadata'] = [];
        self::assertStringContainsString('"metadata":{}', (string) json_encode(Job::fromArray($fixture)));
    }

    public function testAMapWithNumericKeysReadsAndStaysAnObject(): void
    {
        $fixture = $this->api->fixture('jobs.queued');
        $job = json_decode((string) json_encode($fixture + ['x' => 1]), true);
        self::assertIsArray($job);
        $job['metadata'] = json_decode('{"0":"value"}', true);
        $read = Job::fromArray($job);
        self::assertSame('value', $read->metadata['0']);
        self::assertStringContainsString('"metadata":{"0":"value"}', (string) json_encode($read));
    }

    public function testNestedObjectsOfTheSpecStayObjects(): void
    {
        $fixture = $this->api->fixture('jobs.queued');
        self::assertIsArray($fixture['spec']);
        $fixture['spec']['inputs'] = [];
        self::assertStringContainsString('"spec":{"inputs":{},', (string) json_encode(Job::fromArray($fixture)));
    }

    public function testReadsAnIntegerAsAProgress(): void
    {
        $fixture = $this->api->fixture('jobs.running');
        $fixture['progress'] = 1;
        self::assertSame(1.0, Job::fromArray($fixture)->progress);
    }
    public function testReadsStoredResponsesFromBeforeTheAdditionalFields(): void
    {
        $fixture = $this->api->fixture('jobs.failed');
        unset($fixture['steps'], $fixture['notices'], $fixture['ai']);
        self::assertIsArray($fixture['error']);
        unset($fixture['error']['retryable'], $fixture['error']['log_tail']);
        self::assertIsArray($fixture['usage']);
        unset($fixture['usage']['bill'], $fixture['usage']['ai']);
        $job = Job::fromArray($fixture);
        self::assertSame([], $job->steps);
        self::assertSame([], $job->notices);
        self::assertNull($job->ai);
        self::assertFalse($job->error?->retryable);
        self::assertNull($job->usage?->bill);
        self::assertNull($job->usage?->ai);

        // The original positional constructor still accepts its original arguments.
        $old = new Job($job->id, $job->state, $job->kind, $job->operation, $job->spec, $job->command,
            $job->inputFiles, $job->outputFiles, $job->ffmpegCommand, $job->webhookUrl, $job->metadata,
            $job->progress, $job->error, $job->outputs, $job->usage, $job->createdAt, $job->startedAt, $job->finishedAt);
        self::assertSame([], $old->steps);
        self::assertSame($job->createdAt, $old->createdAt);
    }

    public function testKeepsTheExistingOptionalConstructorPositions(): void
    {
        $job = Job::fromArray($this->api->fixture('jobs.succeeded'));
        $notices = [['code' => 'option_replaced', 'option' => '-preset', 'value' => 'fast', 'tier' => 'standard']];
        $ai = ['seconds' => 12];
        $arguments = [$job->id, $job->state, $job->kind, $job->operation, $job->spec, $job->command,
            $job->inputFiles, $job->outputFiles, $job->ffmpegCommand, $job->webhookUrl, $job->metadata,
            $job->progress, $job->error, $job->outputs, $job->usage, $job->createdAt, $job->startedAt, $job->finishedAt,
            $job->steps, $notices, $ai];
        $old = new Job(...$arguments);
        self::assertSame($job->steps, $old->steps);
        self::assertSame($notices, $old->notices);
        self::assertSame($ai, $old->ai);
        self::assertNull($old->waitingReason);

        $waiting = new Job(...[...$arguments, 'credits']);
        self::assertSame('credits', $waiting->waitingReason);
        self::assertSame($ai, $waiting->ai);
    }

    public function testNamedConstructionMayOmitTheCreditWaitingReason(): void
    {
        $job = Job::fromArray($this->api->fixture('jobs.succeeded'));
        $old = new Job(
            id: $job->id, state: $job->state, kind: $job->kind, operation: $job->operation,
            spec: $job->spec, command: $job->command, inputFiles: $job->inputFiles, outputFiles: $job->outputFiles,
            ffmpegCommand: $job->ffmpegCommand, webhookUrl: $job->webhookUrl, metadata: $job->metadata,
            progress: $job->progress, error: $job->error, outputs: $job->outputs, usage: $job->usage,
            createdAt: $job->createdAt, startedAt: $job->startedAt, finishedAt: $job->finishedAt,
            steps: $job->steps, notices: $job->notices, ai: $job->ai,
        );
        self::assertNull($old->waitingReason);
        self::assertSame($job->createdAt, $old->createdAt);
        self::assertSame($job->steps, $old->steps);

        $waiting = new Job(
            id: $job->id, state: $job->state, kind: $job->kind, operation: $job->operation,
            spec: $job->spec, command: $job->command, inputFiles: $job->inputFiles, outputFiles: $job->outputFiles,
            ffmpegCommand: $job->ffmpegCommand, webhookUrl: $job->webhookUrl, metadata: $job->metadata,
            progress: $job->progress, error: $job->error, outputs: $job->outputs, usage: $job->usage,
            createdAt: $job->createdAt, startedAt: $job->startedAt, finishedAt: $job->finishedAt,
            steps: $job->steps, notices: $job->notices, ai: $job->ai,
            waitingReason: 'credits',
        );
        self::assertSame('credits', $waiting->waitingReason);
        self::assertSame('credits', $waiting->jsonSerialize()['waiting_reason']);
        self::assertSame('credits', Job::fromArray(self::decode((string) json_encode($waiting)))->waitingReason);
    }

    public function testReadsAnErasedJob(): void
    {
        $fixture = $this->api->fixture('jobs.succeeded');
        self::assertNull(Job::fromArray($fixture)->contentErasedAt);
        self::assertArrayNotHasKey('content_erased_at', Job::fromArray($fixture)->jsonSerialize());
        $fixture['content_erased_at'] = '2027-11-24T10:00:00.000Z';
        $erased = Job::fromArray($fixture);
        self::assertSame('2027-11-24T10:00:00+00:00', $erased->contentErasedAt?->format(DATE_ATOM));
        self::assertArrayHasKey('content_erased_at', $erased->jsonSerialize());
    }

    public function testReadsFractionalCreditUsage(): void
    {
        $fixture = $this->api->fixture('jobs.succeeded');
        self::assertIsArray($fixture['usage']);
        $fixture['usage']['credits'] = 44.6;
        self::assertSame(44.6, Job::fromArray($fixture)->usage?->credits);
    }

}
