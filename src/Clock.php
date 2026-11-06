<?php

declare(strict_types=1);

namespace Videxo;

/**
 * The time and the waits of the client: between retries, between reads of `jobs->wait()`, and the
 * age of a webhook. SystemClock by default; pass your own to the client to test without waiting.
 */
interface Clock
{
    /** Seconds since the Unix epoch. */
    public function now(): float;

    public function sleep(float $seconds): void;
}
