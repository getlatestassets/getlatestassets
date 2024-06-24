<?php


namespace Org_Heigl\GetLatestAssetsTest\Service;

use Org_Heigl\GetLatestAssets\AssetUrl;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;
use Org_Heigl\GetLatestAssets\Service\ConvertGithubReleaseListService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[CoversClass(ConvertGithubReleaseListService::class)]
class ConvertGithubReleaseListServiceTest extends TestCase
{
    public function testGetReleaseList(): void
    {
        $body = $this->getMockBuilder(StreamInterface::class)->getMock();
        $body->method('getContents')->willReturn(file_get_contents(__DIR__ . '/_assets/githubReleaseList.json'));

        $interface = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $interface->method('getBody')->willReturn($body);
        $service = new ConvertGithubReleaseListService();

        $releases = $service->getReleaseList($interface);

        self::assertInstanceOf(ReleaseList::class, $releases);

        self::assertEquals(
            new Release('0.2.1', new AssetUrl('manero.phar', 'https://github.com/tonymanero/manero/releases/download/0.2.1/manero.phar')),
            $releases->current()
        );
        $releases->next();
        self::assertEquals(
            new Release('0.1.1', new AssetUrl('manero.phar', 'https://github.com/tonymanero/manero/releases/download/0.1.1/manero.phar')),
            $releases->current()
        );
        $releases->next();
        self::assertFalse($releases->valid());
    }
}
