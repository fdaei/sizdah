<?php

declare(strict_types=1);

return [
    // Shared PHP hosting does not run the optional Node renderer. Blade
    // supplies the initial content; Vue mounts the interactive site in-browser.
    'ssr' => [
        'enabled' => (bool) env('INERTIA_SSR_ENABLED', false),
        'url' => env('INERTIA_SSR_URL', 'http://127.0.0.1:13714'),
        'ensure_bundle_exists' => true,
    ],
];
