<?php


namespace Org_Heigl\GetLatestAssetsTest\Service;

use Org_Heigl\GetLatestAssets\AssetUrl;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;
use Org_Heigl\GetLatestAssets\Service\ConvertGithubReleaseListService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Mockery as M;
use Psr\Http\Message\StreamInterface;

class ConvertGithubReleaseListServiceTest extends TestCase
{

    public function testGetReleaseList()
    {
        $body = M::mock(StreamInterface::class);
        $body->shouldReceive('getContents')->andReturn(file_get_contents(__DIR__ . '/_assets/githubReleaseList.json'));

        $interface = M::mock(ResponseInterface::class);
        $interface->shouldReceive('getBody')->andReturn($body);
        $service = new ConvertGithubReleaseListService();

        $releases = $service->getReleaseList($interface);

        self::assertInstanceOf(ReleaseList::class, $releases);
        self::assertAttributeEquals(
            [
                new Release('0.2.1', new AssetUrl('manero.phar', 'https://github.com/tonymanero/manero/releases/download/0.2.1/manero.phar')),
                new Release('0.1.1', new AssetUrl('manero.phar', 'https://github.com/tonymanero/manero/releases/download/0.1.1/manero.phar')),
            ],
            'releases',
            $releases
        );

    }
}
