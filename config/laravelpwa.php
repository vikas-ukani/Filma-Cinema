<?php

return [
    'name' => config('app.name'),
    'manifest' => [
        'name' => env('PWA_NAME', 'My PWA App'),
        'short_name' => 'PWA',
        'start_url' => env('APP_URL'),
        'background_color' => env('PWA_BG_COLOR','#ffffff'),
        'theme_color' => env('PWA_THEME_COLOR','#000000'),
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => env('APP_URL').'images/icons/icon-72x72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => env('APP_URL').'images/icons/icon-96x96.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => env('APP_URL').'images/icons/icon-128x128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => env('APP_URL').'images/icons/icon-144x144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => env('APP_URL').'/images/icons/icon-152x152.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => env('APP_URL').'images/icons/icon-192x192.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => env('APP_URL'). 'images/icons/icon-384x384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => env('APP_URL').'images/icons/icon-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => env('APP_URL').'images/icons/splash-640x1136.png',
            '750x1334' => env('APP_URL').'images/icons/splash-750x1334.png',
            '828x1792' => env('APP_URL').'images/icons/splash-828x1792.png',
            '1125x2436' => env('APP_URL').'images/icons/splash-1125x2436.png',
            '1242x2208' => env('APP_URL').'images/icons/splash-1242x2208.png',
            '1242x2688' => env('APP_URL').'images/icons/splash-1242x2688.png',
            '1536x2048' => env('APP_URL').'images/icons/splash-1536x2048.png',
            '1668x2224' => env('APP_URL').'images/icons/splash-1668x2224.png',
            '1668x2388' => env('APP_URL').'images/icons/splash-1668x2388.png',
            '2048x2732' => env('APP_URL').'images/icons/splash-2048x2732.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Shortcut Link 1',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/shortcutlink1',
                'icons' => [
                    "src" => env('APP_URL')."images/icons/icon-72x72.png",
                    "purpose" => "any"
                ]
            ]
        ],
        'custom' => []
    ]
];
