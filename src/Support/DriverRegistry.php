<?php

namespace Karnoweb\Payment\Support;

class DriverRegistry
{
    protected static array $drivers = [];

    public static function register(string $name, string $class): void
    {
        self::$drivers[$name] = $class;
    }

    public static function has(string $name): bool
    {
        return isset(self::$drivers[$name]);
    }

    public static function get(string $name): ?string
    {
        return self::$drivers[$name] ?? null;
    }

    public static function all(): array
    {
        return self::$drivers;
    }
}