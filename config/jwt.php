<?php

return [

    'access' => [
        'lifetime' => 60 * 15,
    ],

    'refresh' => [
        'length' => 64,
        'lifetime' => 60 * 60 * 24 * 180,
    ],

    'keys' => [
        'public' => env('JWT_PUBLIC_KEY_PATH', storage_path('jwt/public.key')),
        'private' => env('JWT_PRIVATE_KEY_PATH', storage_path('jwt/private.key')),
        'type' => env('JWT_KEY_TYPE', 'RS256'),
    ],

    'issued_by' => env('APP_DOMAIN_FRONTEND', 'newtumbler.test'),
    'audience' => env('APP_DOMAIN_FRONTEND', 'newtumbler.test'),

];
