<?php

declare(strict_types=1);

namespace Videxo\Exception;

/**
 * `outputs->url()` or `outputs->download()` named an output the job does not have
 * (`output_not_found`), or a name several of its files share (`output_ambiguous`: use the alias of one).
 */
final class OutputNotFoundException extends \RuntimeException implements VidexoException
{
    /** @param 'output_not_found'|'output_ambiguous' $errorCode */
    public function __construct(public readonly string $errorCode, string $message)
    {
        parent::__construct($message);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
