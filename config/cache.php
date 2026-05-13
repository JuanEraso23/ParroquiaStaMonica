<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Predeterminado
    |--------------------------------------------------------------------------
    */

    'default' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Stores de Cache
    |--------------------------------------------------------------------------
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Prefijo de Cache
    |--------------------------------------------------------------------------
    */

    'prefix' => Str::slug('parroquia-santa-monica') . '-cache',

];
