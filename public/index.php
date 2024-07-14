<?php

use DI\Container;
use Org_Heigl\GetLatestAssets\Infrastructure\DiProvider;
use Org_Heigl\GetLatestAssets\Infrastructure\RouteProvider;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$diProvider = new DiProvider();
$app = $diProvider->getApp();

$routeProvider = new RouteProvider();
$routeProvider->provide($app);

$app->run();