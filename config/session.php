<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de Sesiones
    |--------------------------------------------------------------------------
    */

    'driver' => env('SESSION_DRIVER', 'file'),

    'lifetime' => env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    'encrypt' => env('SESSION_ENCRYPT', false),

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Cookies de Sesión
    |--------------------------------------------------------------------------
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'parroquia_santa_monica'), '_') . '_session'
    ),

    'path' => env('SESSION_PATH', '/'),

    'domain' => env('SESSION_DOMAIN'),

    'secure' => env('SESSION_SECURE_COOKIE', false),

    'http_only' => true,

    'same_site' => 'lax',

    /*
    |--------------------------------------------------------------------------
    | Limpieza Automática
    |--------------------------------------------------------------------------
    */

    'lottery' => [2, 100],

];
