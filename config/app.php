<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración General
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Parroquia Santa Monica'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Localización
    |--------------------------------------------------------------------------
    */

    'timezone' => 'America/Bogota',

    'locale' => env('APP_LOCALE', 'es'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'es_CO'),

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Modo Mantenimiento
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
    ],

];