<?php

declare(strict_types=1);

namespace Videxo\Tests;

use Videxo\Videxo;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected FakeApi $api;

    protected FakeClock $clock;

    protected Videxo $videxo;

    protected function setUp(): void
    {
        $this->api = new FakeApi();
        $this->clock = new FakeClock();
        $this->videxo = $this->client();
    }

    protected function client(int $maxRetries = Videxo::DEFAULT_MAX_RETRIES): Videxo
    {
        return new Videxo(FakeApi::KEY, FakeApi::BASE_URL, $this->api, maxRetries: $maxRetries, clock: $this->clock);
    }

    /** @return array<array-key, mixed> */
    protected static function decode(string $json): array
    {
        $value = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($value);
        return $value;
    }
}
