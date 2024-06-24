<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Infrastructure;

use Org_Heigl\GetLatestAssets\Handler\GithubHandler;
use Org_Heigl\GetLatestAssets\Handler\PingHandler;
use Psr\Container\ContainerInterface;
use Slim\App;

final class RouteProvider
{
    /**
     * @param App<ContainerInterface> $app
     * @return void
     */
    public function provide(App $app): void
    {
        $app->get(
            '/github/{user}/{project}/{name}',
            GithubHandler::class,
        );
        $app->get(
            '/api/ping',
            PingHandler::class,
        );
    }
}
