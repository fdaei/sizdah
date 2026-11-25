<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Enums\SectionType;
use App\Models\Industry;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\Service;
use App\Services\Seo\SchemaNodes;
use App\Services\Seo\SeoTitle;
use App\Services\SeoBuilder;
use App\Support\LocaleAlternates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

function seoAuditRequest(string $uri, string $routeName, array $parameters = []): Request
{
    app()->setLocale('fa');
    URL::defaults(['locale' => 'fa']);
    $request = Request::create(url($uri));
    $route = clone Route::getRoutes()->getByName($routeName);
    $route->bind($request);
    foreach ($parameters as $key => $value) {
        $route->setParameter($key, $value);
    }
    $request->setRouteResolver(fn () => $route);
    app()->instance('request', $request);

    return $request;
}

it('keeps pagination in language links without tracking or search parameters', function (): void {
    $request = seoAuditRequest('/fa/insights?page=2&utm_source=test&q=term', 'insights.index');

    expect(LocaleAlternates::for($request))->toBe(['fa' => url('/fa/insights').'?page=2']);
});

it('does not advertise a missing or noindex translation', function (): void {
    config(['locales.supported.en' => config('locales.inactive.en')]);
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'farsi']]);
    $request = seoAuditRequest('/fa/insights/farsi', 'insights.show', ['post' => $post]);

    expect(LocaleAlternates::for($request))->toBe(['fa' => url('/fa/insights/farsi')]);

    $post->setTranslations(['en' => ['title' => 'Article', 'slug' => 'english']]);
    expect(LocaleAlternates::for($request)['en'])->toBe(url('/en/insights/english'));

    $post->saveSeo(['en' => ['robots_index' => false]]);
    expect(LocaleAlternates::for($request))->not->toHaveKey('en');
});

it('uses the real service slug in each language', function (): void {
    config(['locales.supported.en' => config('locales.inactive.en')]);
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now()]);
    $service->setTranslations([
        'fa' => ['title' => 'برندینگ', 'slug' => 'brand-fa'],
        'en' => ['title' => 'Branding', 'slug' => 'branding'],
    ]);
    $request = seoAuditRequest('/fa/services/brand-fa', 'services.show');

    expect(LocaleAlternates::for($request))->toBe([
        'fa' => url('/fa/services/brand-fa'), 'en' => url('/en/services/branding'),
    ]);
});

it('does not advertise untranslated or noindex static pages', function (): void {
    config(['locales.supported.en' => config('locales.inactive.en')]);
    $page = Page::factory()->create(['key' => 'about']);
    $page->setTranslations(['fa' => ['title' => 'درباره ما']]);
    $request = seoAuditRequest('/fa/about', 'about');

    expect(LocaleAlternates::for($request))->toBe(['fa' => url('/fa/about')]);

    $page->setTranslations(['en' => ['title' => 'About']]);
    expect(LocaleAlternates::for($request))->toHaveKey('en');

    $page->saveSeo(['en' => ['robots_index' => false]]);
    expect(LocaleAlternates::for($request))->not->toHaveKey('en');
});

it('gives paginated archives distinct titles and respects custom page templates', function (): void {
    seoAuditRequest('/fa/insights?page=2', 'insights.index');

    expect(SeoTitle::render('%title%', 'مقالات'))->toBe('مقالات — صفحهٔ 2')
        ->and(SeoTitle::render('%title% | %page%', 'مقالات'))->toBe('مقالات | 2');

    seoAuditRequest('/fa/about?page=2', 'about');
    expect(SeoTitle::render('%title%', 'درباره ما'))->toBe('درباره ما');
});

it('preserves pagination on clean self canonicals but ignores page parameters on static pages', function (): void {
    $page = Page::factory()->create(['key' => 'insights']);
    $page->setTranslations(['fa' => ['title' => 'مقالات']]);
    $request = seoAuditRequest('/fa/insights?page=2', 'insights.index');
    $page->saveSeo(['fa' => ['canonical_url' => $request->url().'?utm_source=old']]);
    $seo = SeoBuilder::forPage($page, $request->url());

    expect($seo['canonical'])->toBe($request->url().'?page=2');

    $request = seoAuditRequest('/fa/about?page=2', 'about');
    expect(SeoBuilder::forPage(null, $request->url())['canonical'])->toBe($request->url());
});

it('derives legal descriptions from their own content and archive descriptions from their names', function (): void {
    seoAuditRequest('/fa/privacy-policy', 'legal.privacy');
    $page = Page::factory()->create(['key' => 'privacy-policy']);
    $page->setTranslations(['fa' => ['title' => 'حریم خصوصی', 'content' => '<p>نحوهٔ نگهداری اطلاعات تماس شما.</p>']]);
    expect(SeoBuilder::forPage($page, url('/fa/privacy-policy'))['description'])->toBe('نحوهٔ نگهداری اطلاعات تماس شما.');

    $category = PostCategory::create(['sort_order' => 0]);
    $category->setTranslations(['fa' => ['name' => 'برندینگ', 'slug' => 'branding']]);
    $industry = Industry::create(['sort_order' => 0]);
    $industry->setTranslations(['fa' => ['name' => 'خودرو', 'slug' => 'cars']]);
    expect(SeoBuilder::forCategory($category)['description'])->toContain('برندینگ')
        ->and(SeoBuilder::forIndustry($industry)['description'])->toContain('خودرو')
        ->and(SeoBuilder::forCategory($category)['description'])->not->toContain('seo.archive_descriptions');
});

it('uses the original service image in social metadata and structured data', function (): void {
    seoAuditRequest('/fa/services/branding', 'services.show');
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now(), 'image_path' => 'services/seo-prepared.avif']);
    $service->setTranslations(['fa' => ['title' => 'برندینگ', 'slug' => 'branding']]);
    $seo = SeoBuilder::forService($service, url('/fa/services/branding'));

    expect($seo['image'])->toBe(url('/storage/services/seo-prepared.avif'))
        ->and(collect($seo['schema']['@graph'])->firstWhere('@type', 'Service')['image'])->toBe($seo['image']);
});

it('excludes hidden project sections and items from structured body text', function (): void {
    $project = Project::factory()->published()->create();
    $hidden = $project->sections()->create(['type' => SectionType::Goals, 'is_visible' => false]);
    $hidden->setTranslations(['fa' => ['title' => 'hidden-section']]);
    $visible = $project->sections()->create(['type' => SectionType::Results, 'is_visible' => true]);
    $visible->setTranslations(['fa' => ['title' => 'visible-section', 'description' => 'visible-description']]);
    $item = $visible->items()->create(['is_visible' => false]);
    $item->setTranslations(['fa' => ['title' => 'hidden-item']]);
    $project->load('sections.translations', 'sections.items.translations');

    expect(SchemaNodes::bodyHtml(owner: $project))->toContain('visible-section', 'visible-description')
        ->not->toContain('hidden-section', 'hidden-item');
});

it('does not repeat a featured article or another grid item on the second page', function (): void {
    $page = Page::factory()->create(['key' => 'insights']);
    $page->setTranslations(['fa' => ['title' => 'مقالات']]);
    for ($index = 0; $index < 11; $index++) {
        $post = Post::factory()->published()->create(['is_featured' => $index === 0, 'published_at' => now()->subMinutes($index + 1)]);
        $post->setTranslations(['fa' => ['title' => 'مقاله '.$index, 'slug' => 'audit-article-'.$index]]);
    }
    $pages = [];
    foreach (['/fa/insights', '/fa/insights?page=2'] as $url) {
        $html = $this->get($url)->assertOk()->getContent();
        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        $xpath = new DOMXPath($dom);
        $links = [];
        foreach ($xpath->query('//main//a[contains(@href, "/insights/audit-article-")]/@href') as $link) {
            $links[] = $link->nodeValue;
        }
        $pages[] = array_values(array_unique($links));
    }

    expect($pages[0])->toHaveCount(10)
        ->and($pages[1])->toHaveCount(1)
        ->and(array_intersect($pages[0], $pages[1]))->toBe([]);
});
