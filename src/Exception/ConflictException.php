<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** The request conflicts with the current state, such as a reused idempotency key (409, `conflict`). */
class ConflictException extends ApiException
{
}
