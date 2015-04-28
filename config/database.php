<?php

return [

    'fetch' => PDO::FETCH_OBJ,

    'default' => 'mysql',

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => storage_path().'/database.sqlite',
            'prefix'   => '',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'test'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', 'secret'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

    ],

];
