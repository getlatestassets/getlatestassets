<?php

declare(strict_types=1);

// PHPUnit comes from tools/ in CI, tools/phpunit/ locally, or a phive phar
foreach (
    [
        __DIR__ . '/tools/vendor/autoload.php',
        __DIR__ . '/tools/phpunit/vendor/autoload.php',
        __DIR__ . '/tools/phpunit',
    ] as $file
) {
    if (is_file($file)) {
        require_once $file;
        break;
    }
}
