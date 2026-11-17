<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Global settings for the article pipeline (TOC + auto internal links),
 * edited in Filament: محتوا → ابزارهای محتوا.
 */
final class ContentTools
{
    /** @var array<string, mixed> */
    public const DEFAULTS = [
        'toc_enabled' => true,
        'toc_min_headings' => 3,
        'toc_levels' => ['h2', 'h3'],
        'toc_collapsed' => false,
        'autolink_enabled' => true,
        'autolink_max_total' => 10,
    ];

    public static function get(string $key): mixed
    {
        return SiteSettings::get($key, null, self::DEFAULTS[$key] ?? null);
    }

    /** @return list<string> */
    public static function tocLevels(): array
    {
        $levels = array_values(array_intersect((array) self::get('toc_levels'), ['h2', 'h3', 'h4']));

        return $levels === [] ? ['h2', 'h3'] : $levels;
    }

    /** Fingerprint of every setting that changes pipeline output (cache key part). */
    public static function fingerprint(): string
    {
        return sha1(serialize([
            array_map(fn (string $k): mixed => self::get($k), array_keys(self::DEFAULTS)),
            Performance::lazyMedia(),
            Performance::videoFacade(),
        ]));
    }
}
