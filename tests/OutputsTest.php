<?php

declare(strict_types=1);

namespace Videxo\Tests;

use GuzzleHttp\Psr7\Response;
use Videxo\Exception\NotFoundException;
use Videxo\Exception\OutputNotFoundException;
use Videxo\Exception\UnexpectedResponseException;
use Videxo\Model\Job;

final class OutputsTest extends TestCase
{
    private Job $job;

    protected function setUp(): void
    {
        parent::setUp();
        $this->job = Job::fromArray($this->api->fixture('jobs.succeeded'));
        $this->api->putJob($this->api->fixture('jobs.succeeded'));
    }

    public function testAnswersAFreshLinkWithoutFollowingIt(): void
    {
        $url = $this->videxo->outputs->url($this->job->id, 'out_video');
        self::assertSame(FakeApi::FILES . "/{$this->job->id}/out_video?signature=fresh", $url);
        self::assertCount(1, $this->api->requests);
        self::assertSame(FakeApi::BASE_URL . "/v1/jobs/{$this->job->id}/outputs/out_video", $this->api->request()['url']);
    }

    public function testFindsTheAliasOfANameOrAFileName(): void
    {
        self::assertStringContainsString('/out_video?', $this->videxo->outputs->url($this->job->id, 'video'));
        self::assertStringContainsString('/out_video?', $this->videxo->outputs->url($this->job->id, 'talk-720p.mp4'));
        self::assertSame('GET', $this->api->request(-2)['method']);
        self::assertSame(FakeApi::BASE_URL . "/v1/jobs/{$this->job->id}", $this->api->request(-2)['url']);
    }

    public function testRefusesAMissingOrSharedName(): void
    {
        try {
            $this->videxo->outputs->url($this->job->id, 'poster');
            self::fail('no exception');
        } catch (OutputNotFoundException $error) {
            self::assertSame('output_not_found', $error->getErrorCode());
        }
        $job = $this->api->fixture('jobs.succeeded');
        $output = $this->api->fixture('jobs.succeeded.outputs.0');
        $job['outputs'] = [$output, ['alias' => 'out_copy'] + $output];
        $this->api->putJob($job);
        try {
            $this->videxo->outputs->url($this->job->id, 'video');
            self::fail('no exception');
        } catch (OutputNotFoundException $error) {
            self::assertSame('output_ambiguous', $error->getErrorCode());
            self::assertStringContainsString('out_video, out_copy', $error->getMessage());
        }
    }

    public function testThrowsTheApiErrorOfAnUnknownAlias(): void
    {
        $this->expectException(NotFoundException::class);
        $this->videxo->outputs->url($this->job->id, 'out_other');
    }

    public function testResolvesARelativeLocation(): void
    {
        $this->api->queue = [new Response(302, ['Location' => '/v1/files/job_x/out_video?signature=s'])];
        self::assertSame(FakeApi::BASE_URL . '/v1/files/job_x/out_video?signature=s', $this->videxo->outputs->url('job_x', 'out_video'));
    }

    public function testRefusesAClientThatFollowedTheRedirect(): void
    {
        $this->api->queue = [new Response(200, ['Content-Type' => 'video/mp4'], 'bytes')];
        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('must not follow redirects');
        $this->videxo->outputs->url($this->job->id, 'out_video');
    }

    public function testDownloadsWithoutTheKey(): void
    {
        $response = $this->videxo->outputs->download($this->job->id, 'out_video');
        self::assertSame("bytes of /{$this->job->id}/out_video", (string) $response->getBody());
        $file = $this->api->request();
        self::assertStringStartsWith(FakeApi::FILES, $file['url']);
        self::assertArrayNotHasKey('authorization', $file['headers']);
    }

    public function testSavesToAFile(): void
    {
        $path = sys_get_temp_dir() . '/videxo-' . bin2hex(random_bytes(4)) . '.mp4';
        try {
            $bytes = $this->videxo->outputs->save($this->job->id, 'video', $path);
            self::assertSame("bytes of /{$this->job->id}/out_video", file_get_contents($path));
            self::assertSame(filesize($path), $bytes);
        } finally {
            @unlink($path);
        }
    }

    public function testThrowsWhenTheFileUrlFails(): void
    {
        $this->api->queue = [new Response(302, ['Location' => FakeApi::FILES . '/gone']), new Response(410, [], 'Gone')];
        try {
            $this->videxo->outputs->download($this->job->id, 'out_video');
            self::fail('no exception');
        } catch (\Videxo\Exception\ApiException $error) {
            self::assertSame(410, $error->status);
            self::assertSame('http_error', $error->errorCode);
        }
    }
}
