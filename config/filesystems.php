<?php

declare(strict_types=1);

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        /*
         | Every publicly-editable image (project covers, team photos, client
         | logos, etc.) is written here by Filament's FileUpload components
         | and served through `php artisan storage:link` -> public/storage.
         */
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            // Root-relative on purpose: an absolute APP_URL-based URL breaks
            // every image (admin previews + public site) whenever the app is
            // served from a different host/port than APP_URL (e.g. `artisan
            // serve` falling back to :8001). Set PUBLIC_DISK_URL for a CDN.
            'url' => env('PUBLIC_DISK_URL', '/storage'),
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => (bool) env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
