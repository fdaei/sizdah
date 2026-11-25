<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Industry;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Project;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\ServiceRating;
use App\Services\Seo\SchemaNodes;
use App\Services\Seo\SeoAnalyzer;
use App\Services\Seo\SeoTitle;
use App\Support\RichContent;
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
    /** articleBody / text cap — keeps the inline JSON-LD a sane size. */
    private const BODY_MAX = 30000;

    private const PARENT_ROUTES = [
        'work.show' => 'work.index',
        'work.industry' => 'work.index',
        'insights.show' => 'insights.index',
        'insights.category' => 'insights.index',
        'insights.tag' => 'insights.index',
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
            description: $page?->getTranslation('seo_description') ?: $page?->getTranslation('description')
                ?: RichContent::plain($page?->getTranslation('content')),
            image: $image,
            canonical: $canonical,
            type: 'website',
            meta: $page?->seoFor(),
            legacyTitle: $page?->getTranslation('seo_title'),
            schemaType: self::pageSchemaType($page?->key),
            extraNodes: $extraNodes,
            modifiedAt: $page?->updated_at?->toIso8601String(),
            bodyHtml: $page === null ? '' : SchemaNodes::bodyHtml(
                [$page->getTranslation('content')],
                [$page->getTranslation('subtitle')],
                $page,
            ),
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
            extraNodes: self::coverNodes($project->cover_path),
            extra: array_filter([
                'dateCreated' => $project->year ? (string) $project->year : null,
                'creator' => ['@id' => rtrim(url('/'), '/').'/#organization'],
            ]),
            modifiedAt: $project->updated_at?->toIso8601String(),
            bodyHtml: SchemaNodes::bodyHtml(
                [],
                [
                    $project->getTranslation('subtitle'),
                    $project->getTranslation('excerpt'),
                    $project->getTranslation('challenge'),
                    $project->getTranslation('challenge_points'),
                    $project->getTranslation('results_summary'),
                ],
                $project,
            ),
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
            image: $service->image_path ? MediaTransformer::url($service->image_path) : null,
            canonical: $canonical,
            type: 'website',
            schemaType: 'Service',
            meta: $service->seoFor(),
            extra: array_filter(['aggregateRating' => ServiceRating::aggregateNode($service)]),
            modifiedAt: $service->updated_at?->toIso8601String(),
            bodyHtml: SchemaNodes::bodyHtml([], [
                $service->getTranslation('description'),
                $service->getTranslation('features'),
            ]),
        );
    }

    /**
     * Category archive: CollectionPage by default, every field of the
     * category's SEO tab (title, description, robots, canonical, OG,
     * Twitter, schema, breadcrumb title) flows through assemble().
     *
     * @return array<string, mixed>
     */
    public static function forCategory(PostCategory $category): array
    {
        return self::assemble(
            title: (string) $category->getTranslation('name'),
            description: $category->getTranslation('description') ?: __('seo.archive_descriptions.category', [
                'name' => $category->getTranslation('name'),
                'site' => SiteSettings::get('site_name', app()->getLocale(), config('app.name')),
            ]),
            image: null,
            canonical: $category->url(),
            type: 'website',
            schemaType: 'CollectionPage',
            noindex: (bool) SiteSettings::get('seo_noindex_post_category', null, false),
            trail: self::categoryTrail($category->ancestors()),
            meta: $category->seoFor(),
            modifiedAt: $category->updated_at?->toIso8601String(),
            bodyHtml: SchemaNodes::bodyHtml([], [$category->getTranslation('description')]),
        );
    }

    /** SEO metadata for a tag archive. */
    public static function forTag(PostTag $tag): array
    {
        return self::assemble(
            title: (string) $tag->getTranslation('name'),
            description: __('seo.archive_descriptions.tag', [
                'name' => $tag->getTranslation('name'),
                'site' => SiteSettings::get('site_name', app()->getLocale(), config('app.name')),
            ]),
            image: null,
            canonical: $tag->url(),
            type: 'website',
            noindex: (bool) SiteSettings::get('seo_noindex_post_tag', null, false),
            schemaType: 'CollectionPage',
            meta: $tag->seoFor(),
            modifiedAt: $tag->updated_at?->toIso8601String(),
        );
    }

    /** SEO metadata for a project category archive. */
    public static function forIndustry(Industry $industry): array
    {
        return self::assemble(
            title: (string) $industry->getTranslation('name'),
            description: __('seo.archive_descriptions.industry', [
                'name' => $industry->getTranslation('name'),
                'site' => SiteSettings::get('site_name', app()->getLocale(), config('app.name')),
            ]),
            image: null,
            canonical: $industry->url(),
            type: 'website',
            schemaType: 'CollectionPage',
            meta: $industry->seoFor(),
            modifiedAt: $industry->updated_at?->toIso8601String(),
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
            extraNodes: self::coverNodes($post->cover_path),
            trail: $post->category === null ? [] : self::categoryTrail([...$post->category->ancestors(), $post->category]),
            tags: $post->tags->map(fn ($t): string => (string) $t->getTranslation('name'))->filter()->values()->all(),
            wordCount: count(SeoAnalyzer::words(SeoAnalyzer::plain((string) $post->getTranslation('content')))),
            bodyHtml: SchemaNodes::bodyHtml([$post->getTranslation('content')]),
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
        string $bodyHtml = '',
        array $trail = [],
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
        $pageNumber = $request->routeIs('insights.index', 'insights.category', 'insights.tag')
            ? max(1, $request->integer('page', 1)) : 1;

        if ($pageNumber > 1 && self::stripTracking($canonical) === $request->url()) {
            $canonical = self::stripTracking($canonical);
            $canonical .= '?page='.$pageNumber;
        }

        if ($request->filled('q') && (bool) SiteSettings::get('seo_noindex_search', null, true)) {
            $noindex = true;
        }

        if ($pageNumber > 1 && (bool) SiteSettings::get('seo_noindex_paginated', null, false)) {
            $noindex = true;
        }

        // Use one cleaned URL everywhere, including JSON-LD and breadcrumbs.
        $canonical = self::stripTracking($canonical);

        // Whole-site "Discourage search engines" (settings) — forced on staging.
        if (self::siteNoindex()) {
            $noindex = true;
        }

        $schemaType = $meta?->schema_type ?: $schemaType;
        $articleType = $type === 'article' ? ($meta?->article_type ?: 'BlogPosting') : null;
        $keywords = $meta?->schema_data['keywords'] ?? null
            ?: implode('، ', array_filter([$meta?->focus_keyword, ...((array) $meta?->secondary_keywords)])) ?: null;

        /*
         | articleBody / text: the page's whole authored content as plain
         | text, plus a VideoObject for every embed or video file in it —
         | on every page type, not just articles.
         */
        $body = $bodyHtml === '' ? '' : trim(preg_replace('/[ \t]+/u', ' ', preg_replace('/\s*\n\s*/u', "\n", SeoAnalyzer::plain($bodyHtml)) ?? '') ?? '');
        if (mb_strlen($body) > self::BODY_MAX) {
            $body = rtrim(mb_substr($body, 0, self::BODY_MAX - 1)).'…';
        }
        if ($type === 'article' && $wordCount === null && $body !== '') {
            $wordCount = count(SeoAnalyzer::words($body));
        }
        if ($bodyHtml !== '') {
            $extraNodes = [...$extraNodes, ...SchemaNodes::videos(
                $bodyHtml, $canonical, $title, $description, $image, $publishedAt ?? $modifiedAt,
            )];
        }

        // One trail for both the visible breadcrumb and the BreadcrumbList.
        $breadcrumbs = self::breadcrumbTrail($meta?->breadcrumb_title ?: $title, $canonical, $trail);

        $schema = self::schema(
            $meta?->schema_data['headline'] ?? null ?: $title, $description, $image, $canonical, $type, $schemaType,
            $publishedAt, $modifiedAt, $author,
            articleType: $articleType,
            breadcrumbs: $breadcrumbs,
            extra: array_filter([
                'keywords' => $keywords,
                'articleSection' => $section,
                'wordCount' => $wordCount ?: null,
                'about' => $meta?->schema_data['about'] ?? null,
                'serviceType' => $meta?->schema_data['service_type'] ?? null,
                'areaServed' => $meta?->schema_data['area_served'] ?? null,
                'body' => $body ?: null,
            ]) + $extra,
        );

        if ($extraNodes !== []) {
            $schema['@graph'] = [...$schema['@graph'], ...$extraNodes];
        }

        $custom = self::customSchema($meta?->custom_schema);
        $schemaMode = $meta?->schema_data['mode'] ?? 'auto';

        // Per-page override (Schema Pro "disable on this page" / "replace").
        if ($schemaMode === 'off') {
            $schema = null;
        } elseif ($schemaMode === 'replace' && $custom !== []) {
            $schema['@graph'] = $custom;
        } elseif ($custom !== []) {
            $schema['@graph'] = [...$schema['@graph'], ...$custom];
        }

        $ogImage = self::media($meta?->og_image) ?? $image;

        return [
            'title' => $title,
            'documentTitle' => $documentTitle,
            'description' => $description,
            'image' => $ogImage,
            'canonical' => $canonical,
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
            // Rendered by resources/js/Components/Breadcrumbs.vue.
            'breadcrumbs' => $breadcrumbs === null ? [] : array_map(fn (array $c): array => [
                'name' => $c[0],
                'href' => self::relative($c[1]),
                'url' => $c[1],
            ], $breadcrumbs),
        ];
    }

    /**
     * Category chain as middle crumbs: [[name, absolute url], …].
     *
     * @param  list<PostCategory>  $categories
     * @return list<array{0:string, 1:string}>
     */
    private static function categoryTrail(array $categories): array
    {
        return array_map(
            fn (PostCategory $c): array => [(string) $c->getTranslation('name'), $c->url()],
            $categories,
        );
    }

    /**
     * The cover's Media-library node (ImageObject with title, caption, alt,
     * credit, dimensions) when the file is in the library.
     *
     * @return list<array<string, mixed>>
     */
    private static function coverNodes(?string $path): array
    {
        if ($path === null || $path === '') {
            return [];
        }

        $node = Media::query()->where('path', ltrim($path, '/'))->with('translations')->first()?->schema();
        if ($node === null) {
            return [];
        }
        unset($node['@context']); // inside the page @graph

        return [$node];
    }

    private static function relative(string $url): string
    {
        $path = (string) (parse_url($url, PHP_URL_PATH) ?: '/');
        $query = parse_url($url, PHP_URL_QUERY);

        return $path.($query ? '?'.$query : '');
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
        ?array $breadcrumbs = null,
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
            // On-site search (insights ?q=) — sitelinks search box.
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('insights.index', ['locale' => $locale]).'?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        $breadcrumbs = $breadcrumbs === null ? null : self::breadcrumbList($breadcrumbs, $canonical);

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
            // schema.org `text` lives on CreativeWork (WebPage & subtypes,
            // CreativeWork); Service is not one, so its body is its description.
            'text' => $type !== 'article' && $schemaType !== 'Service' ? ($extra['body'] ?? null) : null,
            'dateModified' => $type !== 'article' && $schemaType !== 'Service' ? $modifiedAt : null,
            'creator' => $extra['creator'] ?? null,
            // Only ever built from approved, real ratings (ServiceRating::summary()).
            'aggregateRating' => $extra['aggregateRating'] ?? null,
            // WebPage-family nodes name their main image explicitly.
            'primaryImageOfPage' => $image && $type !== 'article' && str_ends_with((string) ($schemaType ?? 'WebPage'), 'Page')
                ? ['@type' => 'ImageObject', 'url' => $image]
                : null,
        ]);

        if ($type === 'article' && $articleType !== 'None') {
            $node += array_filter([
                'articleSection' => $extra['articleSection'] ?? null,
                'wordCount' => $extra['wordCount'] ?? null,
                'articleBody' => $extra['body'] ?? null,
            ]);
            $node += array_filter([
                'headline' => mb_substr($title, 0, 110),
                'datePublished' => $publishedAt,
                'dateModified' => $modifiedAt ?? $publishedAt,
                'author' => $author === null
                    ? ['@id' => $organizationId]
                    : ['@type' => 'Person', 'name' => $author],
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
    /**
     * Home › [section] › [middle…] › current page, or null on Home / when
     * breadcrumbs are switched off in SEO settings.
     *
     * @param  list<array{0:string, 1:string}>  $middle  e.g. the category chain
     * @return list<array{0:string, 1:string}>|null
     */
    private static function breadcrumbTrail(string $title, string $canonical, array $middle = []): ?array
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

        // A category page's own entry is the current page, not a middle crumb.
        foreach ($middle as $crumb) {
            if ($crumb[1] !== $canonical) {
                $items[] = $crumb;
            }
        }

        $items[] = [strip_tags($title), $canonical];

        return $items;
    }

    /**
     * @param  list<array{0:string, 1:string}>  $items
     * @return array<string, mixed>
     */
    private static function breadcrumbList(array $items, string $canonical): array
    {
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
