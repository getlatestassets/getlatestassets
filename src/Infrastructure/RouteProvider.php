<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Infrastructure;

use Org_Heigl\GetLatestAssets\Handler\GithubHandler;
use Org_Heigl\GetLatestAssets\Handler\PingHandler;
use Slim\App;

final class RouteProvider
{
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