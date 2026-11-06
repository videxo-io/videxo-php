<?php

declare(strict_types=1);

namespace Videxo\Internal;

use Videxo\Exception\UnexpectedResponseException;

/**
 * Reads the fields of a decoded JSON answer with their types, for the generated models. A field that
 * is missing or has another type throws an UnexpectedResponseException; an absent optional field
 * reads as null.
 *
 * @internal
 */
final class Read
{
    /** How the API writes a time: UTC with milliseconds. */
    public const DATE_FORMAT = 'Y-m-d\TH:i:s.v\Z';

    private function __construct()
    {
    }

    /** @param array<array-key, mixed> $data */
    public static function string(array $data, string $key): string
    {
        $value = $data[$key] ?? null;
        return is_string($value) ? $value : throw self::wrong($key, 'a string');
    }

    /** @param array<array-key, mixed> $data */
    public static function nullableString(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;
        return $value === null || is_string($value) ? $value : throw self::wrong($key, 'a string or null');
    }

    /** @param array<array-key, mixed> $data */
    public static function int(array $data, string $key): int
    {
        $value = $data[$key] ?? null;
        return is_int($value) ? $value : throw self::wrong($key, 'an integer');
    }

    /** @param array<array-key, mixed> $data */
    public static function nullableInt(array $data, string $key): ?int
    {
        $value = $data[$key] ?? null;
        return $value === null || is_int($value) ? $value : throw self::wrong($key, 'an integer or null');
    }

    /** @param array<array-key, mixed> $data */
    public static function float(array $data, string $key): float
    {
        $value = $data[$key] ?? null;
        return is_int($value) || is_float($value) ? (float) $value : throw self::wrong($key, 'a number');
    }

    /** @param array<array-key, mixed> $data */
    public static function nullableFloat(array $data, string $key): ?float
    {
        $value = $data[$key] ?? null;
        if ($value === null) {
            return null;
        }
        return is_int($value) || is_float($value) ? (float) $value : throw self::wrong($key, 'a number or null');
    }

    /** @param array<array-key, mixed> $data */
    public static function bool(array $data, string $key): bool
    {
        $value = $data[$key] ?? null;
        return is_bool($value) ? $value : throw self::wrong($key, 'true or false');
    }

    /** @param array<array-key, mixed> $data */
    public static function nullableBool(array $data, string $key): ?bool
    {
        $value = $data[$key] ?? null;
        return $value === null || is_bool($value) ? $value : throw self::wrong($key, 'true, false or null');
    }

    /** @param array<array-key, mixed> $data */
    public static function dateTime(array $data, string $key): \DateTimeImmutable
    {
        return self::parseTime(self::string($data, $key), $key);
    }

    /** @param array<array-key, mixed> $data */
    public static function nullableDateTime(array $data, string $key): ?\DateTimeImmutable
    {
        $value = self::nullableString($data, $key);
        return $value === null ? null : self::parseTime($value, $key);
    }

    /**
     * A JSON object, decoded to an array with string keys.
     *
     * @param array<array-key, mixed> $data
     * @return array<string, mixed>
     */
    public static function object(array $data, string $key): array
    {
        return self::nullableObject($data, $key) ?? throw self::wrong($key, 'an object');
    }

    /**
     * @param array<array-key, mixed> $data
     * @return array<string, mixed>|null
     */
    public static function nullableObject(array $data, string $key): ?array
    {
        $value = $data[$key] ?? null;
        if ($value === null) {
            return null;
        }
        // Decoded JSON cannot tell {"0":"a"} from ["a"], so the keys decide nothing: both are arrays.
        if (!is_array($value)) {
            throw self::wrong($key, 'an object');
        }
        /** @var array<string, mixed> $value */
        return $value;
    }

    /**
     * An object whose shape the API's document gives (a PHPStan type of Types); it is not checked
     * field by field.
     *
     * @param array<array-key, mixed> $data
     * @return array<string, mixed>
     */
    public static function shape(array $data, string $key): array
    {
        return self::object($data, $key);
    }

    /**
     * @param array<array-key, mixed> $data
     * @return array<string, mixed>|null
     */
    public static function nullableShape(array $data, string $key): ?array
    {
        return self::nullableObject($data, $key);
    }

    /**
     * @param array<array-key, mixed> $data
     * @return array<array-key, string>
     */
    public static function stringMap(array $data, string $key): array
    {
        $map = self::object($data, $key);
        foreach ($map as $value) {
            if (!is_string($value)) {
                throw self::wrong($key, 'an object of strings');
            }
        }
        /** @var array<array-key, string> $map */
        return $map;
    }

    /**
     * @param array<array-key, mixed> $data
     * @return array<array-key, string>|null
     */
    public static function nullableStringMap(array $data, string $key): ?array
    {
        return ($data[$key] ?? null) === null ? null : self::stringMap($data, $key);
    }

    /**
     * @param array<array-key, mixed> $data
     * @return list<string>
     */
    public static function stringList(array $data, string $key): array
    {
        $list = $data[$key] ?? null;
        if (!is_array($list) || !array_is_list($list)) {
            throw self::wrong($key, 'a list of strings');
        }
        foreach ($list as $value) {
            if (!is_string($value)) {
                throw self::wrong($key, 'a list of strings');
            }
        }
        /** @var list<string> $list */
        return $list;
    }

    /**
     * A list of objects, each read by `$read`.
     *
     * @template T
     * @param array<array-key, mixed> $data
     * @param callable(array<string, mixed>): T $read
     * @return list<T>
     */
    public static function list(array $data, string $key, callable $read): array
    {
        $list = $data[$key] ?? null;
        if (!is_array($list) || !array_is_list($list)) {
            throw self::wrong($key, 'a list');
        }
        $items = [];
        foreach ($list as $index => $item) {
            if (!is_array($item)) {
                throw self::wrong("{$key}[{$index}]", 'an object');
            }
            /** @var array<string, mixed> $item */
            $items[] = self::inside("{$key}[{$index}]", static fn () => $read($item));
        }
        return $items;
    }

    /**
     * An object read by `$read`, such as a nested model.
     *
     * @template T
     * @param array<array-key, mixed> $data
     * @param callable(array<string, mixed>): T $read
     * @return T
     */
    public static function model(array $data, string $key, callable $read): mixed
    {
        $value = self::object($data, $key);
        return self::inside($key, static fn () => $read($value));
    }

    /**
     * @template T
     * @param array<array-key, mixed> $data
     * @param callable(array<string, mixed>): T $read
     * @return T|null
     */
    public static function nullableModel(array $data, string $key, callable $read): mixed
    {
        $value = self::nullableObject($data, $key);
        return $value === null ? null : self::inside($key, static fn () => $read($value));
    }

    /**
     * Runs `$read`, naming a field it refuses by its path from `$key`.
     *
     * @template T
     * @param callable(): T $read
     * @return T
     */
    private static function inside(string $key, callable $read): mixed
    {
        try {
            return $read();
        } catch (UnexpectedResponseException $error) {
            throw new UnexpectedResponseException(str_replace('a field ', "a field {$key}.", $error->getMessage()), 0, $error);
        }
    }

    /** A time as the API writes it. */
    public static function format(\DateTimeImmutable $time): string
    {
        return $time->setTimezone(new \DateTimeZone('UTC'))->format(self::DATE_FORMAT);
    }

    public static function nullableFormat(?\DateTimeImmutable $time): ?string
    {
        return $time === null ? null : self::format($time);
    }

    private static function parseTime(string $value, string $key): \DateTimeImmutable
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}:\d{2})$/', $value) !== 1) {
            throw self::wrong($key, 'a time');
        }
        try {
            return new \DateTimeImmutable($value);
        } catch (\Exception) {
            throw self::wrong($key, 'a time');
        }
    }

    private static function wrong(string $key, string $expected): UnexpectedResponseException
    {
        return new UnexpectedResponseException("The API answered with a field {$key} that is not {$expected}.");
    }
}
