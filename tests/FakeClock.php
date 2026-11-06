<?php

declare(strict_types=1);

namespace Videxo\Tests;

use Videxo\Clock;

/** A clock that does not wait: a sleep moves its time on and is recorded. */
final class FakeClock implements Clock
{
    /** @var list<float> */
    public array $sleeps = [];

    public function __construct(public float $time = 1_793_793_600.0)
    {
    }

    public function now(): float
    {
        return $this->time;
    }

    public function sleep(float $seconds): void
    {
        $this->sleeps[] = $seconds;
        $this->time += $seconds;
    }
}
