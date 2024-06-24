<?php
/**
 * Created by PhpStorm.
 * User: heiglandreas
 * Date: 22.06.18
 * Time: 20:55
 */

namespace Org_Heigl\GetLatestAssetsTest\Service;

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

    #[DataProvider('getLatestAssetForConstraintFromResultProvider')]
    public function testGetLatestAssetForConstraintFromResult($list, $constraint, $result)
    {
        $array = [];
        $releaseList = new ReleaseList();
        foreach ($list as $key => $version) {
            $array[$key] = $this->getMockBuilder(Release::class)->disableOriginalConstructor()->getMock();
            $array[$key]->method('getVersion')->willReturn($version);
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

    public static function getLatestAssetForConstraintFromResultProvider()
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
        ];
    }
}
