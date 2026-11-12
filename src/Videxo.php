<?php

declare(strict_types=1);

namespace Videxo;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Videxo\Internal\Environment;
use Videxo\Internal\Transport;
use Videxo\Model\Job;
use Videxo\Resource\Fonts;
use Videxo\Resource\Jobs;
use Videxo\Resource\Outputs;
use Videxo\Resource\Presets;
use Videxo\Resource\Uploads;

/**
 * The Videxo API client. One instance per API key; it holds no connection and is cheap to create.
 * Every call throws a VidexoException when it fails.
 *
 * ```php
 * $videxo = Videxo::fromEnvironment();
 * $job = $videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
 * $done = $videxo->jobs->wait($job->id);
 * ```
 *
 * @phpstan-import-type TranscodeRequest from Types
 * @phpstan-import-type ThumbnailsRequest from Types
 * @phpstan-import-type StoryboardRequest from Types
 * @phpstan-import-type GifRequest from Types
 * @phpstan-import-type AudioRequest from Types
 * @phpstan-import-type ConcatRequest from Types
 */
final class Videxo
{
    public const VERSION = '0.1.1';

    public const DEFAULT_BASE_URL = 'https://api.videxo.io';

    public const DEFAULT_MAX_RETRIES = 2;

    public const DEFAULT_TIMEOUT = 60.0;

    public readonly string $baseUrl;

    public readonly Jobs $jobs;

    public readonly Uploads $uploads;

    public readonly Outputs $outputs;

    public readonly Presets $presets;

    public readonly Fonts $fonts;

    private readonly Transport $transport;

    /**
     * @param string|Credentials $apiKey An API key from the dashboard (`vdx_...`). Keep it on a server.
     * @param string $baseUrl The API's address.
     * @param ClientInterface|null $httpClient A PSR-18 client; a Guzzle client by default. Turn off
     *     its redirects and its exceptions for error statuses: the SDK reads every answer itself.
     * @param RequestFactoryInterface|null $requestFactory PSR-17; Guzzle's by default.
     * @param StreamFactoryInterface|null $streamFactory PSR-17; Guzzle's by default.
     * @param int $maxRetries Retries of a failed request (429, 502, 503, 504, a network error or timeout).
     * @param float $timeout Seconds one attempt may take, for the default client only.
     * @param Clock|null $clock The time and the waits between retries and polls; the system's by default.
     */
    public function __construct(
        #[\SensitiveParameter] string|Credentials $apiKey,
        string $baseUrl = self::DEFAULT_BASE_URL,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        int $maxRetries = self::DEFAULT_MAX_RETRIES,
        float $timeout = self::DEFAULT_TIMEOUT,
        ?Clock $clock = null,
    ) {
        $credentials = $apiKey instanceof Credentials ? $apiKey : new ApiKey($apiKey);
        $baseUrl = rtrim($baseUrl, '/');
        if (preg_match('~^https?://[^/?#\s]+(?:/[^?#\s]*)?$~iD', $baseUrl) !== 1) {
            throw new \InvalidArgumentException('baseUrl must be an http or https URL.');
        }
        $factory = new HttpFactory();
        $this->baseUrl = $baseUrl;
        $this->transport = new Transport(
            http: $httpClient ?? new GuzzleClient(['timeout' => $timeout, 'connect_timeout' => min(10.0, $timeout)]),
            requests: $requestFactory ?? $factory,
            streams: $streamFactory ?? $factory,
            baseUrl: $baseUrl,
            credentials: $credentials,
            maxRetries: max(0, $maxRetries),
            clock: $clock ?? new SystemClock(),
            userAgent: 'videxo-php/' . self::VERSION . ' PHP/' . PHP_VERSION,
        );
        $this->jobs = new Jobs($this->transport);
        $this->uploads = new Uploads($this->transport);
        $this->outputs = new Outputs($this->transport, $this->jobs);
        $this->presets = new Presets($this->transport);
        $this->fonts = new Fonts($this->transport);
    }

    /**
     * A client from the environment: the key in `VIDEXO_API_KEY`, and the API's address in
     * `VIDEXO_API_URL` when it is set.
     */
    public static function fromEnvironment(): self
    {
        $key = Environment::get('VIDEXO_API_KEY');
        if ($key === null) {
            throw new \InvalidArgumentException('Set VIDEXO_API_KEY on the server: an API key from the dashboard.');
        }
        return new self($key, Environment::get('VIDEXO_API_URL') ?? self::DEFAULT_BASE_URL);
    }

    /** The jobs, as a method for facades: `Videxo::jobs()->wait($id)`. */
    public function jobs(): Jobs
    {
        return $this->jobs;
    }

    public function uploads(): Uploads
    {
        return $this->uploads;
    }

    public function outputs(): Outputs
    {
        return $this->outputs;
    }

    public function presets(): Presets
    {
        return $this->presets;
    }

    public function fonts(): Fonts
    {
        return $this->fonts;
    }

    /**
     * `POST /v1/transcode`: one video output from `input`, from a preset or your own options.
     *
     * @param TranscodeRequest $body
     * @param string|null $idempotencyKey Makes the creation safe to retry for 24 hours; see Jobs::create().
     */
    public function transcode(array $body, ?string $idempotencyKey = null): Job
    {
        return $this->jobs->createAt('/v1/transcode', $body, $idempotencyKey);
    }

    /**
     * `POST /v1/thumbnails`: one image at `at`, or one per time in `times`.
     *
     * @param ThumbnailsRequest $body
     */
    public function thumbnails(array $body, ?string $idempotencyKey = null): Job
    {
        return $this->jobs->createAt('/v1/thumbnails', $body, $idempotencyKey);
    }

    /**
     * `POST /v1/storyboard`: frames at a fixed interval in a grid on one image.
     *
     * @param StoryboardRequest $body
     */
    public function storyboard(array $body, ?string $idempotencyKey = null): Job
    {
        return $this->jobs->createAt('/v1/storyboard', $body, $idempotencyKey);
    }

    /**
     * `POST /v1/gif`: an animated GIF with its own palette.
     *
     * @param GifRequest $body
     */
    public function gif(array $body, ?string $idempotencyKey = null): Job
    {
        return $this->jobs->createAt('/v1/gif', $body, $idempotencyKey);
    }

    /**
     * `POST /v1/audio`: the sound of `input` as an audio file.
     *
     * @param AudioRequest $body
     */
    public function audio(array $body, ?string $idempotencyKey = null): Job
    {
        return $this->jobs->createAt('/v1/audio', $body, $idempotencyKey);
    }

    /**
     * `POST /v1/concat`: the `inputs` one after another, as one file.
     *
     * @param ConcatRequest $body
     */
    public function concat(array $body, ?string $idempotencyKey = null): Job
    {
        return $this->jobs->createAt('/v1/concat', $body, $idempotencyKey);
    }
}
