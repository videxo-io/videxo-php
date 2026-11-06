<?php

declare(strict_types=1);

namespace Videxo\Tests;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The Videxo API in memory, as a PSR-18 client: uploads with one-time URLs, job creation with
 * idempotency keys, jobs that move from queued to running to succeeded as they are read, pages,
 * cancel, compile, presets, fonts, download redirects and signed file URLs. Its answers come from
 * fixtures/api.json, which is generated from the API's own examples. Tests can put answers or
 * exceptions in `queue`, sent before any route, and read every request in `requests`.
 */
final class FakeApi implements ClientInterface
{
    public const KEY = 'vdx_test_key';

    public const BASE_URL = 'https://api.videxo.test';

    public const FILES = 'https://files.videxo.test';

    public const UPLOADS = 'https://uploads.videxo.test';

    /** @var list<array{method: string, url: string, headers: array<string, list<string>>, body: string}> */
    public array $requests = [];

    /** @var list<ResponseInterface|\Throwable> */
    public array $queue = [];

    /** @var array<string, mixed> */
    public readonly array $fixtures;

    /** @var array<string, array<string, mixed>> Jobs by id, newest last. */
    private array $jobs = [];

    /** @var array<string, array{hash: string, job: string}> */
    private array $keys = [];

    /** @var array<string, array{size: int, type: string, used: bool}> */
    private array $uploads = [];

    private int $counter = 0;

    public function __construct()
    {
        $fixtures = json_decode((string) file_get_contents(__DIR__ . '/fixtures/api.json'), true, 512, JSON_THROW_ON_ERROR);
        \assert(is_array($fixtures));
        /** @var array<string, mixed> $fixtures */
        $this->fixtures = $fixtures;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $body = $request->getBody();
        $content = (string) $body;
        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            $headers[strtolower((string) $name)] = array_values($values);
        }
        $this->requests[] = ['method' => $request->getMethod(), 'url' => (string) $request->getUri(), 'headers' => $headers, 'body' => $content];
        $next = array_shift($this->queue);
        if ($next instanceof \Throwable) {
            throw $next;
        }
        return $next ?? $this->route($request, $content);
    }

    /**
     * The last request sent, or the one at `$index`.
     *
     * @return array{method: string, url: string, headers: array<string, list<string>>, body: string}
     */
    public function request(int $index = -1): array
    {
        $request = $this->requests[$index < 0 ? count($this->requests) + $index : $index] ?? null;
        \assert($request !== null);
        return $request;
    }

    /**
     * A fixture by its path: `jobs.succeeded`, `problems.not_found`.
     *
     * @return array<string, mixed>
     */
    public function fixture(string $path): array
    {
        $value = $this->fixtures;
        foreach (explode('.', $path) as $key) {
            \assert(is_array($value) && array_key_exists($key, $value));
            $value = $value[$key];
        }
        \assert(is_array($value));
        /** @var array<string, mixed> $value */
        return $value;
    }

    /**
     * An answer with a JSON body.
     *
     * @param array<array-key, mixed> $body
     * @param array<string, string> $headers
     */
    public static function json(int $status, array $body, array $headers = []): Response
    {
        $type = $status >= 400 ? 'application/problem+json' : 'application/json';
        return new Response($status, ['Content-Type' => $type] + $headers, json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES));
    }

    /**
     * A problem details answer of the fixtures, with the given status.
     *
     * @param array<string, string> $headers
     */
    public function problem(string $code, ?int $status = null, array $headers = []): Response
    {
        $problem = $this->fixture("problems.{$code}");
        if ($status !== null) {
            $problem['status'] = $status;
        }
        $answerStatus = $problem['status'];
        \assert(is_int($answerStatus));
        return self::json($answerStatus, $problem, ['Request-Id' => 'req_7h2m4x9q0c3v8k1n5r6t2b4z'] + $headers);
    }

    /** Moves a job to `$state` with the fixture of that state; `succeeded` gives it the fixture's outputs. */
    public function setState(string $id, string $state): void
    {
        \assert(isset($this->jobs[$id]));
        $this->jobs[$id] = $this->withId($this->fixture("jobs.{$state}"), $id, $this->jobs[$id]);
    }

    /** @param array<string, mixed> $job */
    public function putJob(array $job): void
    {
        $id = $job['id'];
        \assert(is_string($id));
        $this->jobs[$id] = $job;
    }

    private function route(RequestInterface $request, string $content): ResponseInterface
    {
        $uri = $request->getUri();
        $origin = $uri->getScheme() . '://' . $uri->getHost();
        $method = $request->getMethod();
        $path = $uri->getPath();

        if ($origin === self::FILES) {
            return $method === 'GET' && $request->getHeaderLine('Authorization') === ''
                ? new Response(200, ['Content-Type' => 'video/mp4'], "bytes of {$path}")
                : new Response(403, [], 'Forbidden');
        }
        if ($origin === self::UPLOADS) {
            return $this->receiveUpload($method, substr($path, 1), $content, $request->getHeaderLine('Content-Type'));
        }
        if ($origin !== self::BASE_URL) {
            return new Response(502, ['Content-Type' => 'text/html'], '<html>Bad gateway</html>');
        }
        if ($request->getHeaderLine('Authorization') !== 'Bearer ' . self::KEY) {
            return $this->problem('authentication_failed');
        }

        $body = $content === '' ? null : json_decode($content, true);
        if ($method === 'POST' && $path === '/v1/uploads' && is_array($body)) {
            return $this->createUpload($body);
        }
        if ($method === 'POST' && preg_match('~^/v1/(jobs|transcode|thumbnails|storyboard|gif|audio|concat)$~', $path, $match) === 1) {
            return $this->createJob($match[1], is_array($body) ? $body : [], $request->getHeaderLine('Idempotency-Key'), $content);
        }
        if ($method === 'POST' && $path === '/v1/jobs/compile') {
            return self::json(200, $this->fixture('compile_result'));
        }
        if ($method === 'GET' && $path === '/v1/jobs') {
            parse_str($uri->getQuery(), $query);
            return $this->page($query);
        }
        if ($method === 'GET' && $path === '/v1/presets') {
            return self::json(200, $this->fixture('presets'));
        }
        if ($method === 'GET' && $path === '/v1/fonts') {
            return self::json(200, $this->fixture('fonts'));
        }
        if (preg_match('~^/v1/jobs/([^/]+)(/cancel|/files|/outputs/([^/]+))?$~', $path, $match) === 1) {
            $id = rawurldecode($match[1]);
            $job = $this->jobs[$id] ?? null;
            if ($job === null) {
                return $this->problem('not_found');
            }
            $action = $match[2] ?? '';
            if ($method === 'GET' && $action === '') {
                return self::json(200, $this->read($id));
            }
            if ($method === 'POST' && $action === '/cancel') {
                if (in_array($job['state'], ['queued', 'running'], true)) {
                    $this->setState($id, 'queued');
                    $this->jobs[$id]['state'] = 'cancelled';
                    $this->jobs[$id]['finished_at'] = '2026-11-04T12:00:05.000Z';
                }
                return self::json(200, $this->jobs[$id]);
            }
            if ($method === 'DELETE' && $action === '/files') {
                $outputs = $job['outputs'];
                \assert(is_array($outputs));
                $this->jobs[$id]['outputs'] = array_map(
                    static fn ($output) => is_array($output) ? ['deleted_at' => '2026-11-04T12:30:00.000Z', 'download_url' => null, 'download_url_expires_at' => null] + $output : $output,
                    $outputs,
                );
                return self::json(200, $this->jobs[$id]);
            }
            if ($method === 'GET' && isset($match[3])) {
                $alias = rawurldecode($match[3]);
                $outputs = $job['outputs'];
                \assert(is_array($outputs));
                foreach ($outputs as $output) {
                    if (is_array($output) && $output['alias'] === $alias) {
                        return new Response(302, ['Location' => self::FILES . "/{$id}/{$alias}?signature=fresh"]);
                    }
                }
                return $this->problem('not_found');
            }
        }
        return $this->problem('not_found');
    }

    /** @param array<array-key, mixed> $body */
    private function createUpload(array $body): ResponseInterface
    {
        if (!is_string($body['filename'] ?? null) || !is_int($body['size_bytes'] ?? null)) {
            return $this->problem('invalid_request');
        }
        $id = sprintf('up_%024x', ++$this->counter);
        $type = is_string($body['content_type'] ?? null) ? $body['content_type'] : 'application/octet-stream';
        $this->uploads[$id] = ['size' => $body['size_bytes'], 'type' => $type, 'used' => false];
        return self::json(201, [
            'id' => $id,
            'input_url' => "videxo://uploads/{$id}",
            'upload_url' => self::UPLOADS . "/{$id}",
            'upload_url_expires_at' => '2026-11-04T13:00:00.000Z',
            'expires_at' => '2026-11-05T12:00:00.000Z',
            'max_size_bytes' => 104857600,
        ]);
    }

    private function receiveUpload(string $method, string $id, string $content, string $type): ResponseInterface
    {
        $upload = $this->uploads[$id] ?? null;
        if ($method !== 'PUT' || $upload === null) {
            return $this->problem('not_found');
        }
        if ($upload['used']) {
            return $this->problem('conflict');
        }
        if (strlen($content) !== $upload['size'] || $type !== $upload['type']) {
            return $this->problem('invalid_request');
        }
        $this->uploads[$id]['used'] = true;
        return self::json(200, [
            'id' => $id,
            'input_url' => "videxo://uploads/{$id}",
            'size_bytes' => strlen($content),
            'sha256' => hash('sha256', $content),
            'content_type' => $type,
            'expires_at' => '2026-11-05T12:00:00.000Z',
        ]);
    }

    /** @param array<array-key, mixed> $body */
    private function createJob(string $endpoint, array $body, string $key, string $content): ResponseInterface
    {
        if ($key === '') {
            return $this->problem('invalid_request');
        }
        $hash = hash('sha256', $endpoint . $content);
        $known = $this->keys[$key] ?? null;
        if ($known !== null) {
            return $known['hash'] === $hash ? self::json(202, $this->jobs[$known['job']]) : $this->problem('conflict');
        }
        $id = sprintf('job_%024x', ++$this->counter);
        $fixture = match (true) {
            $endpoint === 'jobs' && isset($body['command']) => $this->fixture('command_job'),
            $endpoint === 'jobs' => $this->fixture('jobs_job'),
            default => $this->fixture('jobs.queued'),
        };
        $job = ['id' => $id] + $fixture;
        if (isset($body['metadata']) && is_array($body['metadata'])) {
            $job['metadata'] = $body['metadata'];
        }
        $this->jobs[$id] = $job;
        $this->keys[$key] = ['hash' => $hash, 'job' => $id];
        return self::json(202, $job);
    }

    /**
     * The job as read now; a queued job runs on its first read and succeeds on the next.
     *
     * @return array<string, mixed>
     */
    private function read(string $id): array
    {
        $job = $this->jobs[$id];
        $next = match ($job['state']) {
            'queued' => 'running',
            'running' => 'succeeded',
            default => null,
        };
        if ($next !== null) {
            $this->setState($id, $next);
        }
        return $job;
    }

    /** @param array<array-key, mixed> $query */
    private function page(array $query): ResponseInterface
    {
        $limit = is_numeric($query['limit'] ?? null) ? (int) $query['limit'] : 20;
        $jobs = array_values(array_reverse($this->jobs));
        if (isset($query['state'])) {
            $jobs = array_values(array_filter($jobs, static fn (array $job) => $job['state'] === $query['state']));
        }
        $start = is_numeric($query['cursor'] ?? null) ? (int) $query['cursor'] : 0;
        $data = array_slice($jobs, $start, $limit);
        $more = $start + $limit < count($jobs);
        return self::json(200, ['data' => $data, 'next_cursor' => $more ? (string) ($start + $limit) : null]);
    }

    /**
     * @param array<string, mixed> $fixture
     * @param array<string, mixed> $current
     * @return array<string, mixed>
     */
    private function withId(array $fixture, string $id, array $current): array
    {
        $fixtureId = $this->fixture('jobs.queued')['id'];
        \assert(is_string($fixtureId));
        $json = str_replace($fixtureId, $id, json_encode($fixture, JSON_THROW_ON_ERROR));
        $job = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        \assert(is_array($job));
        $job['metadata'] = $current['metadata'];
        /** @var array<string, mixed> $job */
        return $job;
    }
}
