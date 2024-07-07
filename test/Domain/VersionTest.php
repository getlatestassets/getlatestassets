<?php

namespace Org_Heigl\GetLatestAssetsTest\Domain;

use Org_Heigl\GetLatestAssets\Domain\Version;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Version::class)]
class VersionTest extends TestCase
{
    #[DataProvider('versionsProvider')]
    public function testReturningCorrectVersionWorks(
        string $version,
        int $major,
        int $minor,
        int $patch,
        string $preRelease,
        string $build,
        bool $isSemVer
    ): void {
        $versionObj = new Version($version);

        self::assertSame($version, (string)$versionObj);
        self::assertSame($major, $versionObj->getMajor());
        self::assertSame($minor, $versionObj->getMinor());
        self::assertSame($patch, $versionObj->getPatch());
        self::assertSame($preRelease, $versionObj->getPreRelease());
        self::assertSame($build, $versionObj->getBuild());
        self::assertSame($isSemVer, $versionObj->isSemVer());
    }

    /**
     * @return array{
     *     string,
     *     int,
     *     int,
     *     int,
     *     string,
     *     string,
     *     boolean
     * }[]
     */
    public static function versionsProvider(): array
    {
        return [
            ['1.2.3', 1, 2, 3, '', '', true],
            ['1.2.3-pre-Release', 1, 2, 3, 'pre-Release', '', true],
            ['1.2.3+build', 1, 2, 3, '', 'build', true],
            ['1.2.3+build-release', 1, 2, 3, '', 'build-release', true],
            ['1.2.3-cool-release+my-build', 1, 2, 3, 'cool-release', 'my-build', true],
            ['bookworm', 0, 0, 0, '', '', false],
            ['1.2', 1, 2, 0, '', '', false],
            ['release-74-1', 74, 1, 0, '', '', false],
        ];
    }
}
