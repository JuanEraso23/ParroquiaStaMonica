<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Conexión Predeterminada
    |--------------------------------------------------------------------------
    */

    'default' => 'mariadb',

    /*
    |--------------------------------------------------------------------------
    | Conexión Base de Datos
    |--------------------------------------------------------------------------
    */

    'connections' => [

        'mariadb' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'parroquia_santa_monica'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migraciones
    |--------------------------------------------------------------------------
    */

    'migrations' => [
        'table' => 'migrations',
    ],

];