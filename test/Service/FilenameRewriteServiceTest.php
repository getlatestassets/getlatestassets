<?php

namespace Org_Heigl\GetLatestAssetsTest\Service;

use Generator;
use Org_Heigl\GetLatestAssets\Domain\Version;
use Org_Heigl\GetLatestAssets\Service\FilenameRewriteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(FilenameRewriteService::class)]
class FilenameRewriteServiceTest extends TestCase
{
    #[DataProvider('provideRewritingWorksAsExpected')]
    public function testRewritingWorksAsExpected(
        string $version,
        string $filename,
        string $result
    ): void {
        $service = new FilenameRewriteService(new Version($version));

        self::assertEquals($result, $service($filename));
    }

    /**
     * @return array{
     *     string,
     *     string,
     *     string
     * }[]
     */
    public static function provideRewritingWorksAsExpected(): array
    {
        return [
            ['1.2.3', 'foo.%major%.%minor%.txt', 'foo.1.2.txt'],
        ];
    }
}
