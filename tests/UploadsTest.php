<?php

declare(strict_types=1);

namespace Videxo\Tests;

use GuzzleHttp\Psr7\PumpStream;
use Videxo\Exception\ConflictException;
use Videxo\Exception\ServiceUnavailableException;

final class UploadsTest extends TestCase
{
    public function testCreatesAnUploadUrl(): void
    {
        $upload = $this->videxo->uploads->create('talk.mp4', 1234, 'video/mp4');
        self::assertSame(['filename' => 'talk.mp4', 'size_bytes' => 1234, 'content_type' => 'video/mp4'], self::decode($this->api->request()['body']));
        self::assertSame("videxo://uploads/{$upload->id}", $upload->inputUrl);
        self::assertStringStartsWith(FakeApi::UPLOADS, $upload->uploadUrl);
        self::assertSame(104857600, $upload->maxSizeBytes);
    }

    public function testUploadsBytesWithoutTheKey(): void
    {
        $done = $this->videxo->uploads->upload('abc', 'talk.mp4', 'video/mp4');
        self::assertSame(3, $done->sizeBytes);
        self::assertSame(hash('sha256', 'abc'), $done->sha256);
        self::assertSame("videxo://uploads/{$done->id}", $done->inputUrl);
        $put = $this->api->request();
        self::assertSame('PUT', $put['method']);
        self::assertSame('abc', $put['body']);
        self::assertSame(['video/mp4'], $put['headers']['content-type']);
        self::assertSame(['3'], $put['headers']['content-length']);
        self::assertArrayNotHasKey('authorization', $put['headers']);
    }

    public function testUploadsAFileAndAnOpenResource(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'videxo');
        self::assertIsString($path);
        file_put_contents($path, str_repeat('x', 1000));
        try {
            $done = $this->videxo->uploads->uploadFile($path, 'clip.mp4', 'video/mp4');
            self::assertSame(1000, $done->sizeBytes);
            self::assertSame('clip.mp4', self::decode($this->api->request(-2)['body'])['filename']);

            $file = fopen($path, 'rb');
            self::assertIsResource($file);
            $done = $this->videxo->uploads->upload($file, 'clip.mp4');
            self::assertSame('application/octet-stream', $done->contentType);
        } finally {
            unlink($path);
        }
    }

    public function testNeedsTheSizeOfAStreamOfUnknownLength(): void
    {
        $stream = new PumpStream(static fn () => false);
        $this->expectException(\InvalidArgumentException::class);
        $this->videxo->uploads->upload($stream, 'talk.mp4');
    }

    public function testNeverRetriesTheBytes(): void
    {
        $upload = $this->videxo->uploads->create('talk.mp4', 3);
        $this->api->queue = [$this->api->problem('service_unavailable')];
        try {
            $this->videxo->uploads->send($upload->uploadUrl, 'abc');
            self::fail('no exception');
        } catch (ServiceUnavailableException) {
        }
        self::assertCount(2, $this->api->requests);
        self::assertSame([], $this->clock->sleeps);
    }

    public function testAnUploadUrlWorksOnce(): void
    {
        $upload = $this->videxo->uploads->create('talk.mp4', 3);
        $this->videxo->uploads->send($upload->uploadUrl, 'abc');
        $this->expectException(ConflictException::class);
        $this->videxo->uploads->send($upload->uploadUrl, 'abc');
    }
    public function testSingleUploadHelperRefusesMultipartBeforeSendingBytes(): void
    {
        $upload = $this->videxo->uploads->create('large.mp4', 104857601);
        $data = $upload->jsonSerialize();
        $data['multipart'] = [
            'part_size_bytes' => 10485760,
            'part_count' => 11,
            'part_url_template' => $upload->uploadUrl . '/parts/{part_number}',
            'complete_url' => $upload->uploadUrl . '/complete',
        ];
        $this->api->queue = [FakeApi::json(201, $data)];
        try {
            $this->videxo->uploads->upload('bytes', 'large.mp4');
            self::fail('single upload helper accepted a multipart upload');
        } catch (\InvalidArgumentException $error) {
            self::assertStringContainsString('Multipart', $error->getMessage());
            self::assertCount(2, $this->api->requests);
            self::assertSame('POST', $this->api->request()['method']);
        }
    }

}
