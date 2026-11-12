<?php

declare(strict_types=1);

namespace Videxo\Resource;

use Psr\Http\Message\StreamInterface;
use Videxo\Exception\ApiException;
use Videxo\Exception\UnexpectedResponseException;
use Videxo\Internal\Transport;
use Videxo\Model\CompletedUpload;
use Videxo\Model\Upload;

final class Uploads
{
    /** @internal */
    public function __construct(private readonly Transport $transport)
    {
    }

    /**
     * `POST /v1/uploads`: a one-time upload URL for a private input. Give `uploadUrl` to a browser
     * or an app to send the file itself, or call send(). The route takes no Idempotency-Key, so only
     * a 429 is retried.
     *
     * @param string $filename A plain file name: letters, digits, dots, underscores and hyphens.
     * @param int $sizeBytes The exact byte length of the file.
     * @param string|null $contentType The type a job sees; `application/octet-stream` by default.
     */
    public function create(string $filename, int $sizeBytes, ?string $contentType = null): Upload
    {
        $body = ['filename' => $filename, 'size_bytes' => $sizeBytes] + ($contentType === null ? [] : ['content_type' => $contentType]);
        return Upload::fromArray($this->transport->json($this->transport->apiRequest('POST', '/v1/uploads', $body), 'rate_limit'));
    }

    /**
     * Creates an upload and sends the bytes to its URL; answers the completed upload, whose
     * `inputUrl` (`videxo://uploads/<id>`) a job takes as an input.
     *
     * @param StreamInterface|resource|string $data The bytes: a PSR-7 stream, an open file or a string.
     * @param int|null $size The byte length; read from the data when it can be known.
     */
    public function upload(mixed $data, string $filename, ?string $contentType = null, ?int $size = null): CompletedUpload
    {
        $stream = $this->stream($data);
        $size ??= $stream->getSize();
        if ($size === null) {
            throw new \InvalidArgumentException('uploads->upload() needs $size for a stream of unknown length.');
        }
        $contentType ??= 'application/octet-stream';
        $upload = $this->create($filename, $size, $contentType);
        if ($upload->multipart !== null) {
            throw new \InvalidArgumentException('Multipart uploads need separate part requests; upload() and uploadFile() support single uploads only.');
        }
        return $this->send($upload->uploadUrl, $stream, $contentType);
    }

    /**
     * Uploads a file from disk: uploadFile('/tmp/talk.mp4'). The file name is the path's base name
     * unless you give one.
     */
    public function uploadFile(string $path, ?string $filename = null, ?string $contentType = null): CompletedUpload
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \InvalidArgumentException("Cannot read the file {$path}.");
        }
        $stream = $this->transport->streams->createStreamFromFile($path, 'rb');
        try {
            return $this->upload($stream, $filename ?? basename($path), $contentType);
        } finally {
            $stream->close();
        }
    }

    /**
     * Sends the bytes to an upload's one-time `uploadUrl` and answers the completed upload. The
     * request carries no API key. The URL is used up once the bytes start to flow, so this request
     * is never retried: on a failure, create a new upload.
     *
     * @param StreamInterface|resource|string $data
     */
    public function send(string $uploadUrl, mixed $data, string $contentType = 'application/octet-stream'): CompletedUpload
    {
        $stream = $this->stream($data);
        $request = $this->transport->requests->createRequest('PUT', $uploadUrl)
            ->withHeader('Content-Type', $contentType)
            ->withBody($stream);
        $size = $stream->getSize();
        if ($size !== null) {
            $request = $request->withHeader('Content-Length', (string) $size);
        }
        $response = $this->transport->send($request, 'never');
        $text = (string) $response->getBody();
        if ($response->getStatusCode() >= 300) {
            throw ApiException::fromResponse($response, $text);
        }
        try {
            $data = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $data = null;
        }
        if (!is_array($data)) {
            throw new UnexpectedResponseException('The upload URL answered without the completed upload.');
        }
        return CompletedUpload::fromArray($data);
    }

    /** @param StreamInterface|resource|string $data */
    private function stream(mixed $data): StreamInterface
    {
        if ($data instanceof StreamInterface) {
            return $data;
        }
        if (is_string($data)) {
            return $this->transport->streams->createStream($data);
        }
        if (is_resource($data)) {
            return $this->transport->streams->createStreamFromResource($data);
        }
        throw new \InvalidArgumentException('Upload data must be a PSR-7 stream, an open file or a string.');
    }
}
