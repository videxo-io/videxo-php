<?php

declare(strict_types=1);

namespace Videxo\Exception;

use Videxo\Model\Job;

/** `jobs->wait()` gave up before the job ended (`wait_timeout`); `job` is its last read state. */
final class WaitTimeoutException extends \RuntimeException implements VidexoException
{
    public function __construct(string $message, public readonly ?Job $job = null)
    {
        parent::__construct($message);
    }

    public function getErrorCode(): string
    {
        return 'wait_timeout';
    }
}
