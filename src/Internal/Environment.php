<?php

declare(strict_types=1);

namespace Videxo\Internal;

/**
 * Reads the environment wherever PHP puts it: `$_ENV` is empty under the usual
 * `variables_order = "GPCS"`, and a framework may fill only `$_ENV` or `$_SERVER`.
 *
 * @internal
 */
final class Environment
{
    private function __construct()
    {
    }

    /** The variable's value, or null when it is unset or blank. */
    public static function get(string $name): ?string
    {
        $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);
        return is_string($value) && trim($value) !== '' ? $value : null;
    }
}
