<?php

declare(strict_types=1);

use App\Support\SiteSettings;

if (! function_exists('setting')) {
    /**
     * Read a (translated) site setting: `setting('contact_phone')`.
     */
    function setting(string $key, mixed $default = null, ?string $locale = null): mixed
    {
        return SiteSettings::get($key, $locale, $default);
    }
}
