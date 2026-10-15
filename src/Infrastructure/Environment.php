<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Infrastructure;

/**
 * Reads settings loaded by phpdotenv.
 *
 * Dotenv::createImmutable() only fills $_ENV and $_SERVER and never calls
 * putenv(), so getenv() does not see values from the .env file.
 */
final class Environment
{
    public static function get(string $name, string $default = ''): string
    {
        $value = $_ENV[$name] ?? $default;

        return is_string($value) ? $value : $default;
    }
}
