<?php

declare(strict_types=1);

namespace Videxo\Resource;

use Videxo\Internal\BodyShape;
use Videxo\Internal\Transport;
use Videxo\Model\PrivateVoice;
use Videxo\Model\PrivateVoiceList;
use Videxo\Types;

/**
 * The private voices of the account, each from a short recording of one person. No other account
 * can use, list or learn of them. A ready voice speaks in `Videxo::tts()` with its `voice_id`.
 *
 * @phpstan-import-type PrivateVoiceCreate from Types
 */
final class Voices
{
    /** @internal */
    public function __construct(private readonly Transport $transport)
    {
    }

    /**
     * `POST /v1/voices`: a private voice from a short WAV recording, declared by its SHA-256 and
     * size, with the voice-use statement confirmed. It answers in `uploading` until
     * uploadReference() sends the recording it declared.
     *
     * @param PrivateVoiceCreate $body
     * @param string|null $idempotencyKey Makes the creation safe to retry for 24 hours; a random one by default.
     */
    public function create(array $body, ?string $idempotencyKey = null): PrivateVoice
    {
        $key = $idempotencyKey ?? Jobs::randomKey();
        $request = $this->transport->apiRequest('POST', '/v1/voices', BodyShape::apply('PrivateVoiceCreate', $body), headers: ['Idempotency-Key' => $key]);
        return PrivateVoice::fromArray($this->transport->json($request, 'safe'));
    }

    /**
     * `PUT /v1/voices/{id}/reference`: the recording a private voice declared, as WAV bytes with the
     * SHA-256 and size of its creation. The voice is then `ready`. Sending the same bytes again is safe.
     *
     * @param string $wav The WAV file's bytes, such as `file_get_contents('anna.wav')`.
     */
    public function uploadReference(string $id, string $wav): PrivateVoice
    {
        $request = $this->transport->apiRequest('PUT', self::path($id) . '/reference')
            ->withHeader('Content-Type', 'audio/wav')
            ->withBody($this->transport->streams->createStream($wav));
        return PrivateVoice::fromArray($this->transport->json($request, 'safe'));
    }

    /** `GET /v1/voices/{id}`: the voice and its `state`. */
    public function get(string $id): PrivateVoice
    {
        return PrivateVoice::fromArray($this->transport->json($this->transport->apiRequest('GET', self::path($id)), 'safe'));
    }

    /**
     * `GET /v1/voices`: one page, newest first. Pass its `nextCursor` as `$cursor` for the next.
     *
     * @param int|null $limit Voices per page, 1 to 100; 20 by default.
     */
    public function page(?int $limit = null, ?string $cursor = null): PrivateVoiceList
    {
        $query = array_filter(['limit' => $limit, 'cursor' => $cursor], static fn ($value) => $value !== null);
        return PrivateVoiceList::fromArray($this->transport->json($this->transport->apiRequest('GET', '/v1/voices', query: $query), 'safe'));
    }

    /**
     * Every voice, newest first, reading page after page as you iterate:
     * `foreach ($videxo->voices->list() as $voice)`.
     *
     * @param int|null $limit Voices per page, 1 to 100.
     * @return \Generator<int, PrivateVoice, mixed, void>
     */
    public function list(?int $limit = null): \Generator
    {
        $cursor = null;
        do {
            $page = $this->page($limit, $cursor);
            foreach ($page->data as $voice) {
                yield $voice;
            }
            $cursor = $page->nextCursor;
        } while ($cursor !== null && $cursor !== '');
    }

    /**
     * `DELETE /v1/voices/{id}`: the voice can no longer be used, a job using it is stopped, and its
     * recording and everything made from it are erased. It answers `deleting` until that is done,
     * then `deleted`. Deleting again is safe.
     */
    public function delete(string $id): PrivateVoice
    {
        return PrivateVoice::fromArray($this->transport->json($this->transport->apiRequest('DELETE', self::path($id)), 'safe'));
    }

    private static function path(string $id): string
    {
        return '/v1/voices/' . rawurlencode($id);
    }
}
