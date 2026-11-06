<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** Too many requests; `retryAfter` says when to try again (429, `rate_limited`). */
class RateLimitedException extends ApiException
{
}
