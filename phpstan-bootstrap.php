<?php

declare(strict_types=1);

// PHPUnit is a phive phar locally and a composer install in CI
if (is_file(__DIR__ . '/tools/vendor/autoload.php')) {
    require_once __DIR__ . '/tools/vendor/autoload.php';
} else {
    require_once __DIR__ . '/tools/phpunit';
}
