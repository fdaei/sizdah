<?php

declare(strict_types=1);

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
     | Figma personal access token — used only by scripts/export-figma-assets.sh
     | for local/manual asset re-exports (docs/ASSET-MANIFEST.md). Never called
     | at runtime by the application itself.
     */
    'figma' => [
        'token' => env('FIGMA_TOKEN'),
    ],

    /*
     | Google Tag Manager + Google Analytics 4 (gtag.js), rendered by
     | resources/views/partials/analytics-*.blade.php on every page. Off outside
     | production so local/test traffic never reaches the live property; an
     | empty ID drops that snippet. If a GA4 tag is ever added inside the GTM
     | container, clear GOOGLE_ANALYTICS_ID — otherwise page views count twice.
     */
    'google' => [
        'analytics_enabled' => (bool) env('GOOGLE_ANALYTICS_ENABLED', env('APP_ENV', 'production') === 'production'),
        'tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID', 'GTM-TPQ229H9'),
        'analytics_id' => env('GOOGLE_ANALYTICS_ID', 'G-2P5DDJGQH3'),
    ],

];
