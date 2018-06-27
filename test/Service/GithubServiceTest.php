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
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;
use Org_Heigl\GetLatestAssets\Service\ConvertGithubReleaseListService;
use Org_Heigl\GetLatestAssets\Service\GithubService;
use Org_Heigl\GetLatestAssets\Service\VersionService;
use PHPUnit\Framework\TestCase;
use Mockery as M;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Uri;

class GithubServiceTest extends TestCase
{
    /**
     * @covers \Org_Heigl\GetLatestAssets\Service\GithubService::__construct
     */
    public function testInstantiation()
    {
        $client = M::mock(Client::class);
        $versionService = M::mock(VersionService::class);
        $convertService = M::mock(ConvertGithubReleaseListService::class);

        $service = new GithubService($client, $versionService, $convertService);

        self::assertAttributeSame($client, 'client', $service);
        self::assertAttributeSame($versionService, 'versionService', $service);
        self::assertAttributeSame($convertService, 'converterService', $service);
    }

    /**
     * @expectedException \Org_Heigl\GetLatestAssets\Exception\TroubleWithGithubApiAccess
     * @covers \Org_Heigl\GetLatestAssets\Service\GithubService::__invoke
     */
    public function testServiceThrowsUpOnClientException()
    {
        $client = M::mock(Client::class);
        $client->shouldReceive('get')
               ->with('repos/tonymanero/manero/releases')
               ->andThrow( new \Exception());
        $versionService = M::mock(VersionService::class);
        $convertService = M::mock(ConvertGithubReleaseListService::class);

        $service = new GithubService($client, $versionService, $convertService);
        $service('tonymanero', 'manero', 'foo');
    }

    /**
     * @covers \Org_Heigl\GetLatestAssets\Service\GithubService::__invoke
     */
    public function testService()
    {
        $response = M::mock(ResponseInterface::class);

        $client = M::mock(Client::class);
        $client->shouldReceive('get')
               ->with('/repos/tonymanero/manero/releases')
               ->andReturn($response);

        $releaseList = M::mock(ReleaseList::class);

        $convertService = M::mock(ConvertGithubReleaseListService::class);
        $convertService->shouldReceive('getReleaseList')
                       ->with($response)
                       ->andReturn($releaseList);

        $release = new Release('1.0.0', new AssetUrl('name', 'http://example.com/foo?bar=baz#foob'));

        $versionService = M::mock(VersionService::class);
        $versionService->shouldReceive('getLatestAssetForConstraintFromResult')
                       ->with($releaseList, null)
                       ->andReturn($release);

        $service = new GithubService($client, $versionService, $convertService);
        $result = $service('tonymanero', 'manero', 'name');

        self::assertInstanceOf(Uri::class, $result);
        self::assertEquals('http://example.com/foo?bar=baz#foob', (string) $result);
    }
}
