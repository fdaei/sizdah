<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Typed accessor for the admin-managed performance settings
 * (Filament: عملکرد → تنظیمات عملکرد). Every option is off/empty by default
 * so a fresh install behaves exactly as before.
 */
final class Performance
{
    /** @var array<string, mixed> key => default */
    public const DEFAULTS = [
        'perf_minify_html' => false,
        'perf_page_cache' => false,
        'perf_page_cache_ttl' => 3600,
        'perf_lazy_media' => true,
        'perf_video_facade' => true,
        'perf_preconnect' => [],
        'perf_dns_prefetch' => [],
        'perf_preload' => [],
        'perf_prefetch' => [],
    ];

    public static function minifyHtml(): bool
    {
        return (bool) self::get('perf_minify_html');
    }

    public static function pageCache(): bool
    {
        return (bool) self::get('perf_page_cache');
    }

    public static function pageCacheTtl(): int
    {
        return max(60, (int) self::get('perf_page_cache_ttl'));
    }

    public static function lazyMedia(): bool
    {
        return (bool) self::get('perf_lazy_media');
    }

    public static function videoFacade(): bool
    {
        return (bool) self::get('perf_video_facade');
    }

    /**
     * Resource hints for <head>, already filtered to safe URLs.
     *
     * @return array{preconnect: list<string>, dnsPrefetch: list<string>, preload: list<array{href: string, as: string, type: string|null, crossorigin: bool}>, prefetch: list<string>}
     */
    public static function hints(): array
    {
        $urls = fn (string $key): array => array_values(array_filter(
            array_map(fn (mixed $row): string => trim((string) (is_array($row) ? ($row['url'] ?? '') : $row)), (array) self::get($key)),
            fn (string $url): bool => self::isSafeUrl($url),
        ));

        $preload = [];
        foreach ((array) self::get('perf_preload') as $row) {
            $href = trim((string) ($row['url'] ?? ''));
            $as = (string) ($row['as'] ?? '');
            if (! self::isSafeUrl($href) || ! in_array($as, ['font', 'image', 'style', 'script', 'fetch'], true)) {
                continue;
            }
            $preload[] = [
                'href' => $href,
                'as' => $as,
                'type' => filled($row['type'] ?? null) ? (string) $row['type'] : null,
                // Fonts must be fetched in CORS mode or the preload is wasted.
                'crossorigin' => $as === 'font' || (bool) ($row['crossorigin'] ?? false),
            ];
        }

        return [
            'preconnect' => $urls('perf_preconnect'),
            'dnsPrefetch' => $urls('perf_dns_prefetch'),
            'preload' => $preload,
            'prefetch' => $urls('perf_prefetch'),
        ];
    }

    public static function get(string $key): mixed
    {
        return SiteSettings::get($key, null, self::DEFAULTS[$key] ?? null);
    }

    /** Absolute http(s) or root-relative paths only — no javascript:, data:, etc. */
    private static function isSafeUrl(string $url): bool
    {
        return $url !== '' && (str_starts_with($url, '/') || (bool) preg_match('#^https?://#i', $url));
    }
}
