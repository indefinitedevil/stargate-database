<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Would you like the install button to appear on all pages?
      Set true/false
    |--------------------------------------------------------------------------
    */

    'install-button' => true,

    /*
    |--------------------------------------------------------------------------
    | PWA Manifest Configuration
    |--------------------------------------------------------------------------
    |  php artisan erag:update-manifest
    */

    'manifest' => [
        'name' => 'Stargate Character Database',
        'short_name' => 'Stargate',
        'background_color' => '#231356',
        'display' => 'minimal-ui',
        'description' => 'The database for Stargate characters and downtimes.',
        'theme_color' => '#231356',
        'icons' => [
            [
                'src' => 'images/sef-logo-512.png',
                'sizes' => '512x512',
                'type' => 'image/png',
            ],
            [
                'src' => 'images/sef-logo-192.png',
                'sizes' => '192x192',
                'type' => 'image/png',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug Configuration
    |--------------------------------------------------------------------------
    | Toggles the application's debug mode based on the environment variable
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Livewire Integration
    |--------------------------------------------------------------------------
    | Set to true if you're using Livewire in your application to enable
    | Livewire-specific PWA optimizations or features.
    */

    'livewire-app' => false,
];
