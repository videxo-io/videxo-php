<?php

declare(strict_types=1);

namespace Videxo;

/**
 * What a request to the API is authorized with. ApiKey is the one kind today; the client takes any
 * implementation, so another kind of credential can be added without changing the client.
 */
interface Credentials
{
    /** The value of the `Authorization` header. */
    public function authorization(): string;

    /** `$text` without the secret, for an error message that might hold it. */
    public function redact(string $text): string;
}
