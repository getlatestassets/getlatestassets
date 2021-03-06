<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Alias;
use bitExpert\Disco\Annotations\Parameter;
use bitExpert\Disco\BeanFactoryRegistry;
use GuzzleHttp\Client;
use Org_Heigl\GetLatestAssets\Handler\GithubHandler;
use Org_Heigl\GetLatestAssets\Handler\PingHandler;
use Org_Heigl\GetLatestAssets\Service\ConvertGithubReleaseListService;
use Org_Heigl\GetLatestAssets\Service\GithubService;
use Org_Heigl\GetLatestAssets\Service\VersionService;

/**
 * @Configuration
 */
class ConfigProvider
{
    use ManeroConfigTrait;

    /**
     * @Bean({"parameters" = {
     *    @Parameter({"name" = "expressive"})
     * }})
     */
    public function config(array $expressiveConfig = []): array
    {
        $defaultConfig = [
            'debug'                => true,
            'config_cache_enabled' => false,
            'zend-expressive'      => [
                'error_handler' => [
                    'template_404'   => 'error::404',
                    'template_error' => 'error::error',
                ],
            ],
        ];

        return array_merge($defaultConfig, $expressiveConfig);
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Org_Heigl\GetLatestAssets\Service\GithubService"})
     * }})
     */
    public function getOrgHeiglGetLatestAssetsServiceGithubService() : GithubService
    {
        $client = new Client([
            'base_uri' => 'https://api.github.com',
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ]
        ]);

        return new GithubService($client, new VersionService(), new ConvertGithubReleaseListService());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Org_Heigl\GetLatestAssets\Handler\GithubHandler"})
     * }})
     */
    public function getOrgHeiglGetLatestAssetsHandlerGithubHandler() : GithubHandler
    {
        $service = BeanFactoryRegistry::getInstance()->get(GithubService::class);

        return new GithubHandler(
            $service
        );
    }
}
