<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssetsTest\Infrastructure;

use Dotenv\Dotenv;
use Org_Heigl\GetLatestAssets\Infrastructure\Environment;
use PHPUnit\Framework\Attributes\BackupGlobals;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Environment::class)]
#[BackupGlobals(true)]
class EnvironmentTest extends TestCase
{
    public function testReturnsValueFromEnv(): void
    {
        $_ENV['GLA_TEST_VALUE'] = 'secret';

        self::assertSame('secret', Environment::get('GLA_TEST_VALUE'));
    }

    public function testReturnsDefaultForMissingValue(): void
    {
        unset($_ENV['GLA_TEST_MISSING']);

        self::assertSame('', Environment::get('GLA_TEST_MISSING'));
        self::assertSame('x', Environment::get('GLA_TEST_MISSING', 'x'));
    }

    public function testReturnsDefaultForNonStringValue(): void
    {
        $_ENV['GLA_TEST_ARRAY'] = ['not', 'a', 'string'];

        self::assertSame('x', Environment::get('GLA_TEST_ARRAY', 'x'));
    }

    public function testSeesValuesLoadedByImmutableDotenv(): void
    {
        $dir = sys_get_temp_dir() . '/gla-env-' . bin2hex(random_bytes(4));
        mkdir($dir);
        file_put_contents($dir . '/.env', "GLA_TEST_DOTENV=from-file\n");

        try {
            Dotenv::createImmutable($dir)->load();

            self::assertSame('from-file', Environment::get('GLA_TEST_DOTENV'));
            self::assertFalse(getenv('GLA_TEST_DOTENV'));
        } finally {
            unlink($dir . '/.env');
            rmdir($dir);
        }
    }
}
