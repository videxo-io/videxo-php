<?php

declare(strict_types=1);

namespace Videxo\Exception;

/**
 * The request got no answer: `network_error` (DNS, TLS, a reset connection) or `timeout`. A request
 * that is safe to repeat was retried before this was thrown.
 */
final class ConnectionException extends \RuntimeException implements VidexoException
{
    /** @param 'network_error'|'timeout' $errorCode */
    public function __construct(public readonly string $errorCode, string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
