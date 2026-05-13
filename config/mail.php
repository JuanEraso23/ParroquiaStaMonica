<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mailer Predeterminado
    |--------------------------------------------------------------------------
    */

    'default' => 'smtp',

    /*
    |--------------------------------------------------------------------------
    | Configuración SMTP
    |--------------------------------------------------------------------------
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'timeout' => null,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Remitente Global
    |--------------------------------------------------------------------------
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS'),
        'name' => env('MAIL_FROM_NAME', 'Parroquia Santa Monica'),
    ],

];
