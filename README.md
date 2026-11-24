# Videxo for PHP

The PHP client of the [Videxo](https://videxo.io) API: hosted FFmpeg jobs over HTTP, described as
JSON operations or as an ffmpeg command. PHP 8.2 or newer. It sends requests through any PSR-18
HTTP client, with Guzzle by default, answers typed readonly objects, and types every request body
for PHPStan and Psalm from the API's OpenAPI document.

Using Laravel? [`videxo/laravel`](https://github.com/videxo-io/videxo-laravel) adds a service
provider, a facade and a webhook route on top of this package.

```bash
composer require videxo/videxo-php
```

## Keep the API key on a server

The API key gives full access to your account. Use it only in server code, never in a browser or
an app. A browser or an app sends files to an upload URL your server made ([below](#upload-a-file)).

## Create a job and wait for it

```php
use Videxo\Model\JobState;
use Videxo\Videxo;

$videxo = Videxo::fromEnvironment();

$job = $videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p']);
$done = $videxo->jobs->wait($job->id);
if ($done->state === JobState::Succeeded) {
    $videxo->outputs->save($done->id, 'video', '/tmp/talk-720p.mp4');
} else {
    echo $done->error?->code, ': ', $done->error?->message;
}
```

`Videxo::fromEnvironment()` reads the key from `VIDEXO_API_KEY` and the API's address from
`VIDEXO_API_URL` when it is set. `new Videxo($key)` takes the key itself.

| Call | API |
|---|---|
| `new Videxo($apiKey, baseUrl:, httpClient:, maxRetries:, timeout:)` | `baseUrl` is `https://api.videxo.io` by default |
| `transcode`, `thumbnails`, `storyboard`, `gif`, `audio`, `concat`, `adaptive` | `POST /v1/<operation>` |
| `detectSilence`, `removeSilence` | `POST /v1/detect_silence`, `POST /v1/remove_silence` |
| `tts($body)` | `POST /v1/tts`: text spoken as a WAV file, by a default or a private voice |
| `voices->create($body)`, `voices->uploadReference($id, $wav)` | `POST /v1/voices`, then `PUT /v1/voices/{id}/reference` with the WAV bytes |
| `voices->get($id)`, `voices->list(limit:)`, `voices->page(limit:, cursor:)`, `voices->delete($id)` | `GET /v1/voices/{id}`, `GET /v1/voices`, `DELETE /v1/voices/{id}` |
| `jobs->create($body, $idempotencyKey)` | `POST /v1/jobs`, a command body or an operations body |
| `jobs->get($id)`, `jobs->cancel($id)`, `jobs->deleteFiles($id)` | `GET /v1/jobs/{id}`, `POST .../cancel`, `DELETE .../files` |
| `jobs->list(limit:, state:)`, `jobs->page(limit:, state:, cursor:)` | `GET /v1/jobs`: every job as a generator, or one page |
| `jobs->wait($id, timeout:, interval:)` | reads the job until it is succeeded, failed or cancelled |
| `jobs->compile($body)` | `POST /v1/jobs/compile` |
| `presets->list()`, `fonts->list()` | `GET /v1/presets`, `GET /v1/fonts` |
| `uploads->create($filename, $sizeBytes, $contentType)` | `POST /v1/uploads`: a one-time upload URL |
| `uploads->upload($data, $filename)`, `uploads->uploadFile($path)` | the upload URL, then the bytes |
| `outputs->url($jobId, $output)` | `GET /v1/jobs/{id}/outputs/{alias}`: a fresh signed link |
| `outputs->download($jobId, $output)`, `outputs->save(..., $path)` | the file, as a PSR-7 response or into a file |
| `Webhook::fromEnvironment()` or `new Webhook($secret)`, then `->verify($payload, $headers)` | checks a webhook's signature and answers its event |

An output is named by its alias (`out_video`), or by its name or file name when only one file of
the job has it. The [docs](https://videxo.io/docs/sdk/php) cover each call.

## Typed bodies and answers

Request bodies are arrays, sent as JSON. `Videxo\Types` holds a PHPStan type for each one, generated
from the API's OpenAPI document, and every method names its type: PHPStan or Psalm tells you about a
missing `input` or a misspelt field before the request is sent. The API checks every body again.
Where the API takes an object, an array is sent as one, so `'metadata' => []` becomes `{}`.

Answers are readonly objects with camelCase properties: `Job`, `JobOutput`, `Upload`,
`CompletedUpload`, `Preset`, `Font` and the others in `Videxo\Model`, with `JobState` and
`WebhookEventType` as enums and times as `DateTimeImmutable`. A job in a state a newer API adds reads
as `JobState::Unknown`. `json_encode()` gives back the API's JSON shape.

## Upload a file

From the server:

```php
$upload = $videxo->uploads->uploadFile('/tmp/talk.mp4', contentType: 'video/mp4');
$job = $videxo->transcode(['input' => $upload->inputUrl, 'preset' => 'web-720p']);
```

From a browser or an app, create the upload on your server and hand over its `uploadUrl` only. The
client sends the file with `PUT` to that URL, without an API key, and your server creates the job
with `inputUrl` (`videxo://uploads/<id>`). An upload URL works once; it is never retried.

## Verify a webhook

```php
use Videxo\Exception\WebhookVerificationException;
use Videxo\Webhook;

try {
    $event = Webhook::fromEnvironment()->verify(file_get_contents('php://input'), getallheaders());
} catch (WebhookVerificationException) {
    http_response_code(400);
    exit;
}
// $event->id is the delivery's webhook-id: skip one you already handled.
if ($event->job !== null) {
    echo $event->type, ' ', $event->job->id;
}
http_response_code(204);
```

Pass the raw body exactly as it arrived. The check accepts either signature after a secret
rotation and refuses a timestamp more than five minutes away. A PSR-7 request works with
`verifyRequest($request)`.

## Errors

Every failure throws a `Videxo\Exception\VidexoException`; `getErrorCode()` is the API's documented
[error code](https://videxo.io/docs/errors) or one of the SDK's own. An error answer of the API is
an `ApiException` with `status`, `errorCode`, `reason`, `title`, `detail`, `type`, `retryable`,
`requestId`, `retryAfter`, `errors` (field errors), `problems` (command problems) and the raw
`body`. Each code has its own subclass, so you can catch what you handle:

```php
use Videxo\Exception\InvalidRequestException;
use Videxo\Exception\OutOfCreditsException;
use Videxo\Exception\VidexoException;

try {
    $job = $videxo->transcode($body);
} catch (InvalidRequestException $error) {
    foreach ($error->errors as $field) {
        echo $field->pointer, ': ', $field->detail, "\n";
    }
} catch (OutOfCreditsException) {
    // Ask the customer to buy credits.
} catch (VidexoException $error) {
    error_log($error->getErrorCode() . ' ' . ($error->requestId ?? ''));
}
```

The SDK's own codes: `network_error` and `timeout` (`ConnectionException`), `wait_timeout`,
`invalid_response`, `output_not_found`, `output_ambiguous`, and `http_error` for an error answer that
is not problem details. No message holds the API key.

## Retries and idempotency

Every job creation sends an `Idempotency-Key`: a random UUID unless you pass your own, reused on
the SDK's retries. Pass a key of your own (an order id, an upload id) to make your own retries safe
too, such as a queued job that runs again: within 24 hours the same key returns the same job.

A request is retried after `429`, `502`, `503`, `504`, a network error or a timeout, at most
`maxRetries` times (2 by default), with backoff and jitter, or after its `Retry-After` when that is
60 seconds or less. Other answers are not retried. `POST /v1/uploads` takes no idempotency key, so
it is retried only after a `429`; bytes sent to an upload URL are never sent twice.

The PHP upload helpers request single uploads only; `uploads->create()` has no multipart option.
For a multipart transfer, use your HTTP client to send `POST /v1/uploads` with `"multipart": true`,
`filename`, `size_bytes` and `content_type`. Send the parts using the response's `multipart` URLs,
then call its completion URL. `upload()` and `uploadFile()` refuse a multipart response before
sending any bytes.

## Your own HTTP client

Pass any PSR-18 client and PSR-17 factories:

```php
$videxo = new Videxo($key, httpClient: $client, requestFactory: $factory, streamFactory: $factory);
```

The client must not follow redirects (the download link is read from a redirect) and must not throw
for error statuses; Guzzle's PSR-18 `sendRequest()` behaves this way. The `timeout` option applies
to the default client only.

`jobs->wait()` includes retry delays and HTTP attempts in its timeout. The default Guzzle client
and the Laravel adapter shorten each attempt to the remaining budget. A late response is never
accepted. A custom PSR-18 client's active request cannot be interrupted through PSR-18 alone;
implement `Videxo\TimeoutClientInterface::sendRequestWithTimeout($request, $timeout)` to apply the
remaining budget to its connection and total request timeouts. A plain custom client must set its
own request timeout. Both values must be finite and nonnegative. A zero timeout sends no request.

## License

MIT, see [LICENSE](LICENSE). Issues are welcome; see [CONTRIBUTING](CONTRIBUTING.md).
