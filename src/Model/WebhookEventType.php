<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo\Model;

enum WebhookEventType: string
{
    case JobSucceeded = 'job.succeeded';
    case JobFailed = 'job.failed';
    case JobCancelled = 'job.cancelled';
}
