<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

/**
 * queued and running are active; the others are final.
 */
enum JobState: string
{
    case Queued = 'queued';
    case Running = 'running';
    case Succeeded = 'succeeded';
    case Failed = 'failed';
    case Cancelled = 'cancelled';

    /** A value this version of the SDK does not know yet. */
    case Unknown = 'unknown';

    /** Whether a job in this state has ended: succeeded, failed or cancelled. */
    public function isFinal(): bool
    {
        return in_array($this, [self::Succeeded, self::Failed, self::Cancelled], true);
    }
}
