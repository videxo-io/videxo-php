<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** The request is not valid; `errors` names each field (400, `invalid_request`). */
class InvalidRequestException extends ApiException
{
}
