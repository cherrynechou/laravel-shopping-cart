<?php
return [
    //数据库配置
    'database' => [
        // Database connection for following tables.
        'connection'                    =>      '',

        //Database database config name
        'prefix'                        =>      env('DB_PREFIX', ''),    //项目表前缀
    ],

    'storage' => \CherryneChou\LaravelShoppingCart\Storage\DatabaseStorage::class,
    /** @lang guards alias name. */
    'aliases' => [
        'web' => 'default',
        'api' => 'default'
    ]
];
