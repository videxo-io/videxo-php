<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** A successful answer the SDK could not read (`invalid_response`). */
final class UnexpectedResponseException extends \RuntimeException implements VidexoException
{
    public function getErrorCode(): string
    {
        return 'invalid_response';
    }
}
