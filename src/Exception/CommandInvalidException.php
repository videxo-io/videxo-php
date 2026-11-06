<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** The ffmpeg command is not accepted; `problems` says why (400, `command_invalid`). */
class CommandInvalidException extends InvalidRequestException
{
}
