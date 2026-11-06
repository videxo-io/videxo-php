<?php

declare(strict_types=1);

namespace Videxo\Exception;

/**
 * A webhook delivery that did not pass verification: answer it with 400 and do not act on it.
 * The code says why: `missing_headers`, `invalid_secret`, `invalid_timestamp`, `invalid_signature`
 * or `invalid_payload`.
 */
final class WebhookVerificationException extends \RuntimeException implements VidexoException
{
    /** @param 'missing_headers'|'invalid_secret'|'invalid_timestamp'|'invalid_signature'|'invalid_payload' $errorCode */
    public function __construct(public readonly string $errorCode, string $message)
    {
        parent::__construct($message);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
