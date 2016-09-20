<?php
return [
    //...
    'default' => env('DB_CONNECTION', 'mysql'),
    //...
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'scotchbox'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', 'root'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'testing' => [
            'driver'    => 'mysql',
            'host'      => env('DB_TEST_HOST', 'localhost'),
            'database'  => env('DB_TEST_DATABASE', 'scotchbox_test'),
            'username'  => env('DB_TEST_USERNAME', 'root'),
            'password'  => env('DB_TEST_PASSWORD', 'root'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
    ],
    //...
    'migrations' => 'migrations',
];