<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** The API cannot take the request now; try again later (503, `service_unavailable`). */
class ServiceUnavailableException extends ApiException
{
}
