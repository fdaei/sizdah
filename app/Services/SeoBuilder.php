<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Services\Seo\SchemaNodes;
use App\Services\Seo\SeoAnalyzer;
use App\Services\Seo\SeoTitle;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Builds the SeoMeta payload for every public page type.
 *
 * This is the single SEO contract (technical SEO audit 2026-10-04, SEO-DEV-02):
 * resources/views/partials/seo.blade.php renders it into the initial HTML —
 * so title/description/canonical/robots/hreflang/OG/JSON-LD never depend on
 * JavaScript or on the SSR process — and resources/js/Components/SeoHead.vue
 * re-renders the same keys on client-side Inertia navigations.
 *
 * Falls back through: entity SEO override -> entity title/excerpt ->
 * site defaults. Canonical is always the absolute, self-referencing URL of the
 * current locale's version of the page.
 */
final class SeoBuilder
{
    /**
     * Detail route => its listing route, for BreadcrumbList.
     */
    private const PARENT_ROUTES = [
        'work.show' => 'work.index',
        'insights.show' => 'insights.index',
        'services.show' => 'services',
    ];

    /**
     * @return array<string, mixed>
     */
    /**
     * @param  list<array<string, mixed>>  $extraNodes  e.g. SchemaNodes::faqPage(), ::people()
     */
    public static function forPage(?Page $page, string $canonical, ?string $image = null, array $extraNodes = []): array
    {
        return self::assemble(
            title: (string) $page?->getTranslation('title'),
            description: $page?->getTranslation('seo_description') ?: $page?->getTranslation('description'),
            image: $image,
            canonical: $canonical,
            type: 'website',
            meta: $page?->seoFor(),
            legacyTitle: $page?->getTranslation('seo_title'),
            schemaType: self::pageSchemaType($page?->key),
            extraNodes: $extraNodes,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function forProject(Project $project): array
    {
        return self::assemble(
            title: (string) $project->getTranslation('title'),
            description: $project->getTranslation('seo_description') ?: $project->getTranslation('excerpt'),
            image: $project->cover_path === null
                ? null
                : MediaTransformer::url($project->cover_path),
            canonical: $project->url(),
            type: 'website',
            meta: $project->seoFor(),
            legacyTitle: $project->getTranslation('seo_title'),
            schemaType: 'CreativeWork',
            extra: array_filter([
                'dateCreated' => $project->year ? (string) $project->year : null,
                'creator' => ['@id' => rtrim(url('/'), '/').'/#organization'],
            ]),
            modifiedAt: $project->updated_at?->toIso8601String(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function forService(Service $service, string $canonical): array
    {
        return self::assemble(
            title: (string) $service->getTranslation('title'),
            description: (string) $service->getTranslation('description'),
            image: null,
            canonical: $canonical,
            type: 'website',
            schemaType: 'Service',
            meta: $service->seoFor(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function forPost(Post $post): array
    {
        return self::assemble(
            title: (string) $post->getTranslation('title'),
            description: $post->getTranslation('seo_description') ?: $post->getTranslation('excerpt'),
            image: $post->cover_path === null
                ? null
                : MediaTransformer::url($post->cover_path),
            canonical: $post->url(),
            type: 'article',
            publishedAt: $post->published_at?->toIso8601String(),
            modifiedAt: $post->updated_at?->toIso8601String(),
            author: $post->author?->name,
            meta: $post->seoFor(),
            legacyTitle: $post->getTranslation('seo_title'),
            section: $post->category?->getTranslation('name'),
            tags: $post->tags->map(fn ($t): string => (string) $t->getTranslation('name'))->filter()->values()->all(),
            wordCount: count(SeoAnalyzer::words(SeoAnalyzer::plain((string) $post->getTranslation('content')))),
            extraNodes: SchemaNodes::videos(
                (string) $post->getTranslation('content'),
                $post->url(),
                (string) $post->getTranslation('title'),
                $post->getTranslation('excerpt'),
                $post->cover_path ? self::absoluteUrl(MediaTransformer::url($post->cover_path)) : null,
                $post->published_at?->toIso8601String(),
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private static function assemble(
        ?string $title,
        ?string $description,
        ?string $image,
        string $canonical,
        string $type,
        ?string $publishedAt = null,
        ?string $modifiedAt = null,
        ?string $author = null,
        bool $noindex = false,
        ?string $schemaType = null,
        ?SeoMeta $meta = null,
        ?string $legacyTitle = null,
        ?string $section = null,
        array $tags = [],
        ?int $wordCount = null,
        array $extra = [],
        array $extraNodes = [],
    ): array {
        $locale = app()->getLocale();
        $request = request();

        /*
         | Fallback chain: item SEO field -> legacy translation column ->
         | type/global template -> content (title / excerpt, auto-trimmed).
         */
        $title = $title ?: SiteSettings::get('seo_default_title', $locale, 'Sizdah');
        $template = $meta?->title ?: ($legacyTitle ? $legacyTitle.' %sep% %sitename%' : null);
        $documentTitle = SeoTitle::render($template, $title, $locale, $type);

        $description = $meta?->description
            ?: self::trimDescription($description ?: SiteSettings::get('seo_default_description', $locale, ''));
        // og:image / twitter:image / JSON-LD image must be absolute URLs.
        $image = self::absoluteUrl($image ?: SiteSettings::forFrontend($locale)['seo']['defaultImage']);

        if ($meta?->canonical_url) {
            $canonical = $meta->canonical_url;
        }
        if ($meta?->robots_index === false) {
            $noindex = true;
        }

        /*
         | Query-string variants (SEO-CHK-10). Filters (?category=, ?service=)
         | keep the clean listing URL as canonical; pagination is
         | self-canonical so page 2+ stays indexable; on-site search results
         | are noindex so ?q= can't mint an unbounded URL space.
         */
        $pageNumber = $request->integer('page', 1);

        if ($pageNumber > 1 && $canonical === $request->url()) {
            $canonical .= '?page='.$pageNumber;
        }

        if ($request->filled('q') && (bool) SiteSettings::get('seo_noindex_search', null, true)) {
            $noindex = true;
        }

        if ($pageNumber > 1 && (bool) SiteSettings::get('seo_noindex_paginated', null, false)) {
            $noindex = true;
        }

        // Whole-site "Discourage search engines" (settings) — forced on staging.
        if (self::siteNoindex()) {
            $noindex = true;
        }

        $schemaType = $meta?->schema_type ?: $schemaType;
        $articleType = $type === 'article' ? ($meta?->article_type ?: 'BlogPosting') : null;
        $keywords = $meta?->schema_data['keywords'] ?? null
            ?: implode('، ', array_filter([$meta?->focus_keyword, ...((array) $meta?->secondary_keywords)])) ?: null;

        $schema = self::schema(
            $meta?->schema_data['headline'] ?? null ?: $title, $description, $image, $canonical, $type, $schemaType,
            $publishedAt, $modifiedAt, $author,
            articleType: $articleType,
            breadcrumbTitle: $meta?->breadcrumb_title ?: $title,
            extra: array_filter([
                'keywords' => $keywords,
                'articleSection' => $section,
                'wordCount' => $wordCount ?: null,
                'about' => $meta?->schema_data['about'] ?? null,
                'serviceType' => $meta?->schema_data['service_type'] ?? null,
                'areaServed' => $meta?->schema_data['area_served'] ?? null,
            ]) + $extra,
        );

        if ($extraNodes !== []) {
            $schema['@graph'] = [...$schema['@graph'], ...$extraNodes];
        }

        if ($custom = self::customSchema($meta?->custom_schema)) {
            $schema['@graph'] = [...$schema['@graph'], ...$custom];
        }

        $ogImage = self::media($meta?->og_image) ?? $image;

        return [
            'title' => $title,
            'documentTitle' => $documentTitle,
            'description' => $description,
            'image' => $ogImage,
            'canonical' => self::stripTracking($canonical),
            'type' => $type,
            'publishedAt' => $publishedAt,
            'modifiedAt' => $modifiedAt,
            'author' => $author,
            'noindex' => $noindex,
            'robots' => self::robots($noindex, $meta),
            'og' => [
                'title' => $meta?->og_title ?: $documentTitle,
                'description' => $meta?->og_description ?: $description,
                'type' => $meta?->og_type ?: $type,
                'section' => $section,
                'tags' => $tags,
            ],
            'twitter' => [
                'card' => $meta?->twitter_card ?: ($ogImage ? (SiteSettings::get('seo_default_twitter_card', null, 'summary_large_image') ?: 'summary_large_image') : 'summary'),
                'title' => $meta?->twitter_title ?: $meta?->og_title ?: $documentTitle,
                'description' => $meta?->twitter_description ?: $meta?->og_description ?: $description,
                'image' => self::media($meta?->twitter_image) ?? $ogImage,
            ],
            'schema' => $schema,
        ];
    }

    /**
     * JSON-LD @graph (SEO-CHK-08): Organization + WebSite on every page, the
     * page's own node, and a BreadcrumbList on everything below Home.
     *
     * @return array<string, mixed>
     */
    private static function schema(
        string $title,
        string $description,
        ?string $image,
        string $canonical,
        string $type,
        ?string $schemaType,
        ?string $publishedAt,
        ?string $modifiedAt,
        ?string $author,
        ?string $articleType = null,
        ?string $breadcrumbTitle = null,
        array $extra = [],
    ): array {
        $locale = app()->getLocale();
        $settings = SiteSettings::forFrontend($locale);
        $root = rtrim(url('/'), '/');
        $home = route('home', ['locale' => $locale]);
        $inLanguage = config("locales.supported.{$locale}.html_lang");

        $organizationId = "{$root}/#organization";
        $websiteId = "{$root}/#website";

        $organization = self::organization($settings, $root, $home, $organizationId, $locale);

        $website = [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'name' => $settings['siteName'],
            'url' => $home,
            'inLanguage' => $inLanguage,
            'publisher' => ['@id' => $organizationId],
        ];

        $breadcrumbs = self::breadcrumbs($breadcrumbTitle ?? $title, $canonical);

        $node = array_filter([
            '@type' => $type === 'article'
                ? ($articleType === 'None' ? 'WebPage' : ($articleType ?? 'Article'))
                : ($schemaType ?? 'WebPage'),
            '@id' => "{$canonical}#main",
            'url' => $canonical,
            'name' => $title,
            'description' => $description ?: null,
            'inLanguage' => $inLanguage,
            'image' => $image,
            'isPartOf' => $schemaType === 'Service' ? null : ['@id' => $websiteId],
            'provider' => $schemaType === 'Service' ? ['@id' => $organizationId] : null,
            'breadcrumb' => $breadcrumbs === null ? null : ['@id' => "{$canonical}#breadcrumb"],
        ]);

        $node += array_filter([
            'keywords' => $extra['keywords'] ?? null,
            'about' => $extra['about'] ?? null,
            'serviceType' => $schemaType === 'Service' ? ($extra['serviceType'] ?? null) : null,
            'areaServed' => $schemaType === 'Service' ? ($extra['areaServed'] ?? null) : null,
            'dateCreated' => $extra['dateCreated'] ?? null,
            'creator' => $extra['creator'] ?? null,
            // WebPage-family nodes name their main image explicitly.
            'primaryImageOfPage' => $image && $type !== 'article' && str_ends_with((string) ($schemaType ?? 'WebPage'), 'Page')
                ? ['@type' => 'ImageObject', 'url' => $image]
                : null,
        ]);

        if ($type === 'article' && $articleType !== 'None') {
            $node += array_filter([
                'articleSection' => $extra['articleSection'] ?? null,
                'wordCount' => $extra['wordCount'] ?? null,
            ]);
            $node += array_filter([
                'headline' => mb_substr($title, 0, 110),
                'datePublished' => $publishedAt,
                'dateModified' => $modifiedAt ?? $publishedAt,
                'author' => $author === null ? null : ['@type' => 'Person', 'name' => $author],
                'publisher' => ['@id' => $organizationId],
                'mainEntityOfPage' => $canonical,
            ]);
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([$organization, $website, $node, $breadcrumbs])),
        ];
    }

    /**
     * Home -> [listing] -> current page, derived from the current route name
     * so no controller has to hand-assemble it.
     *
     * @return array<string, mixed>|null
     */
    private static function breadcrumbs(string $title, string $canonical): ?array
    {
        $name = Route::currentRouteName();

        if ($name === null || $name === 'home' || ! (bool) SiteSettings::get('seo_breadcrumbs_enabled', null, true)) {
            return null;
        }

        $items = [[SiteSettings::get('seo_breadcrumb_home') ?: __('seo.breadcrumbs.home'), route('home')]];

        if (isset(self::PARENT_ROUTES[$name])) {
            $parent = self::PARENT_ROUTES[$name];
            $items[] = [__("seo.breadcrumbs.{$parent}"), route($parent)];
        }

        $items[] = [$title, $canonical];

        return [
            '@type' => 'BreadcrumbList',
            '@id' => "{$canonical}#breadcrumb",
            'itemListElement' => array_map(
                fn (array $item, int $i): array => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $item[0],
                    'item' => $item[1],
                ],
                $items,
                array_keys($items),
            ),
        ];
    }

    /** Page-key -> default WebPage subtype (Yoast "Page type" default). */
    private static function pageSchemaType(?string $key): ?string
    {
        return match ($key) {
            'about' => 'AboutPage',
            'contact' => 'ContactPage',
            'work', 'insights', 'services' => 'CollectionPage',
            default => null,
        };
    }

    private static function trimDescription(string $text): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags($text)) ?? '');

        return mb_strlen($text) <= 155 ? $text : rtrim(mb_substr($text, 0, 154)).'…';
    }

    /** Drop utm_* / click-id params so tracked links never mint canonicals. */
    public static function stripTracking(string $url): string
    {
        $parts = parse_url($url);
        if (! isset($parts['query'])) {
            return $url;
        }
        parse_str($parts['query'], $query);
        $query = array_filter(
            $query,
            fn (string $k): bool => ! preg_match('/^(utm_|fbclid$|gclid$|msclkid$|yclid$|_ga$)/i', $k),
            ARRAY_FILTER_USE_KEY,
        );
        $base = strtok($url, '?');

        return $query === [] ? $base : $base.'?'.http_build_query($query);
    }

    private static function robots(bool $noindex, ?SeoMeta $meta): string
    {
        $adv = (array) ($meta?->robots_advanced ?? []);
        $flags = (array) ($adv['flags'] ?? []);
        $directives = [
            $noindex ? 'noindex' : 'index',
            $meta?->robots_follow === false ? 'nofollow' : 'follow',
            ...array_values(array_intersect($flags, ['noarchive', 'nosnippet', 'noimageindex'])),
        ];
        if (! $noindex) {
            $directives[] = 'max-snippet:'.(int) ($adv['max_snippet'] ?? -1);
            $directives[] = 'max-image-preview:'.(in_array($adv['max_image_preview'] ?? null, ['none', 'standard', 'large'], true) ? $adv['max_image_preview'] : 'large');
            $directives[] = 'max-video-preview:'.(int) ($adv['max_video_preview'] ?? -1);
        }

        return implode(', ', $directives);
    }

    /** @return list<array<string, mixed>> */
    private static function customSchema(?string $json): array
    {
        if (blank($json)) {
            return [];
        }
        $decoded = json_decode((string) $json, true);
        if (! is_array($decoded)) {
            return [];
        }
        $nodes = array_is_list($decoded) ? $decoded : ($decoded['@graph'] ?? [$decoded]);

        return array_values(array_map(function (array $n): array {
            unset($n['@context']);

            return $n;
        }, array_filter($nodes, 'is_array')));
    }

    private static function media(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return self::absoluteUrl(str_starts_with($path, 'http') ? $path : Storage::disk('public')->url($path));
    }

    private static function absoluteUrl(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        return preg_match('#^https?://#i', $url) ? $url : url('/'.ltrim($url, '/'));
    }

    public static function siteNoindex(): bool
    {
        return app()->environment('staging') || (bool) SiteSettings::get('seo_noindex_site', null, false);
    }

    /**
     * Knowledge Graph node (SEO settings): Organization or Person, optionally
     * also a LocalBusiness subtype with address / geo / hours. Keeps the
     * stable `#organization` @id so every other node can reference it.
     *
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    private static function organization(array $settings, string $root, string $home, string $id, string $locale): array
    {
        $get = fn (string $key) => SiteSettings::get($key, $locale);
        $sameAs = array_values(array_filter(array_column($settings['socialLinks'], 'url'))) ?: null;

        if ($get('seo_kg_type') === 'Person') {
            return array_filter([
                '@type' => 'Person',
                '@id' => $id,
                'name' => $settings['seo']['organizationName'],
                'alternateName' => $get('seo_kg_alternate_name') ?: null,
                'url' => $home,
                'sameAs' => $sameAs,
            ]);
        }

        $business = $get('seo_kg_business_type') ?: null;
        $phone = $get('seo_kg_phone') ?: ($settings['contact']['phone'] ?: null);
        $email = $get('seo_kg_email') ?: ($settings['contact']['email'] ?: null);
        $logo = self::media($get('seo_kg_logo')) ?? "{$root}/icon-512.png";

        $node = [
            '@type' => $business ? ['Organization', $business] : 'Organization',
            '@id' => $id,
            'name' => $settings['seo']['organizationName'],
            'alternateName' => $get('seo_kg_alternate_name') ?: null,
            'url' => $home,
            'logo' => ['@type' => 'ImageObject', '@id' => "{$root}/#logo", 'url' => $logo, 'contentUrl' => $logo],
            'image' => ['@id' => "{$root}/#logo"],
            'email' => $email,
            'telephone' => $phone,
            'sameAs' => $sameAs,
        ];

        if ($business) {
            $address = array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => $get('seo_kg_street') ?: null,
                'addressLocality' => $get('seo_kg_locality') ?: null,
                'addressRegion' => $get('seo_kg_region') ?: null,
                'postalCode' => $get('seo_kg_postal_code') ?: null,
                'addressCountry' => $get('seo_kg_country') ?: null,
            ]);
            $lat = $get('seo_kg_lat');
            $lng = $get('seo_kg_lng');

            $node += array_filter([
                'address' => count($address) > 1 ? $address : null,
                'geo' => is_numeric($lat) && is_numeric($lng)
                    ? ['@type' => 'GeoCoordinates', 'latitude' => (float) $lat, 'longitude' => (float) $lng]
                    : null,
                'priceRange' => $get('seo_kg_price_range') ?: null,
                'areaServed' => $get('seo_kg_area_served') ?: null,
                'openingHoursSpecification' => array_values(array_map(
                    fn (array $h): array => [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => array_values((array) ($h['days'] ?? [])),
                        'opens' => substr((string) ($h['opens'] ?? ''), 0, 5),
                        'closes' => substr((string) ($h['closes'] ?? ''), 0, 5),
                    ],
                    array_filter((array) $get('seo_kg_hours'), fn ($h): bool => is_array($h) && ! empty($h['days'])),
                )) ?: null,
            ]);
        }

        return array_filter($node, fn ($v): bool => $v !== null && $v !== '');
    }
}
