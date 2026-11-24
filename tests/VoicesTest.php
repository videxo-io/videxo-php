<?php

declare(strict_types=1);

namespace Videxo\Tests;

use Videxo\Exception\LimitExceededException;
use Videxo\Exception\NotFoundException;
use Videxo\Model\PrivateVoice;

final class VoicesTest extends TestCase
{
    private const VOICE_ID = 'voice_0123456789abcdefghijklmn';

    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D';

    private const CREATE = [
        'locale' => 'pl',
        'authorization' => ['authorized' => true, 'statement_version' => 'voice-use-v1'],
        'reference' => ['sha256' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'size_bytes' => 160044, 'content_type' => 'audio/wav'],
    ];

    /** @return array<string, string> */
    private static function voice(string $state = 'ready', string $id = self::VOICE_ID): array
    {
        return ['id' => $id, 'version_id' => 'vver_0123456789abcdefghijklmn', 'locale' => 'pl', 'state' => $state];
    }

    public function testCreatesAVoiceWithAnIdempotencyKeyThenSendsTheWavBytesAsTheyAre(): void
    {
        $this->api->queue = [
            FakeApi::json(201, self::voice('uploading')),
            FakeApi::json(201, self::voice('uploading')),
            $this->api->problem('service_unavailable', 503),
            FakeApi::json(200, self::voice()),
        ];
        $created = $this->videxo->voices->create(self::CREATE, 'voice-1');
        self::assertInstanceOf(PrivateVoice::class, $created);
        self::assertSame('uploading', $created->state);
        self::assertSame('vver_0123456789abcdefghijklmn', $created->versionId);
        $request = $this->api->request(0);
        self::assertSame(['POST', FakeApi::BASE_URL . '/v1/voices'], [$request['method'], $request['url']]);
        self::assertSame(['voice-1'], $request['headers']['idempotency-key']);
        self::assertSame(self::CREATE, self::decode($request['body']));

        $this->videxo->voices->create(self::CREATE);
        self::assertMatchesRegularExpression(self::UUID, $this->api->request(1)['headers']['idempotency-key'][0] ?? '');

        $ready = $this->videxo->voices->uploadReference(self::VOICE_ID, "RIFF\x00\x01");
        self::assertSame('ready', $ready->state);
        // A retried PUT sends the same bytes again.
        foreach ([2, 3] as $index) {
            $put = $this->api->request($index);
            self::assertSame(['PUT', FakeApi::BASE_URL . '/v1/voices/' . self::VOICE_ID . '/reference'], [$put['method'], $put['url']]);
            self::assertSame(['audio/wav'], $put['headers']['content-type']);
            self::assertSame("RIFF\x00\x01", $put['body']);
        }
        self::assertCount(4, $this->api->requests);
    }

    public function testReadsListsAcrossPagesAndDeletes(): void
    {
        $this->api->queue = [
            FakeApi::json(200, self::voice()),
            FakeApi::json(200, ['data' => [self::voice('ready', 'voice_a')], 'next_cursor' => 'c1']),
            FakeApi::json(200, ['data' => [self::voice('ready', 'voice_b')], 'next_cursor' => null]),
            FakeApi::json(202, self::voice('deleting')),
        ];
        self::assertSame(self::voice(), $this->videxo->voices->get(self::VOICE_ID)->jsonSerialize());
        $ids = array_map(static fn (PrivateVoice $voice) => $voice->id, iterator_to_array($this->videxo->voices()->list(limit: 1), false));
        self::assertSame(['voice_a', 'voice_b'], $ids);
        self::assertSame('deleting', $this->videxo->voices->delete(self::VOICE_ID)->state);
        $sent = array_map(static fn (array $request) => [$request['method'], $request['url']], $this->api->requests);
        self::assertSame([
            ['GET', FakeApi::BASE_URL . '/v1/voices/' . self::VOICE_ID],
            ['GET', FakeApi::BASE_URL . '/v1/voices?limit=1'],
            ['GET', FakeApi::BASE_URL . '/v1/voices?limit=1&cursor=c1'],
            ['DELETE', FakeApi::BASE_URL . '/v1/voices/' . self::VOICE_ID],
        ], $sent);
    }

    public function testAnswersRefusalsAsExceptionsWithTheirReason(): void
    {
        $limit = $this->api->fixture('problems.limit_exceeded');
        $limit['reason'] = 'voice_limit';
        $this->api->queue = [$this->api->problem('not_found'), FakeApi::json(409, $limit)];
        try {
            $this->videxo->voices->get('voice_unknown');
            self::fail('expected a NotFoundException');
        } catch (NotFoundException $error) {
            self::assertSame(404, $error->status);
        }
        try {
            $this->videxo->voices->create(self::CREATE);
            self::fail('expected a LimitExceededException');
        } catch (LimitExceededException $error) {
            self::assertSame('voice_limit', $error->reason);
        }
        self::assertCount(2, $this->api->requests);
    }
}
