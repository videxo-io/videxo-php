<?php

declare(strict_types=1);

namespace Videxo\Resource;

use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Message\ResponseInterface;
use Videxo\Exception\ApiException;
use Videxo\Exception\OutputNotFoundException;
use Videxo\Exception\UnexpectedResponseException;
use Videxo\Internal\Transport;

final class Outputs
{
    private const ALIAS = '/^out_[A-Za-z0-9_]{1,48}$/D';

    /** @internal */
    public function __construct(private readonly Transport $transport, private readonly Jobs $jobs)
    {
    }

    /**
     * A fresh signed download URL of one output (`GET /v1/jobs/{id}/outputs/{alias}`), valid for a
     * short while: redirect a browser to it, or fetch it. `$output` is the file's alias (`out_video`),
     * or its name or file name when only one file of the job has it.
     */
    public function url(string $jobId, string $output): string
    {
        $alias = $this->alias($jobId, $output);
        $request = $this->transport->apiRequest('GET', Jobs::path($jobId) . '/outputs/' . rawurlencode($alias));
        $response = $this->transport->send($request, 'safe');
        $status = $response->getStatusCode();
        $location = $response->getHeaderLine('Location');
        if ($status >= 300 && $status < 400 && $location !== '') {
            return (string) UriResolver::resolve(new Uri($this->transport->baseUrl . '/'), new Uri($location));
        }
        if ($status >= 400) {
            throw ApiException::fromResponse($response, (string) $response->getBody());
        }
        throw new UnexpectedResponseException(
            'The API did not answer with the download URL. A custom HTTP client must not follow redirects.',
        );
    }

    /**
     * The output's bytes, fetched from a fresh signed URL: read `getBody()` of the answer as a
     * stream. The API key is not sent to the file URL.
     */
    public function download(string $jobId, string $output): ResponseInterface
    {
        $request = $this->transport->requests->createRequest('GET', $this->url($jobId, $output));
        $response = $this->transport->send($request, 'safe');
        if ($response->getStatusCode() >= 300) {
            throw ApiException::fromResponse($response, (string) $response->getBody());
        }
        return $response;
    }

    /** Downloads an output into the file at `$path` and answers the number of bytes written. */
    public function save(string $jobId, string $output, string $path): int
    {
        $body = $this->download($jobId, $output)->getBody();
        $file = @fopen($path, 'wb');
        if ($file === false) {
            throw new \RuntimeException("Cannot write the file {$path}.");
        }
        $written = 0;
        try {
            while (!$body->eof()) {
                $chunk = $body->read(1024 * 1024);
                if ($chunk === '') {
                    continue;
                }
                if (fwrite($file, $chunk) !== strlen($chunk)) {
                    throw new \RuntimeException("Cannot write the file {$path}.");
                }
                $written += strlen($chunk);
            }
        } finally {
            fclose($file);
            $body->close();
        }
        return $written;
    }

    private function alias(string $jobId, string $output): string
    {
        if (preg_match(self::ALIAS, $output) === 1) {
            return $output;
        }
        $aliases = [];
        foreach ($this->jobs->get($jobId)->outputs as $file) {
            if (($file->name === $output || $file->filename === $output) && !in_array($file->alias, $aliases, true)) {
                $aliases[] = $file->alias;
            }
        }
        if (count($aliases) === 1) {
            return $aliases[0];
        }
        if ($aliases === []) {
            throw new OutputNotFoundException('output_not_found', "Job {$jobId} has no output named {$output}.");
        }
        throw new OutputNotFoundException('output_ambiguous', "Several files of job {$jobId} are named {$output}: " . implode(', ', $aliases) . '.');
    }
}
