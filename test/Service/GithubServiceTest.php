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
        $client = self::createStub(Client::class);
        $versionService = self::createStub(VersionService::class);
        $convertService = self::createStub(ConvertGithubReleaseListService::class);
        $caching = self::createStub(CacheItemPoolInterface::class);

        $service = new GithubService($client, $versionService, $convertService, $caching);

		// @phpstan-ignore staticMethod.alreadyNarrowedType,instanceof.alwaysTrue
        self::assertTrue($service instanceof GithubService);
    }

    public function testServiceThrowsUpOnClientException(): void
    {
        $client = $this->createMock(Client::class);
        $client->expects(self::once())
               ->method('get')
               ->with('/repos/tonymanero/manero/releases')
               ->willThrowException(new \Exception());
        $versionService = self::createStub(VersionService::class);
        $convertService = self::createStub(ConvertGithubReleaseListService::class);
        $caching = self::createStub(CacheItemPoolInterface::class);

        $service = new GithubService($client, $versionService, $convertService, $caching);

        self::expectException(TroubleWithGithubApiAccess::class);
        $service('tonymanero', 'manero', 'foo');
    }

    public function testService(): void
    {
        $response = self::createStub(ResponseInterface::class);
        $response->method('getHeader')->willReturn(['<url>; rel="alternate"; hreflang="en"']);

        $client = $this->createMock(Client::class);
        $client->expects(self::once())
               ->method('get')
               ->with('/repos/tonymanero/manero/releases')
               ->willReturn($response);

        $releaseList = new ReleaseList();

        $newReleaseList = new ReleaseList();
        $release = new Release('1.0.0', '1.0.0', new AssetUrl('name', 'http://example.com/foo?bar=baz#foob'));
        $newReleaseList->addRelease($release);

        $convertService = $this->createMock(ConvertGithubReleaseListService::class);
        $convertService->expects(self::once())
                       ->method('addToReleaseList')
                       ->with($releaseList, $response)
                       ->willReturn($newReleaseList);


        $versionService = $this->createMock(VersionService::class);
        $versionService->expects(self::once())
                       ->method('getLatestAssetForConstraintFromResult')
                       ->with($newReleaseList, null)
                       ->willReturn($release);

        $caching = self::createStub(CacheItemPoolInterface::class);
        $item = new CacheItem('whatever', '', new \DateTimeImmutable('-1 hour'));

        $caching->method('getItem')->willReturn($item);

        $service = new GithubService($client, $versionService, $convertService, $caching);
        $result = $service('tonymanero', 'manero', 'name');

        self::assertInstanceOf(Uri::class, $result);
        self::assertEquals('http://example.com/foo?bar=baz#foob', (string) $result);
    }
}
