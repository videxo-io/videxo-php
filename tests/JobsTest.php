<?php

declare(strict_types=1);

namespace Videxo\Tests;

use Videxo\Exception\NotFoundException;
use Videxo\Exception\WaitTimeoutException;
use Videxo\Internal\BodyShape;
use Videxo\Model\Job;
use Videxo\Model\JobState;

final class JobsTest extends TestCase
{
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D';

    public function testCreatesWithAnOperationEndpointAndARandomIdempotencyKey(): void
    {
        $job = $this->videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        self::assertSame(JobState::Queued, $job->state);
        self::assertSame('transcode', $job->operation);
        $request = $this->api->request();
        self::assertSame('POST', $request['method']);
        self::assertSame(FakeApi::BASE_URL . '/v1/transcode', $request['url']);
        self::assertSame(['application/json'], $request['headers']['content-type']);
        self::assertSame(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p'], self::decode($request['body']));
        self::assertMatchesRegularExpression(self::UUID, $request['headers']['idempotency-key'][0] ?? '');

        $this->videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        self::assertNotSame($request['headers']['idempotency-key'], $this->api->request()['headers']['idempotency-key']);
    }

    public function testEveryOperationHasItsEndpoint(): void
    {
        $this->videxo->thumbnails(['input' => 'https://example.com/a.mp4', 'at' => 3]);
        $this->videxo->storyboard(['input' => 'https://example.com/a.mp4']);
        $this->videxo->gif(['input' => 'https://example.com/a.mp4']);
        $this->videxo->audio(['input' => 'https://example.com/a.mp4']);
        $this->videxo->concat(['inputs' => ['https://example.com/a.mp4', 'https://example.com/b.mp4']]);
        $paths = array_map(static fn (array $request) => parse_url($request['url'], PHP_URL_PATH), $this->api->requests);
        self::assertSame(['/v1/thumbnails', '/v1/storyboard', '/v1/gif', '/v1/audio', '/v1/concat'], $paths);
    }

    public function testYourIdempotencyKeyReturnsTheSameJob(): void
    {
        $body = ['input' => 'videxo://uploads/up_000000000000000000000001', 'preset' => 'web-720p'];
        $first = $this->videxo->transcode($body, 'order-1234');
        $again = $this->videxo->transcode($body, 'order-1234');
        self::assertSame($first->id, $again->id);
        self::assertSame(['order-1234'], $this->api->request()['headers']['idempotency-key']);
    }

    public function testCreatesCommandAndOperationsJobs(): void
    {
        $operations = $this->videxo->jobs->create([
            'inputs' => ['talk' => 'https://example.com/talk.mp4'],
            'outputs' => [['name' => 'web', 'input' => 'talk', 'preset' => 'web-720p']],
            'metadata' => ['order' => '42'],
        ]);
        self::assertSame('operations', $operations->kind);
        self::assertSame(['order' => '42'], $operations->metadata);
        $command = $this->videxo->jobs->create([
            'command' => '-i {{in_a}} -c:v libx264 {{out_a}}',
            'input_files' => ['in_a' => 'https://example.com/a.mp4'],
            'output_files' => ['out_a' => 'a.mp4'],
        ]);
        self::assertSame('command', $command->kind);
        self::assertNull($command->spec);
        self::assertSame(FakeApi::BASE_URL . '/v1/jobs', $this->api->request()['url']);
    }

    public function testObjectFieldsAreObjectsEvenEmptyOrNumeric(): void
    {
        $this->videxo->transcode([
            'input' => 'https://example.com/a.mp4',
            'metadata' => [],
            'resize' => [],
            'audio' => [],
            'text' => [],
            'video' => ['codec' => 'h264'],
        ]);
        $body = $this->api->request()['body'];
        self::assertStringContainsString('"metadata":{}', $body);
        self::assertStringContainsString('"resize":{}', $body);
        self::assertStringContainsString('"audio":{}', $body);
        self::assertStringContainsString('"text":[]', $body);
        self::assertStringContainsString('"video":{"codec":"h264"}', $body);

        $this->videxo->transcode(['input' => 'https://example.com/a.mp4', 'metadata' => ['0' => 'first', '1' => 'second']]);
        self::assertStringContainsString('"metadata":{"0":"first","1":"second"}', $this->api->request()['body']);

        // createAt(): PHPStan gives up on a union this large and refuses the empty array in a typed body.
        $this->videxo->jobs->createAt('/v1/jobs', [
            'inputs' => ['0' => 'https://example.com/a.mp4'],
            'outputs' => [['name' => 'web', 'input' => '0', 'preset' => 'web-720p', 'resize' => []]],
        ]);
        $body = $this->api->request()['body'];
        self::assertStringContainsString('"inputs":{"0":"https://example.com/a.mp4"}', $body);
        self::assertStringContainsString('"outputs":[{"name":"web"', $body);
        self::assertStringContainsString('"resize":{}', $body);

        $this->videxo->concat(['inputs' => ['https://example.com/a.mp4', 'https://example.com/b.mp4']]);
        self::assertStringContainsString('"inputs":["https://example.com/a.mp4","https://example.com/b.mp4"]', $this->api->request()['body']);

        $compile = BodyShape::apply('CompileRequest', ['operation' => 'transcode', 'input' => 'https://example.com/a.mp4', 'resize' => []]);
        self::assertStringContainsString('"resize":{}', (string) json_encode($compile));
    }

    public function testWaitsForTheJobToEnd(): void
    {
        $job = $this->videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        $done = $this->videxo->jobs->wait($job->id, interval: 1.5);
        self::assertSame(JobState::Succeeded, $done->state);
        self::assertTrue($done->state->isFinal());
        self::assertSame([1.5, 1.5], $this->clock->sleeps);
        self::assertSame('out_video', $done->outputs[0]->alias);
        self::assertNotNull($done->usage);
        self::assertNotNull($done->finishedAt);
    }

    public function testAnswersAFailedJobAndDoesNotThrow(): void
    {
        $failed = Job::fromArray($this->api->fixture('jobs.failed'));
        $this->api->putJob($this->api->fixture('jobs.failed'));
        $job = $this->videxo->jobs->wait($failed->id);
        self::assertSame(JobState::Failed, $job->state);
        self::assertSame('input_unreachable', $job->error?->code);
        self::assertSame('http_status', $job->error->reason);
        self::assertSame([], $this->clock->sleeps);
    }

    public function testGivesUpWaitingAtTheTimeout(): void
    {
        $job = $this->videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        $this->api->queue = array_fill(0, 10, FakeApi::json(200, $this->api->fixture('jobs.running')));
        try {
            $this->videxo->jobs->wait($job->id, timeout: 5, interval: 2);
            self::fail('no exception');
        } catch (WaitTimeoutException $error) {
            self::assertSame('wait_timeout', $error->getErrorCode());
            self::assertSame(JobState::Running, $error->job?->state);
            self::assertSame([2.0, 2.0, 1.0], $this->clock->sleeps);
        }
    }

    public function testWaitsOutARateLimitByItsRetryAfter(): void
    {
        $job = $this->videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        $limited = $this->api->problem('rate_limited', headers: ['Retry-After' => '7']);
        // Retries inside the read take the first two 429s; wait() takes the third and pauses by Retry-After.
        $this->api->queue = [$limited, $limited, $limited];
        $done = $this->videxo->jobs->wait($job->id, interval: 1);
        self::assertSame(JobState::Succeeded, $done->state);
        self::assertSame([7.0, 7.0, 7.0, 1.0, 1.0], $this->clock->sleeps);
    }

    public function testWaitThrowsOtherErrors(): void
    {
        $this->expectException(NotFoundException::class);
        $this->videxo->jobs->wait('job_unknown');
    }

    public function testReadsPagesAndEveryJob(): void
    {
        $ids = [];
        for ($i = 0; $i < 5; $i++) {
            $ids[] = $this->videxo->transcode(['input' => "https://example.com/{$i}.mp4", 'preset' => 'web-720p'])->id;
        }
        $page = $this->videxo->jobs->page(limit: 2, state: JobState::Queued);
        self::assertSame([$ids[4], $ids[3]], array_map(static fn (Job $job) => $job->id, $page->data));
        self::assertSame('2', $page->nextCursor);
        self::assertStringEndsWith('/v1/jobs?limit=2&state=queued', $this->api->request()['url']);

        // iterator_to_array() as a customer calls it: with the generator's keys, every page kept.
        $all = array_map(static fn (Job $job) => $job->id, iterator_to_array($this->videxo->jobs->list(limit: 2)));
        self::assertSame(array_reverse($ids), array_values($all));
        self::assertSame([0, 1, 2, 3, 4], array_keys($all));
        self::assertStringEndsWith('/v1/jobs?limit=2&cursor=4', $this->api->request()['url']);
    }

    public function testCancelsDeletesFilesAndCompiles(): void
    {
        $job = $this->videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        self::assertSame(JobState::Cancelled, $this->videxo->jobs->cancel($job->id)->state);
        self::assertSame(FakeApi::BASE_URL . "/v1/jobs/{$job->id}/cancel", $this->api->request()['url']);

        $succeeded = Job::fromArray($this->api->fixture('jobs.succeeded'));
        $this->api->putJob($this->api->fixture('jobs.succeeded'));
        $deleted = $this->videxo->jobs->deleteFiles($succeeded->id);
        self::assertSame('DELETE', $this->api->request()['method']);
        self::assertNotNull($deleted->outputs[0]->deletedAt);

        $compiled = $this->videxo->jobs->compile(['operation' => 'transcode', 'input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
        self::assertStringStartsWith('ffmpeg ', (string) $compiled->ffmpegCommand);
        self::assertSame($this->api->fixture('compile_result')['command'], $compiled->command);
    }

    public function testEncodesTheIdInThePath(): void
    {
        try {
            $this->videxo->jobs->get('../v1/me?x=1');
        } catch (NotFoundException) {
        }
        self::assertSame(FakeApi::BASE_URL . '/v1/jobs/..%2Fv1%2Fme%3Fx%3D1', $this->api->request()['url']);
    }
}
