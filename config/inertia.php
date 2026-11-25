<?php

declare(strict_types=1);

return [
    // Public pages use native Blade. This retained adapter is only for legacy PHP integrations.
    'testing' => ['ensure_pages_exist' => false],
    'ssr' => [
        'enabled' => false,
        'url' => env('INERTIA_SSR_URL', 'http://127.0.0.1:13714'),
        'ensure_bundle_exists' => true,
    ],
];
