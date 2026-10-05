<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Route;

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
    public static function forPage(?Page $page, string $canonical, ?string $image = null): array
    {
        return self::assemble(
            title: $page?->getTranslation('seo_title') ?: $page?->getTranslation('title'),
            description: $page?->getTranslation('seo_description') ?: $page?->getTranslation('description'),
            image: $image,
            canonical: $canonical,
            type: 'website',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function forProject(Project $project): array
    {
        return self::assemble(
            title: $project->getTranslation('seo_title') ?: $project->getTranslation('title'),
            description: $project->getTranslation('seo_description') ?: $project->getTranslation('excerpt'),
            image: $project->cover_path === null
                ? null
                : MediaTransformer::url($project->cover_path),
            canonical: $project->url(),
            type: 'website',
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
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function forPost(Post $post): array
    {
        return self::assemble(
            title: $post->getTranslation('seo_title') ?: $post->getTranslation('title'),
            description: $post->getTranslation('seo_description') ?: $post->getTranslation('excerpt'),
            image: $post->cover_path === null
                ? null
                : MediaTransformer::url($post->cover_path),
            canonical: $post->url(),
            type: 'article',
            publishedAt: $post->published_at?->toIso8601String(),
            modifiedAt: $post->updated_at?->toIso8601String(),
            author: $post->author?->name,
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
    ): array {
        $locale = app()->getLocale();
        $request = request();

        $title = $title ?: SiteSettings::get('seo_default_title', $locale, 'Sizdah');
        $description = $description ?: SiteSettings::get('seo_default_description', $locale, '');
        $image = $image ?: SiteSettings::forFrontend($locale)['seo']['defaultImage'];

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

        if ($request->filled('q')) {
            $noindex = true;
        }

        return [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'canonical' => $canonical,
            'type' => $type,
            'publishedAt' => $publishedAt,
            'modifiedAt' => $modifiedAt,
            'author' => $author,
            'noindex' => $noindex,
            'schema' => self::schema(
                $title, $description, $image, $canonical, $type, $schemaType,
                $publishedAt, $modifiedAt, $author,
            ),
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
    ): array {
        $locale = app()->getLocale();
        $settings = SiteSettings::forFrontend($locale);
        $root = rtrim(url('/'), '/');
        $home = route('home', ['locale' => $locale]);
        $inLanguage = config("locales.supported.{$locale}.html_lang");

        $organizationId = "{$root}/#organization";
        $websiteId = "{$root}/#website";

        $organization = array_filter([
            '@type' => 'Organization',
            '@id' => $organizationId,
            'name' => $settings['seo']['organizationName'],
            'url' => $home,
            'logo' => "{$root}/icon-512.png",
            'email' => $settings['contact']['email'] ?: null,
            'telephone' => $settings['contact']['phone'] ?: null,
            'sameAs' => array_values(array_filter(array_column($settings['socialLinks'], 'url'))) ?: null,
        ]);

        $website = [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'name' => $settings['siteName'],
            'url' => $home,
            'inLanguage' => $inLanguage,
            'publisher' => ['@id' => $organizationId],
        ];

        $breadcrumbs = self::breadcrumbs($title, $canonical);

        $node = array_filter([
            '@type' => $schemaType ?? ($type === 'article' ? 'Article' : 'WebPage'),
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

        if ($type === 'article') {
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

        if ($name === null || $name === 'home') {
            return null;
        }

        $items = [[__('seo.breadcrumbs.home'), route('home')]];

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
}
