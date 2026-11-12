<?php

declare(strict_types=1);

namespace Videxo\Resource;

use Videxo\Exception\ApiException;
use Videxo\Exception\WaitTimeoutException;
use Videxo\Internal\BodyShape;
use Videxo\Internal\DeadlineExceeded;
use Videxo\Internal\Transport;
use Videxo\Model\CompileResult;
use Videxo\Model\Job;
use Videxo\Model\JobList;
use Videxo\Model\JobState;
use Videxo\Types;

/**
 * @phpstan-import-type JobRequest from Types
 * @phpstan-import-type CompileRequest from Types
 */
final class Jobs
{
    /** How long wait() waits for a job to end by default, in seconds. */
    public const DEFAULT_WAIT_TIMEOUT = 600.0;

    /** Between two reads of wait() by default, in seconds. */
    public const DEFAULT_WAIT_INTERVAL = 2.0;

    /** The body's shape at each path that creates a job. */
    private const SHAPES = [
        '/v1/jobs' => 'JobRequest',
        '/v1/transcode' => 'TranscodeRequest',
        '/v1/thumbnails' => 'ThumbnailsRequest',
        '/v1/storyboard' => 'StoryboardRequest',
        '/v1/gif' => 'GifRequest',
        '/v1/audio' => 'AudioRequest',
        '/v1/concat' => 'ConcatRequest',
    ];

    /** @internal */
    public function __construct(private readonly Transport $transport)
    {
    }

    /**
     * `POST /v1/jobs`: a command body (`command`, `input_files`, `output_files`) or an operations
     * body (`inputs`, `outputs`).
     *
     * Every creation sends an `Idempotency-Key`, which makes it safe to retry for 24 hours: the SDK
     * makes a random one when you leave it out and reuses it on its own retries. Pass your own (an
     * order id, an upload id) to also cover your retries, such as a queued job that runs again.
     *
     * @param JobRequest $body
     */
    public function create(array $body, ?string $idempotencyKey = null): Job
    {
        return $this->createAt('/v1/jobs', $body, $idempotencyKey);
    }

    /**
     * A job creation at `$path`, with an Idempotency-Key that its retries reuse.
     *
     * @internal
     * @param array<array-key, mixed> $body
     */
    public function createAt(string $path, array $body, ?string $idempotencyKey = null): Job
    {
        $key = $idempotencyKey ?? self::randomKey();
        $shaped = BodyShape::apply(self::SHAPES[$path] ?? 'JobRequest', $body);
        $request = $this->transport->apiRequest('POST', $path, $shaped, headers: ['Idempotency-Key' => $key]);
        return Job::fromArray($this->transport->json($request, 'safe'));
    }

    /** `GET /v1/jobs/{id}`. */
    public function get(string $id): Job
    {
        return Job::fromArray($this->transport->json($this->transport->apiRequest('GET', self::path($id)), 'safe'));
    }

    /**
     * `GET /v1/jobs`: one page, newest first. Pass its `nextCursor` as `$cursor` for the next.
     *
     * @param int|null $limit Jobs per page, 1 to 100; 20 by default.
     */
    public function page(?int $limit = null, JobState|string|null $state = null, ?string $cursor = null): JobList
    {
        $query = array_filter(
            ['limit' => $limit, 'state' => $state instanceof JobState ? $state->value : $state, 'cursor' => $cursor],
            static fn ($value) => $value !== null,
        );
        return JobList::fromArray($this->transport->json($this->transport->apiRequest('GET', '/v1/jobs', query: $query), 'safe'));
    }

    /**
     * Every job, newest first, reading page after page as you iterate:
     * `foreach ($videxo->jobs->list(state: JobState::Failed) as $job)`.
     *
     * @param int|null $limit Jobs per page, 1 to 100.
     * @return \Generator<int, Job, mixed, void>
     */
    public function list(?int $limit = null, JobState|string|null $state = null): \Generator
    {
        $cursor = null;
        do {
            $page = $this->page($limit, $state, $cursor);
            // One by one, so the keys go on counting across pages: iterator_to_array() keeps every job.
            foreach ($page->data as $job) {
                yield $job;
            }
            $cursor = $page->nextCursor;
        } while ($cursor !== null && $cursor !== '');
    }

    /** `POST /v1/jobs/{id}/cancel`: a queued or running job becomes cancelled; an ended one is answered as it is. */
    public function cancel(string $id): Job
    {
        return Job::fromArray($this->transport->json($this->transport->apiRequest('POST', self::path($id) . '/cancel'), 'safe'));
    }

    /** `DELETE /v1/jobs/{id}/files`: deletes an ended job's stored outputs now. */
    public function deleteFiles(string $id): Job
    {
        return Job::fromArray($this->transport->json($this->transport->apiRequest('DELETE', self::path($id) . '/files'), 'safe'));
    }

    /**
     * `POST /v1/jobs/compile`: the spec, command and `ffmpeg_command` a job would get; no job is made.
     *
     * @param CompileRequest $body
     */
    public function compile(array $body): CompileResult
    {
        $shaped = BodyShape::apply('CompileRequest', $body);
        return CompileResult::fromArray($this->transport->json($this->transport->apiRequest('POST', '/v1/jobs/compile', $shaped), 'safe'));
    }

    /**
     * Reads the job until it is succeeded, failed or cancelled, and answers it then: a failed job
     * is answered, not thrown (see `$job->error`). Waits out a 429 by its `Retry-After`. Throws a
     * WaitTimeoutException when the job has not ended within `$timeout` seconds.
     */
    public function wait(string $id, float $timeout = self::DEFAULT_WAIT_TIMEOUT, float $interval = self::DEFAULT_WAIT_INTERVAL): Job
    {
        if (!is_finite($timeout) || $timeout < 0 || !is_finite($interval) || $interval < 0) {
            throw new \InvalidArgumentException('wait() needs finite nonnegative timeout and interval values.');
        }
        $clock = $this->transport->clock;
        $deadline = $clock->now() + $timeout;
        $job = null;
        for (;;) {
            if ($clock->now() >= $deadline) {
                throw new WaitTimeoutException("Job {$id} did not end within {$timeout} seconds.", $job);
            }
            $pause = $interval;
            try {
                $job = Job::fromArray($this->transport->json(
                    $this->transport->apiRequest('GET', self::path($id)), 'safe', $deadline,
                ));
                if ($clock->now() >= $deadline) {
                    throw new DeadlineExceeded();
                }
                if ($job->state->isFinal()) {
                    return $job;
                }
            } catch (DeadlineExceeded) {
                throw new WaitTimeoutException("Job {$id} did not end within {$timeout} seconds.", $job);
            } catch (ApiException $error) {
                if ($error->status !== 429) {
                    throw $error;
                }
                $pause = max($interval, (float) ($error->retryAfter ?? 0));
            }
            $left = $deadline - $clock->now();
            if ($left <= 0) {
                throw new WaitTimeoutException("Job {$id} did not end within {$timeout} seconds.", $job);
            }
            $clock->sleep(min($pause, $left));
        }
    }

    /** @internal */
    public static function path(string $id): string
    {
        return '/v1/jobs/' . rawurlencode($id);
    }

    /** A random UUID (version 4). */
    private static function randomKey(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
