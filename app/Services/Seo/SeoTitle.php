<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Support\SiteSettings;

/**
 * Yoast-style title templates: %title% %sep% %sitename% (+ %tagline%, %page%).
 *
 * An empty SEO title falls back to the global template. Any value is run
 * through the same variable replacement, so "%title% | برند" works too.
 */
final class SeoTitle
{
    public const DEFAULT_TEMPLATE = '%title% %sep% %sitename%';

    public const SEPARATORS = ['—', '–', '-', '|', '·', '»', '•'];

    public static function render(?string $template, string $title, ?string $locale = null, ?string $type = null): string
    {
        $locale ??= app()->getLocale();
        $template = trim((string) $template);

        if ($template === '') {
            $template = (string) (SiteSettings::get($type ? "seo_title_template_{$type}" : 'seo_title_template', $locale)
                ?: SiteSettings::get('seo_title_template', $locale)
                ?: self::DEFAULT_TEMPLATE);
        }

        $page = request()->routeIs('insights.index', 'insights.category', 'insights.tag')
            ? max(1, request()->integer('page', 1)) : 1;
        if ($page > 1 && ! str_contains($template, '%page%')) {
            $template .= ' %sep% '.__('seo.pagination_title', ['page' => $page]);
        }

        $out = strtr($template, [
            '%title%' => $title,
            '%sitename%' => (string) SiteSettings::get('site_name', $locale, config('app.name')),
            '%sep%' => self::separator(),
            '%tagline%' => (string) SiteSettings::get('tagline', $locale, ''),
            '%page%' => $page > 1 ? (string) $page : '',
        ]);

        // Collapse separators left dangling by an empty variable.
        $sep = preg_quote(self::separator(), '/');
        $out = preg_replace("/^\s*{$sep}\s*|\s*{$sep}\s*$/u", '', $out) ?? $out;

        return trim(preg_replace('/\s{2,}/u', ' ', $out) ?? $out);
    }

    public static function separator(): string
    {
        $sep = (string) SiteSettings::get('seo_title_separator', null, '—');

        return in_array($sep, self::SEPARATORS, true) ? $sep : '—';
    }
}
