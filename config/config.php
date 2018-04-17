<?php

declare(strict_types=1);

use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

define(
    'GETLATESTASSETS_HOME',
    getenv('GETLATESTASSETS_HOME')??realpath(dirname(__DIR__ ) . '/data')
);

$config = [
    \Zend\Expressive\Router\FastRouteRouter\ConfigProvider::class,
    \Zend\HttpHandlerRunner\ConfigProvider::class,
    // Include cache configuration
    new ArrayProvider($cacheConfig),

    \Zend\Expressive\Helper\ConfigProvider::class,
    \Zend\Expressive\ConfigProvider::class,
    \Zend\Expressive\Router\ConfigProvider::class,

    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
    new PhpFileProvider(GETLATESTASSETS_HOME . '/config/{{,*.}local}.php'),
];

$aggregator = new ConfigAggregator(
    $config,
    $cacheConfig['config_cache_path']
);

defined('APPLICATION_ENVIRONMENT') || define('APPLICATION_ENVIRONMENT', ($config['environment']) ?? 'production');

return $aggregator->getMergedConfig();
