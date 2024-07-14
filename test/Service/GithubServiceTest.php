<?php
/**
 * Created by PhpStorm.
 * User: heiglandreas
 * Date: 25.06.18
 * Time: 18:14
 */

namespace Org_Heigl\GetLatestAssetsTest\Service;

use GuzzleHttp\Client;
use Org_Heigl\GetLatestAssets\AssetUrl;
use Org_Heigl\GetLatestAssets\Exception\TroubleWithGithubApiAccess;
use Org_Heigl\GetLatestAssets\Infrastructure\Cache\CacheItem;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;
use Org_Heigl\GetLatestAssets\Service\ConvertGithubReleaseListService;
use Org_Heigl\GetLatestAssets\Service\GithubService;
use Org_Heigl\GetLatestAssets\Service\VersionService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Uri;

#[CoversClass(GithubService::class)]
class GithubServiceTest extends TestCase
{
    public function testInstantiation(): void
    {
        $client = $this->getMockBuilder(Client::class)->getMock();
        $versionService = $this->getMockBuilder(VersionService::class)->getMock();
        $convertService = $this->getMockBuilder(ConvertGithubReleaseListService::class)->getMock();
        $caching = $this->getMockBuilder(CacheItemPoolInterface::class)->getMock();

        $service = new GithubService($client, $versionService, $convertService, $caching);

        self::assertTrue($service instanceof GithubService);
    }

    public function testServiceThrowsUpOnClientException(): void
    {
        $client = $this->getMockBuilder(Client::class)->getMock();
        $client->method('get')
               ->with('repos/tonymanero/manero/releases')
               ->willThrowException( new \Exception());
        $versionService = $this->getMockBuilder(VersionService::class)->getMock();
        $convertService = $this->getMockBuilder(ConvertGithubReleaseListService::class)->getMock();
        $caching = $this->getMockBuilder(CacheItemPoolInterface::class)->getMock();

        $service = new GithubService($client, $versionService, $convertService, $caching);

        self::expectException(TroubleWithGithubApiAccess::class);
        $service('tonymanero', 'manero', 'foo');
    }

    public function testService(): void
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $client = $this->getMockBuilder(Client::class)->getMock();
        $client->method('get')
               ->with('/repos/tonymanero/manero/releases')
               ->willReturn($response);

        $releaseList = new ReleaseList();

        $convertService = $this->getMockBuilder(ConvertGithubReleaseListService::class)->getMock();
        $convertService->method('getReleaseList')
                       ->with($response)
                       ->willReturn($releaseList);

        $release = new Release('1.0.0', new AssetUrl('name', 'http://example.com/foo?bar=baz#foob'));
        $releaseList->addRelease($release);

        $versionService = $this->getMockBuilder(VersionService::class)->getMock();
        $versionService->method('getLatestAssetForConstraintFromResult')
                       ->with($releaseList, null)
                       ->willReturn($release);

        $caching = $this->getMockBuilder(CacheItemPoolInterface::class)->getMock();
        $item = new CacheItem('whatever', '', new \DateTimeImmutable('-1 hour'));

        $caching->method('getItem')->willReturn($item);

        $service = new GithubService($client, $versionService, $convertService, $caching);
        $result = $service('tonymanero', 'manero', 'name');

        self::assertInstanceOf(Uri::class, $result);
        self::assertEquals('http://example.com/foo?bar=baz#foob', (string) $result);
    }
}
