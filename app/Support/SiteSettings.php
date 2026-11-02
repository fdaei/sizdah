<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Cached accessor for the key/value settings store and social links.
 *
 * Settings are edited in Filament (Pages\ManageSettings) and flushed by the
 * Setting model's saved/deleted events, so the cache never goes stale.
 */
final class SiteSettings
{
    private const CACHE_KEY = 'site.settings';

    private const SOCIAL_CACHE_KEY = 'site.social_links';

    private const TTL = 86400; // 24h — invalidated on write anyway

    /**
     * Everything the header, footer and SEO defaults need.
     *
     * @return array<string, mixed>
     */
    public static function forFrontend(string $locale): array
    {
        return [
            'siteName' => self::get('site_name', $locale, 'Sizdah'),
            'tagline' => self::get('tagline', $locale, ''),
            'description' => self::get('footer_description', $locale, ''),

            'contact' => [
                'whatsapp' => self::get('contact_whatsapp', $locale, ''),
                'phone' => self::get('contact_phone', $locale, ''),
                'email' => self::get('contact_email', $locale, ''),
                'location' => self::get('contact_location', $locale, ''),
                'workingWith' => self::get('contact_working_with', $locale, ''),
            ],

            'contactDetails' => self::contactDetails($locale),

            'socialLinks' => self::socialLinks(),

            'seo' => [
                'defaultTitle' => self::get('seo_default_title', $locale, 'Sizdah'),
                'defaultDescription' => self::get('seo_default_description', $locale, ''),
                'defaultImage' => self::mediaUrl(self::get('seo_default_image', $locale, null)),
                'organizationName' => self::get('seo_organization_name', $locale, 'Sizdah'),
            ],
        ];
    }

    /**
     * Read a single translated setting value.
     */
    public static function get(string $key, ?string $locale = null, mixed $default = null): mixed
    {
        $locale ??= app()->getLocale();

        $all = self::all();

        if (! isset($all[$key])) {
            return $default;
        }

        $value = $all[$key];

        // Non-translatable values are stored as {"value": ...}. Unwrap that
        // envelope before treating an array as a locale map.
        if (is_array($value) && array_key_exists('value', $value)) {
            $value = $value['value'];
        }

        // Translatable settings are stored as {"en": "...", "fa": "..."}.
        if (is_array($value)) {
            return $value[$locale]
                ?? $value[config('locales.fallback')]
                ?? $default;
        }

        return $value === null || $value === '' ? $default : $value;
    }

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::TTL,
            fn (): array => Setting::query()
                ->get()
                ->mapWithKeys(fn (Setting $s): array => [$s->key => $s->value])
                ->all(),
        );
    }

    /**
     * @return array<int, array{platform: string, label: string, url: string, icon: string}>
     */
    public static function socialLinks(): array
    {
        return Cache::remember(
            self::SOCIAL_CACHE_KEY,
            self::TTL,
            fn (): array => SocialLink::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (SocialLink $l): array => [
                    'platform' => $l->platform,
                    'label' => $l->label,
                    'url' => $l->url,
                    'icon' => $l->icon,
                ])
                ->all(),
        );
    }

    /**
     * Contact-card items are one ordered setting whose individual label/value
     * fields are translated. Keeping them together makes their order editable
     * in one Filament repeater without coupling the card to a fixed set of
     * fields or social platforms.
     *
     * @return array<int, array{type: string, icon: string, label: string, value: string, url: string|null}>
     */
    public static function contactDetails(string $locale): array
    {
        $stored = self::all()['contact_details'] ?? null;
        $items = is_array($stored) && array_key_exists('value', $stored)
            ? $stored['value']
            : [];

        if (! is_array($items)) {
            return [];
        }

        $fallback = config('locales.fallback');

        return collect($items)
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(function (array $item) use ($locale, $fallback): ?array {
                $label = self::localisedContactField($item['label'] ?? [], $locale, $fallback);
                $value = self::localisedContactField($item['value'] ?? [], $locale, $fallback);

                if ($label === '' || $value === '') {
                    return null;
                }

                $type = (string) ($item['type'] ?? 'custom');

                return [
                    'type' => $type,
                    'icon' => (string) ($item['icon'] ?? 'link'),
                    'label' => $label,
                    'value' => $value,
                    'url' => self::contactDetailUrl($type, $value, $item['url'] ?? null),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::SOCIAL_CACHE_KEY);
    }

    private static function mediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        // og:image must be absolute; url() resolves against the current request host.
        return url(Storage::disk('public')->url($path));
    }

    private static function localisedContactField(mixed $field, string $locale, string $fallback): string
    {
        if (! is_array($field)) {
            return '';
        }

        return (string) ($field[$locale] ?? $field[$fallback] ?? '');
    }

    private static function contactDetailUrl(string $type, string $value, mixed $url): ?string
    {
        if (is_string($url) && $url !== '') {
            return $url;
        }

        return match ($type) {
            'phone' => 'tel:'.preg_replace('/\\s+/', '', $value),
            'email' => 'mailto:'.$value,
            'whatsapp' => ($number = preg_replace('/\\D+/', '', $value)) !== '' ? 'https://wa.me/'.$number : null,
            default => null,
        };
    }
}
