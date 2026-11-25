<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Services\ContentTransformer;
use App\Services\SeoBuilder;
use App\View\PublicPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Blog listing (Figma 1353:7935) and article detail (1352:7391).
 */
final class PostController extends Controller
{
    private const PER_PAGE = 9;

    public function index(Request $request): PublicPage|RedirectResponse
    {
        // Legacy ?category= filter URLs → the category's own indexable page.
        $legacy = $request->string('category')->toString();
        if ($legacy !== '') {
            $category = (new PostCategory)->resolveRouteBinding($legacy);
            if ($category !== null) {
                return redirect()->to($category->url().self::query($request, ['category']), 301);
            }
        }

        return $this->listing($request, null);
    }

    /**
     * Category archive — same listing, scoped, with the category's own
     * heading and SEO meta (CollectionPage).
     */
    public function category(Request $request, string $locale, PostCategory $category): PublicPage
    {
        return $this->listing($request, $category);
    }

    public function tag(Request $request, string $locale, PostTag $tag): PublicPage
    {
        return $this->listing($request, null, $tag);
    }

    private static function query(Request $request, array $without): string
    {
        $q = http_build_query($request->except($without));

        return $q === '' ? '' : '?'.$q;
    }

    private function listing(Request $request, ?PostCategory $category, ?PostTag $tag = null): PublicPage
    {
        $page = Page::query()
            ->key('insights')
            ->published()
            ->withContent()
            ->first();

        $categoryFilter = $category === null ? '' : (string) $category->getTranslation('slug');
        $search = $request->string('q')->toString();

        $query = Post::query()->forListing();

        if ($categoryFilter !== '') {
            $query->inCategory($categoryFilter);
        }

        if ($tag !== null) {
            $query->whereHas('tags', fn ($q) => $q->where('post_tags.id', $tag->id));
        }

        if ($search !== '') {
            $locale = app()->getLocale();

            $query->whereHas('translations', function ($q) use ($locale, $search): void {
                $q->where('locale', $locale)
                    ->where(function ($inner) use ($search): void {
                        $inner->where('title', 'like', "%{$search}%")
                            ->orWhere('excerpt', 'like', "%{$search}%");
                    });
            });
        }

        // The featured card (Figma 1419:9265) is only shown on an unfiltered
        // first page — otherwise it would duplicate a grid result.
        $featured = null;

        if ($categoryFilter === '' && $tag === null && $search === '') {
            $featuredPost = Post::query()->forListing()->featured()->first();

            if ($featuredPost !== null) {
                // Exclude it on every page so page 2 cannot repeat page 1's
                // last grid item when the featured card disappears.
                $query->whereKeyNot($featuredPost->getKey());
                $featured = $request->integer('page', 1) === 1 ? $featuredPost : null;
            }
        }

        $posts = $query->paginate(self::PER_PAGE)->withQueryString();

        return PublicPage::render('Insights/Index', [
            'heading' => $category === null && $tag === null ? [
                'eyebrow' => (string) $page?->getTranslation('subtitle'),
                'title' => (string) $page?->getTranslation('title'),
                'description' => (string) $page?->getTranslation('description'),
            ] : [
                'eyebrow' => (string) $page?->getTranslation('title'),
                'title' => (string) ($category ?? $tag)->getTranslation('name'),
                'description' => (string) ($category?->getTranslation('description') ?? ''),
            ],

            'featured' => $featured === null
                ? null
                : ContentTransformer::postSummary($featured),

            'posts' => [
                'data' => collect($posts->items())
                    ->map(fn (Post $p): array => ContentTransformer::postSummary($p))
                    ->all(),
                'currentPage' => $posts->currentPage(),
                'lastPage' => $posts->lastPage(),
                'total' => $posts->total(),
                'nextPageUrl' => $posts->nextPageUrl(),
                'prevPageUrl' => $posts->previousPageUrl(),
            ],

            'categories' => Cache::remember(
                'insights.categories.v2.'.app()->getLocale(),
                now()->addHour(),
                // Flat list; `parent` (a slug) lets the page show the
                // subcategories of the selected branch.
                fn (): array => PostCategory::query()
                    ->ordered()
                    ->withTranslations()
                    ->with('parent.translations')
                    ->get()
                    ->map(fn (PostCategory $c): array => [
                        'slug' => (string) $c->getTranslation('slug'),
                        'name' => (string) $c->getTranslation('name'),
                        'parent' => $c->parent === null ? null : (string) $c->parent->getTranslation('slug'),
                    ])
                    ->filter(fn (array $c): bool => $c['slug'] !== '')
                    ->values()
                    ->all(),
            ),

            'filters' => [
                'category' => $categoryFilter ?: null,
                'q' => $search ?: null,
            ],

            'sections' => $page === null
                ? []
                : ContentTransformer::sectionMap($page->sections),

            // Generic page-builder blocks (rich text, gallery, features, CTA), in order.
            'blocks' => $page === null ? [] : ContentTransformer::blocks($page->sections),

            'seo' => $tag !== null ? SeoBuilder::forTag($tag) : ($category === null
                ? SeoBuilder::forPage($page, $request->url())
                : SeoBuilder::forCategory($category)),
        ]);
    }

    public function show(string $locale, Post $post): PublicPage
    {
        $post->loadMissing([
            'category.translations',
            'tags.translations',
            'author',
        ]);

        // The article runs the lead-magnet strip mid-body (Figma 604:1464).
        // It has no page of its own, so it reuses the one authored on Home.
        $home = Page::query()->key('home')->published()->withContent()->first();
        $homeSections = $home === null
            ? []
            : ContentTransformer::sectionMap($home->sections);

        return PublicPage::render('Insights/Show', [
            'post' => ContentTransformer::postDetail($post),

            'leadMagnet' => $homeSections['lead_magnet'] ?? null,
            'finalCta' => $homeSections['final_cta'] ?? null,

            'seo' => SeoBuilder::forPost($post),
        ]);
    }
}
