<?php

declare(strict_types=1);

use Org_Heigl\GetLatestAssets\ConfigProvider;

error_log(sys_get_temp_dir());
return [
    'di' => [
        'cache' => sys_get_temp_dir(),
        'class' => ConfigProvider::class,
    ],
];

