<?php
/**
 * Created by PhpStorm.
 * User: heiglandreas
 * Date: 26.06.18
 * Time: 15:39
 */

namespace Org_Heigl\GetLatestAssetsTest;

use Org_Heigl\GetLatestAssets\ConfigProvider;
use Org_Heigl\GetLatestAssets\Handler\GithubHandler;
use Org_Heigl\GetLatestAssets\Service\GithubService;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function setup()
    {
        require_once __DIR__ . '/../config/container.php';
    }

    /**
     * @covers \Org_Heigl\GetLatestAssets\ConfigProvider::config
     */
    public function testConfig()
    {
        $config = new ConfigProvider();

        $conf = $config->config();

        self::assertTrue(is_array($conf));
        self::assertArrayHasKey('debug', $conf);
        self::assertArrayHasKey('config_cache_enabled', $conf);
        self::assertArrayHasKey('zend-expressive', $conf);
    }

    /**
     * @covers \Org_Heigl\GetLatestAssets\ConfigProvider::getOrgHeiglGetLatestAssetsServiceGithubService
     */
    public function testgettingGithubService()
    {
        $config = new ConfigProvider();

        self::assertInstanceOf(
            GithubService::class,
            $config->getOrgHeiglGetLatestAssetsServiceGithubService()
        );
    }

    /**
     * @covers \Org_Heigl\GetLatestAssets\ConfigProvider::getOrgHeiglGetLatestAssetsHandlerGithubHandler
     */
    public function testGEttingGithubHandler()
    {
        $config = new ConfigProvider();

        self::assertInstanceof(
            GithubHandler::class,
            $config->getOrgHeiglGetLatestAssetsHandlerGithubHandler()
        );
    }
}
