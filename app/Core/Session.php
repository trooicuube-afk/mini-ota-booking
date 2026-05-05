<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name('lht_estate_session');
            session_start();
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public static function put(string $key, mixed $value): void
    {
        $segments = explode('.', $key);
        $target = &$_SESSION;
        foreach ($segments as $segment) {
            if (!isset($target[$segment]) || !is_array($target[$segment])) {
                $target[$segment] = [];
            }
            $target = &$target[$segment];
        }
        $target = $value;
    }

    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    public static function forget(string $key): void
    {
        $segments = explode('.', $key);
        $target = &$_SESSION;
        foreach ($segments as $index => $segment) {
            if (!is_array($target) || !array_key_exists($segment, $target)) {
                return;
            }

            if ($index === count($segments) - 1) {
                unset($target[$segment]);
                return;
            }

            $target = &$target[$segment];
        }
    }
}
