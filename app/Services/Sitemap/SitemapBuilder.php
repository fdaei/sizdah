<?php

declare(strict_types=1);

namespace App\Services\Sitemap;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Services\MediaTransformer;
use App\Services\SeoBuilder;
use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Yoast-style XML sitemaps: an index plus one paginated sitemap per content
 * type. Every <url> carries hreflang alternates for each live locale (+
 * x-default), a real lastmod and its images. Items that are unpublished,
 * noindex or "exclude from sitemap" are left out.
 *
 * Output is cached under a version number that is bumped whenever content
 * or SEO metadata is saved (see AppServiceProvider), so it is never stale.
 */
final class SitemapBuilder
{
    public const PER_PAGE = 1000;

    public const TYPES = ['pages', 'services', 'projects', 'posts', 'categories'];

    /** Static route => Page key (for SEO meta + lastmod) and default priority. */
    private const STATIC_ROUTES = [
        'home' => ['home', 1.0],
        'work.index' => ['work', 0.9],
        'services' => ['services', 0.9],
        'about' => ['about', 0.8],
        'insights.index' => ['insights', 0.8],
        'contact' => ['contact', 0.7],
        'legal.privacy' => ['privacy-policy', 0.3],
        'legal.terms' => ['terms', 0.3],
    ];

    /** Persian labels for the admin page. */
    public const LABELS = ['pages' => 'صفحات اصلی', 'services' => 'خدمات', 'projects' => 'پروژه‌ها', 'posts' => 'مقالات', 'categories' => 'دسته‌بندی مقالات'];

    /** @return list<string> content types switched on in the Sitemap admin page */
    public static function enabledTypes(): array
    {
        $types = SiteSettings::get('sitemap_types');

        return is_array($types) ? array_values(array_intersect(self::TYPES, $types)) : self::TYPES;
    }

    public static function imagesEnabled(): bool
    {
        return (bool) SiteSettings::get('sitemap_images', null, true);
    }

    public static function stylesheetEnabled(): bool
    {
        return (bool) SiteSettings::get('sitemap_stylesheet', null, true);
    }

    /**
     * Per-type URL counts for the admin page.
     *
     * @return array<string, array{enabled: bool, count: int, files: list<string>}>
     */
    public function stats(): array
    {
        $enabled = self::enabledTypes();
        $out = [];
        foreach (self::TYPES as $type) {
            $count = count($this->entries($type));
            $out[$type] = [
                'enabled' => in_array($type, $enabled, true),
                'count' => $count,
                'files' => array_map(
                    fn (int $p): string => route('sitemap.type', ['type' => $type, 'page' => $p]),
                    range(1, max(1, (int) ceil($count / self::PER_PAGE))),
                ),
            ];
        }

        return $out;
    }

    /** @return list<string> every URL currently in the sitemap */
    public function allUrls(): array
    {
        if (SeoBuilder::siteNoindex()) {
            return [];
        }

        $urls = [];
        foreach (self::enabledTypes() as $type) {
            array_push($urls, ...array_column($this->entries($type), 'loc'));
        }

        return $urls;
    }

    public static function bust(): void
    {
        Cache::forever('sitemap.version', (int) Cache::get('sitemap.version', 0) + 1);
    }

    /**
     * @return list<array{loc: string, lastmod: ?string}>
     */
    public function index(): array
    {
        if (SeoBuilder::siteNoindex()) {
            return [];
        }

        return $this->cached('index', function (): array {
            $out = [];
            foreach (self::enabledTypes() as $type) {
                $entries = $this->entries($type);
                $pages = (int) ceil(count($entries) / self::PER_PAGE);
                for ($p = 1; $p <= $pages; $p++) {
                    $chunk = array_slice($entries, ($p - 1) * self::PER_PAGE, self::PER_PAGE);
                    $mods = array_filter(array_column($chunk, 'lastmod'));
                    $out[] = [
                        'loc' => route('sitemap.type', ['type' => $type, 'page' => $p]),
                        'lastmod' => $mods === [] ? null : max($mods),
                    ];
                }
            }

            return $out;
        });
    }

    /**
     * @return list<array<string, mixed>>|null null when the page does not exist
     */
    public function page(string $type, int $page): ?array
    {
        if (! in_array($type, self::enabledTypes(), true) || $page < 1 || SeoBuilder::siteNoindex()) {
            return null;
        }

        $chunk = array_slice($this->entries($type), ($page - 1) * self::PER_PAGE, self::PER_PAGE);

        return $chunk === [] && $page > 1 ? null : $chunk;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function entries(string $type): array
    {
        return $this->cached("type.{$type}", fn (): array => match ($type) {
            'pages' => $this->pages(),
            'services' => $this->services(),
            'projects' => $this->projects(),
            'posts' => $this->posts(),
            'categories' => $this->categories(),
        });
    }

    private function pages(): array
    {
        $pages = Page::query()->with('seoMeta')->whereIn('key', array_column(self::STATIC_ROUTES, 0))->get()->keyBy('key');
        $out = [];

        foreach (self::STATIC_ROUTES as $route => [$key, $priority]) {
            $page = $pages->get($key);
            if ($page !== null && method_exists($page, 'isPublished') && ! $page->isPublished()) {
                continue;
            }
            $hrefs = $this->locales(fn (string $l): string => route($route, ['locale' => $l]));
            array_push($out, ...$this->perLocale($hrefs, $page, $page?->updated_at?->toAtomString(), $priority, 'weekly', []));
        }

        return $out;
    }

    private function services(): array
    {
        $out = [];
        Service::query()->forDisplay()->with('seoMeta')->get()->each(function (Service $s) use (&$out): void {
            $hrefs = $this->locales(function (string $l) use ($s): ?string {
                $slug = (string) $s->getTranslation('slug', $l, false);

                return $slug === '' ? null : route('services.show', ['locale' => $l, 'service' => $slug]);
            });
            array_push($out, ...$this->perLocale($hrefs, $s, $s->updated_at?->toAtomString(), 0.6, 'monthly', []));
        });

        return $out;
    }

    private function projects(): array
    {
        $out = [];
        Project::query()->published()->withTranslations()->with('seoMeta')->get()->each(function (Project $p) use (&$out): void {
            $hrefs = $this->locales(fn (string $l): ?string => $p->getTranslation('slug', $l, false) ? $p->url($l) : null);
            $images = $p->cover_path ? [self::absolute(MediaTransformer::url($p->cover_path))] : [];
            array_push($out, ...$this->perLocale($hrefs, $p, $p->updated_at?->toAtomString(), 0.7, 'monthly', $images));
        });

        return $out;
    }

    private function categories(): array
    {
        $out = [];
        PostCategory::query()->ordered()->withTranslations()->with('seoMeta')->has('posts')->get()->each(function (PostCategory $c) use (&$out): void {
            $hrefs = $this->locales(fn (string $l): ?string => $c->getTranslation('slug', $l, false) ? $c->url($l) : null);
            array_push($out, ...$this->perLocale($hrefs, $c, $c->updated_at?->toAtomString(), 0.5, 'weekly', []));
        });

        return $out;
    }

    private function posts(): array
    {
        $out = [];
        Post::query()->published()->withTranslations()->with('seoMeta')->get()->each(function (Post $p) use (&$out): void {
            $hrefs = $this->locales(fn (string $l): ?string => $p->getTranslation('slug', $l, false) ? $p->url($l) : null);
            $images = array_values(array_unique(array_filter([
                $p->cover_path ? self::absolute(MediaTransformer::url($p->cover_path)) : null,
                ...self::contentImages((string) $p->getTranslation('content')),
            ])));
            array_push($out, ...$this->perLocale($hrefs, $p, $p->updated_at?->toAtomString(), 0.6, 'monthly', $images));
        });

        return $out;
    }

    /**
     * One <url> per locale that has a URL and is indexable there; each lists
     * all live locales as alternates (+ x-default).
     *
     * @param  array<string, string>  $hrefs  locale => absolute URL
     * @param  list<string>  $images
     * @return list<array<string, mixed>>
     */
    private function perLocale(array $hrefs, ?Model $model, ?string $lastmod, float $priority, string $freq, array $images): array
    {
        $alternates = [];
        foreach ($hrefs as $locale => $href) {
            $alternates[] = ['hreflang' => config("locales.supported.{$locale}.html_lang"), 'href' => $href];
        }
        $default = $hrefs[config('locales.default')] ?? reset($hrefs);
        if ($default) {
            $alternates[] = ['hreflang' => 'x-default', 'href' => $default];
        }

        $out = [];
        foreach ($hrefs as $locale => $href) {
            $meta = $model !== null && method_exists($model, 'seoFor') ? $model->seoFor($locale) : null;
            if (! self::indexable($meta)) {
                continue;
            }
            $out[] = [
                'loc' => $href,
                'lastmod' => $lastmod,
                'priority' => $meta?->sitemap_priority ?: $priority,
                'changefreq' => $meta?->sitemap_changefreq ?: $freq,
                'alternates' => $alternates,
                'images' => self::imagesEnabled() ? $images : [],
            ];
        }

        return $out;
    }

    private static function indexable(?SeoMeta $meta): bool
    {
        return $meta === null || ($meta->robots_index !== false && ! $meta->exclude_from_sitemap && blank($meta->canonical_url));
    }

    /**
     * @param  callable(string): ?string  $url
     * @return array<string, string>
     */
    private function locales(callable $url): array
    {
        $out = [];
        foreach (array_keys(config('locales.supported')) as $locale) {
            $href = $url($locale);
            if ($href !== null) {
                $out[$locale] = $href;
            }
        }

        return $out;
    }

    private static function absolute(?string $src): ?string
    {
        if ($src === null || $src === '') {
            return null;
        }

        return str_starts_with($src, 'http') ? $src : url('/'.ltrim($src, '/'));
    }

    /** @return list<string> absolute image URLs from body HTML */
    private static function contentImages(string $html): array
    {
        preg_match_all('/<img\b[^>]*\bsrc\s*=\s*["\']([^"\']+)["\']/i', $html, $m);

        return array_values(array_filter(array_map(
            fn (string $src): ?string => str_starts_with($src, 'http') ? $src : (str_starts_with($src, '/') ? url($src) : null),
            $m[1],
        )));
    }

    private function cached(string $key, \Closure $build): array
    {
        $version = (int) Cache::get('sitemap.version', 0);

        return Cache::remember("sitemap.v{$version}.{$key}", now()->addDay(), $build);
    }
}
