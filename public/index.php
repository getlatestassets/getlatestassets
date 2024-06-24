<?php

use DI\Container;
use Org_Heigl\GetLatestAssets\Infrastructure\DiProvider;
use Org_Heigl\GetLatestAssets\Infrastructure\RouteProvider;

require __DIR__ . '/../vendor/autoload.php';

$diProvider = new DiProvider();
$app = $diProvider->getApp();

$routeProvider = new RouteProvider();
$routeProvider->provide($app);

$app->run();