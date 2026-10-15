<?php

namespace Org_Heigl\GetLatestAssetsTest\Service;

use Org_Heigl\GetLatestAssets\Domain\TagNormalizer;
use Org_Heigl\GetLatestAssets\Exception\NoAssetMatchingConstraintFound;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;
use Org_Heigl\GetLatestAssets\Service\VersionService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(VersionService::class)]
class VersionServiceTest extends TestCase
{

    /**
     * @param string[] $list
     */
    #[DataProvider('getLatestAssetForConstraintFromResultProvider')]
    public function testGetLatestAssetForConstraintFromResult(
        array $list,
        string|null $constraint,
        int|null $result
    ): void {
        $array = [];
        $releaseList = new ReleaseList();
        $normalizer = new TagNormalizer();
        foreach ($list as $key => $version) {
            $array[$key] = self::createStub(Release::class);
            $array[$key]->method('getVersion')->willReturn($version);
            $array[$key]->method('getNormalizedVersion')
                ->willReturn($normalizer->normalize($version));
            $releaseList->addRelease($array[$key]);
        }

        $service = new VersionService();

        if (null === $result) {
            self::expectException(NoAssetMatchingConstraintFound::class);
            $service->getLatestAssetForConstraintFromResult($releaseList, $constraint);

            return;
        }

        self::assertSame($array[$result], $service->getLatestAssetForConstraintFromResult($releaseList, $constraint));
    }

    /**
     * @return array{array{array<string>, string|null, int|null}}
     */
    public static function getLatestAssetForConstraintFromResultProvider(): array
    {
        return [
            [['1.2.3'], '1.2.3', 0],
            [['1.2.3', '1.2.4'], '1.2.3', 0],
            [['1.2.3', '1.2.4'], '^1.2', 1],
            [['1.2.3', '1.2.4', '1.3.1'], '^1.2', 2],
            [['1.2.3', '1.2.4', '1.3.1', '2.1.0'], '^1.2', 2],
            [['1.2.3', '1.2.4', '1.3.1', '2.1.0'], '1.1.1', null],
            [['1.2.3', '1.2.3', '1.3.1', '2.1.0'], '^1.1', 2],
            [['1.2.3', '1.2.3', '1.3.1', '2.1.0'], null, 3],
            [['release-78.3', 'release-79.1rc'], '78.3', 0],
            [['release-79.1rc', 'release-78.3'], null, 1],
            [['release-79.1rc', 'release-78.3'], '^79.1@RC', 0],
            [['release-77-1', 'release-78.3'], '~77.0', 0],
            [['nightly', '1.2.3'], null, 1],
            [['nightly', '1.2.3'], '^1.0', 1],
            [['2.0.0-beta1', '1.2.3'], null, 1],
            [['nightly'], null, null],
            [['2026c'], null, 0],
            [['2026a', '2026c', '2026b'], null, 1],
            [['2026c', '2025b', '2026a'], null, 0],
            [['2026c', '2025b', '2026a'], '^2026', 0],
            [['2026c', '2025b', '2026a'], '2025.*', 1],
            [['2026c', '2025b', '2026a'], '2026.1', 2],
        ];
    }
}
