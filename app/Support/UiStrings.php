<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

/**
 * Admin-editable UI copy layered on top of the `lang/{locale}/*.php` files.
 *
 * The lang files remain the defaults (and the source of truth for keys); the
 * admin's edits are stored as a sparse override map in the `ui_strings`
 * setting: {"value": {"fa": {"footer.copyright": "…"}}}. An empty override
 * means "use the file default", so a fresh install renders exactly as before.
 *
 * Overrides reach both sides:
 *  - the Inertia `translations` prop (Vue `t()`), via {@see self::tree()};
 *  - Laravel's `__()` (Blade, mail, SEO partials), via {@see self::applyToTranslator()}.
 */
final class UiStrings
{
    public const SETTING_KEY = 'ui_strings';

    /** Lang groups that are developer/technical and never edited in the panel. */
    public const LOCKED_GROUPS = ['admin', 'validation'];

    /**
     * Full translation tree for a locale with overrides applied.
     *
     * @return array<string, mixed>
     */
    public static function tree(string $locale): array
    {
        $load = function () use ($locale): array {
            $tree = self::defaults($locale);

            foreach (self::overrides($locale) as $key => $value) {
                if (Arr::has($tree, $key)) {
                    Arr::set($tree, $key, $value);
                }
            }

            return $tree;
        };

        return app()->environment('local')
            ? $load()
            : Cache::rememberForever(self::cacheKey($locale), $load);
    }

    /** Public Vue pages do not use the admin and validation translation groups. */
    public static function publicTree(string $locale): array
    {
        return array_diff_key(self::tree($locale), array_flip(self::LOCKED_GROUPS));
    }

    /**
     * File defaults for a locale, keyed by group.
     *
     * @return array<string, mixed>
     */
    public static function defaults(string $locale): array
    {
        $path = lang_path($locale);

        if (! File::isDirectory($path)) {
            return [];
        }

        $tree = [];

        foreach (File::files($path) as $file) {
            if ($file->getExtension() === 'php') {
                $tree[$file->getFilenameWithoutExtension()] = require $file->getPathname();
            }
        }

        ksort($tree);

        return $tree;
    }

    /**
     * Editable string keys for a locale, grouped: group => [dotKey => default].
     *
     * @return array<string, array<string, string>>
     */
    public static function editable(string $locale): array
    {
        return collect(self::defaults($locale))
            ->except(self::LOCKED_GROUPS)
            ->map(fn (array $lines, string $group): array => collect(Arr::dot($lines))
                ->filter(fn (mixed $v): bool => is_string($v))
                ->mapWithKeys(fn (string $v, string $k): array => ["{$group}.{$k}" => $v])
                ->all())
            ->filter()
            ->all();
    }

    /**
     * @return array<string, string> dotKey => override
     */
    public static function overrides(string $locale): array
    {
        $all = SiteSettings::get(self::SETTING_KEY, $locale, []);

        // Stored non-translatable ({"value": {...}}) so SiteSettings returns the whole map.
        $forLocale = is_array($all) ? ($all[$locale] ?? []) : [];

        return collect(is_array($forLocale) ? $forLocale : [])
            ->filter(fn (mixed $v, mixed $k): bool => is_string($k) && is_string($v) && trim($v) !== '')
            ->all();
    }

    /**
     * Persist overrides for one locale; values equal to the file default are dropped.
     *
     * @param  array<string, string|null>  $values  dotKey => value
     */
    public static function save(string $locale, array $values): void
    {
        $editable = collect(self::editable($locale))->collapse();

        $clean = collect($values)
            ->filter(fn (mixed $v, string $k): bool => $editable->has($k)
                && is_string($v) && trim($v) !== '' && $v !== $editable->get($k))
            ->all();

        $setting = Setting::query()->firstOrNew(['key' => self::SETTING_KEY]);
        $stored = $setting->value['value'] ?? [];
        $stored[$locale] = $clean;

        $setting->fill(['group' => 'ui', 'is_translatable' => false, 'value' => ['value' => $stored]])->save();
    }

    /**
     * Register overrides with Laravel's translator so `__()` honours them.
     * Groups are loaded first: addLines() on an unloaded group would mark it
     * loaded and hide the rest of its file.
     */
    public static function applyToTranslator(string $locale): void
    {
        $overrides = self::overrides($locale);

        if ($overrides === []) {
            return;
        }

        $translator = app('translator');

        foreach (array_unique(array_map(fn (string $k): string => explode('.', $k, 2)[0], array_keys($overrides))) as $group) {
            $translator->load('*', $group, $locale);
        }

        $translator->addLines($overrides, $locale);
    }

    public static function flush(): void
    {
        $locales = array_merge(
            array_keys(config('locales.supported', [])),
            array_keys(config('locales.inactive', [])),
        );

        foreach ($locales as $locale) {
            Cache::forget(self::cacheKey((string) $locale));
        }
    }

    private static function cacheKey(string $locale): string
    {
        return "translations.{$locale}";
    }
}
