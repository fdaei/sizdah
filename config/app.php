<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Sizdah'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    /*
     | 301 every request whose scheme/host differs from APP_URL (www, http)
     | or that carries a trailing slash, in one hop — see
     | App\Http\Middleware\RedirectToCanonicalHost. On by default in
     | production only, so local `php artisan serve` keeps working.
     */
    'canonical_redirect' => (bool) env('APP_CANONICAL_REDIRECT', env('APP_ENV', 'production') === 'production'),

    'timezone' => 'UTC',

    /*
     | The `locale` here is the Laravel/validation-message locale, distinct
     | from config/locales.php which drives the public-facing en/fa/ar system.
     | Kept in sync with locales.default so validation strings load correctly
     | before SetLocale middleware runs (e.g. during console commands).
     */
    'locale' => env('APP_LOCALE', 'fa'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'fa'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => array_filter(explode(',', (string) env('APP_PREVIOUS_KEYS', ''))),

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
     | Core framework providers must remain in this list. Package providers are
     | added through Composer discovery, while application providers are merged
     | from bootstrap/providers.php.
     */
    'providers' => ServiceProvider::defaultProviders()->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        // Add custom facade aliases here if needed.
    ])->toArray(),

];
