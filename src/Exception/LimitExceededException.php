<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** A size, duration or plan limit was exceeded (413, `limit_exceeded`). */
class LimitExceededException extends ApiException
{
}
