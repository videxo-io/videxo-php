<?php

declare(strict_types=1);

namespace Videxo\Internal;

/**
 * Makes a PHP array encode as the JSON the API's schema asks for. A PHP array cannot say whether it
 * is a list or an object when it is empty or has numeric keys, so `'metadata' => []` would otherwise
 * be sent as `[]` and refused. Each array at a path that holds an object becomes one; lists stay lists.
 *
 * @internal
 */
final class BodyShape
{
    private function __construct()
    {
    }

    /**
     * `$body` ready for json_encode(), by its shape in BodyShapes.
     *
     * @param array<array-key, mixed> $body
     * @return array<array-key, mixed>|object
     */
    public static function apply(string $shape, array $body): array|object
    {
        $shaped = self::walk($body, BodyShapes::SHAPES[$shape] ?? []);
        \assert(is_array($shaped) || is_object($shaped));
        return $shaped;
    }

    /** @param array<array-key, mixed> $node */
    private static function walk(mixed $value, array $node): mixed
    {
        if (!is_array($value)) {
            return $value;
        }
        $items = is_array($node['i'] ?? null) && array_is_list($value) ? $node['i'] : null;
        $properties = is_array($node['p'] ?? null) ? $node['p'] : [];
        $additional = is_array($node['a'] ?? null) ? $node['a'] : [];
        $shaped = [];
        foreach ($value as $key => $child) {
            $inner = $items ?? ($properties[$key] ?? $additional);
            $shaped[$key] = self::walk($child, is_array($inner) ? $inner : []);
        }
        return ($node['o'] ?? false) === true ? (object) $shaped : $shaped;
    }
}
