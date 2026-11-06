<?php

declare(strict_types=1);

namespace Videxo\Exception;

/**
 * Every exception the SDK throws. Catch this to handle any failure of a call; branch on
 * getErrorCode(), the API's documented error code (https://videxo.io/docs/errors) or one of the
 * SDK's own codes. No message holds the API key.
 */
interface VidexoException extends \Throwable
{
    /** The error code: the API's `code`, or the SDK's own (`network_error`, `timeout`, ...). */
    public function getErrorCode(): string;
}
