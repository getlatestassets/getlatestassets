<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Infrastructure;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Org_Heigl\GetLatestAssets\Infrastructure\Cache\FileCacheItemPool;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Slim\App;

final class DiProvider
{
    private Container $container;

    public function __construct()
    {
        $builder = new ContainerBuilder();
        if (getenv('APP_ENV') !== 'dev') {
            $builder->enableCompilation(__DIR__ . '/../../data/cache/di');
            $builder->writeProxiesToFile(true, __DIR__ . '/../../data/cache/proxies');
        }

        $builder->addDefinitions($this->getDefinitions());

        $this->container = $builder->build();
    }

    /**
     * @return App<ContainerInterface>
     */
    public function getApp(): App
    {
        $app = Bridge::create($this->container);
        $this->container->set(App::class, $app);

        return $app;
    }

    /**
     * @return array<string, callable>
     */
    public function getDefinitions(): array
    {
        return [
            Client::class => function (ContainerInterface $container): Client {
                return new Client([
                    'base_uri' => 'https://api.github.com',
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'Authorization' => "Bearer " . ($_ENV['GITHUB_API_KEY']??''),
                    ]
                ]);
            },
            CacheItemPoolInterface::class => function (ContainerInterface $container): CacheItemPoolInterface {
                return new FileCacheItemPool($_ENV['CACHE_BASE_DIR']);
            }
        ];
    }
}
