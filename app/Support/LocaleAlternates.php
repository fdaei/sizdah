<?php

declare(strict_types=1);

namespace App\Support;

use App\Contracts\HasLocalisedSlugs;
use App\Models\Page;
use App\Models\Service;
use App\Services\SeoBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Builds the URL of the current page in every supported locale.
 *
 * Powers two things that must never disagree:
 *   1. the language switcher in the header
 *   2. the <link rel="alternate" hreflang="..."> tags
 *
 * Slug-bearing routes are the interesting case: a post has a *different* slug
 * per locale, so /en/insights/content-direction must map to
 * /fa/insights/جهت-محتوا rather than reusing the English slug. Models that
 * implement HasLocalisedSlugs are asked for their translated slug; everything
 * else just swaps the locale segment.
 */
final class LocaleAlternates
{
    /**
     * @return array<string, string> locale code => absolute URL
     */
    public static function for(Request $request): array
    {
        $supported = array_keys(config('locales.supported'));
        $route = $request->route();

        if ($route === null) {
            return array_fill_keys($supported, $request->url());
        }

        $name = $route->getName();
        $params = $route->parameters();
        $pageKey = [
            'home' => 'home', 'about' => 'about', 'contact' => 'contact',
            'services' => 'services', 'work.index' => 'work', 'insights.index' => 'insights',
            'legal.privacy' => 'privacy-policy', 'legal.terms' => 'terms',
        ][$name] ?? null;
        $page = $pageKey === null ? null : Page::query()->key($pageKey)->published()
            ->with(['translations', 'seoMeta'])->first();

        // Service detail routes currently resolve their slug in the controller.
        // Resolve it here too so another language never inherits the current slug.
        if ($name === 'services.show' && is_string($params['service'] ?? null)) {
            $service = Service::query()->forDisplay()->whereHas('translations', fn ($query) => $query
                ->where('locale', app()->getLocale())->where('slug', $params['service']))->first();
            if ($service !== null) {
                $params['service'] = $service;
            }
        }

        foreach ($params as $param) {
            if ($param instanceof HasLocalisedSlugs || $param instanceof Service) {
                // Listing scopes load only the current/fallback language. Read
                // real translations before deciding which alternatives exist.
                $param->load(['translations', 'seoMeta']);
            }
        }

        $alternates = [];

        foreach ($supported as $locale) {
            $url = self::buildUrl($name, $params, $locale, $request);
            if ($page !== null && (blank($page->getTranslation('title', $locale, false))
                || $page->seoFor($locale)?->robots_index === false
                || (filled($canonical = $page->seoFor($locale)?->canonical_url)
                    && SeoBuilder::stripTracking($canonical) !== $url))) {
                continue;
            }
            if ($url !== null) {
                $alternates[$locale] = $url;
            }
        }

        return $alternates;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private static function buildUrl(
        ?string $name,
        array $params,
        string $locale,
        Request $request,
    ): ?string {
        // Unnamed route (rare — fallback pages): swap the first segment.
        if ($name === null || ! Route::has($name)) {
            $segments = $request->segments();
            $segments[0] = $locale;

            return url(implode('/', $segments));
        }

        $translated = $params;

        foreach ($params as $key => $value) {
            // Route-model-bound parameter that carries per-locale slugs.
            if ($value instanceof HasLocalisedSlugs || $value instanceof Service) {
                $slug = $value->getTranslation('slug', $locale, false);
                if (blank($slug) || $value->seoFor($locale)?->robots_index === false) {
                    return null;
                }
                $translated[$key] = $slug;

                continue;
            }

            if (is_object($value) && method_exists($value, 'getRouteKey')) {
                $translated[$key] = $value->getRouteKey();
            }
        }

        $translated['locale'] = $locale;

        $url = route($name, $translated, absolute: true);
        foreach ($params as $value) {
            if (($value instanceof HasLocalisedSlugs || $value instanceof Service)
                && filled($canonical = $value->seoFor($locale)?->canonical_url)
                && rtrim(SeoBuilder::stripTracking($canonical), '/') !== rtrim($url, '/')) {
                return null;
            }
        }

        // Page 2 must link to page 2 in the other language, not to page 1.
        if ($request->routeIs('insights.index', 'insights.category', 'insights.tag') && $request->integer('page', 1) > 1) {
            $url .= '?page='.$request->integer('page');
        }

        return $url;
    }
}
