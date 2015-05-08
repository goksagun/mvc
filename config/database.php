<?php

return [

    'fetch' => PDO::FETCH_OBJ,

    'default' => 'mysql',

    'connections' => [

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'database'),
            'username'  => env('DB_USERNAME', 'username'),
            'password'  => env('DB_PASSWORD', 'secret'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

    ],

];
