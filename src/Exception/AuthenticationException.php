<?php

declare(strict_types=1);

namespace Videxo\Exception;

/** The API key is missing, wrong, revoked or expired (401, `authentication_failed`). */
class AuthenticationException extends ApiException
{
}
