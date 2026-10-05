<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;

/*
 | Regression tests for the technical SEO audit of 2026-10-04
 | (Sahra_Technical_SEO_Developer_Audit). SSR is switched off on purpose:
 | everything asserted here must be in the initial HTML even when the Node
 | SSR process is down or JavaScript never runs.
 */

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function seoPage(string $key, string $title = 'عنوان صفحه'): Page
{
    $page = Page::factory()->create(['key' => $key]);
    $page->setTranslations(['fa' => [
        'title' => $title,
        'seo_title' => $title,
        'seo_description' => 'توضیحات متا برای '.$title,
    ]]);

    return $page;
}

/**
 * @return array<string, mixed>
 */
function jsonLd(string $html): array
{
    preg_match('#<script type="application/ld\+json" inertia="schema">(.*?)</script>#s', $html, $m);

    return json_decode($m[1] ?? 'null', true) ?? [];
}

it('renders title, description, canonical, robots and hreflang in the initial HTML', function (): void {
    seoPage('about', 'درباره ما');

    $html = $this->get('/fa/about')->assertOk()->getContent();
    $url = url('/fa/about');

    expect($html)
        ->toContain('<html lang="fa-IR" dir="rtl"')
        ->toContain('<title inertia>درباره ما — '.config('app.name').'</title>')
        ->toContain('<meta name="description" content="توضیحات متا برای درباره ما" inertia="description">')
        ->toContain('<link rel="canonical" href="'.$url.'" inertia="canonical">')
        ->toContain('<meta name="robots" content="index, follow" inertia="robots">')
        ->toContain('<link rel="alternate" hreflang="fa-IR" href="'.$url.'" inertia="hreflang-fa">')
        ->toContain('<link rel="alternate" hreflang="x-default" href="'.$url.'" inertia="hreflang-x-default">')
        ->toContain('<meta property="og:url" content="'.$url.'" inertia="og:url">');

    expect(substr_count($html, '<title'))->toBe(1)
        ->and(substr_count($html, 'rel="canonical"'))->toBe(1);
});

it('emits Organization, WebSite and BreadcrumbList JSON-LD', function (): void {
    seoPage('about', 'درباره ما');

    $types = array_column(jsonLd($this->get('/fa/about')->getContent())['@graph'], '@type');

    expect($types)->toContain('Organization', 'WebSite', 'WebPage', 'BreadcrumbList');
});

it('omits BreadcrumbList on the home page', function (): void {
    seoPage('home', 'خانه');

    $types = array_column(jsonLd($this->get('/fa')->getContent())['@graph'], '@type');

    expect($types)->toContain('Organization', 'WebSite')->not->toContain('BreadcrumbList');
});

it('noindexes on-site search results and keeps pagination self-canonical', function (): void {
    seoPage('insights', 'مقالات');

    expect($this->get('/fa/insights?q=test')->getContent())
        ->toContain('<meta name="robots" content="noindex, follow" inertia="robots">')
        ->toContain('<link rel="canonical" href="'.url('/fa/insights').'" inertia="canonical">');

    expect($this->get('/fa/insights?page=2')->getContent())
        ->toContain('<link rel="canonical" href="'.url('/fa/insights').'?page=2" inertia="canonical">');
});

it('returns a real 404 with noindex for unknown URLs and missing slugs', function (string $path): void {
    $this->get($path)
        ->assertNotFound()
        ->assertSee('<meta name="robots" content="noindex, follow" inertia="robots">', false);
})->with(['/fa/does-not-exist', '/fa/work/does-not-exist', '/fa/insights/does-not-exist', '/fa/services/does-not-exist']);

it('shares the full prop set with error pages so SSR can render them', function (): void {
    $props = $this->get('/fa/work/does-not-exist', ['X-Inertia' => 'true'])->json('props');

    expect($props)->toHaveKeys(['locale', 'flash', 'ziggy', 'navigation', 'settings']);
});

it('serves service detail pages', function (): void {
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now(), 'sort_order' => 1]);
    $service->setTranslations(['fa' => ['title' => 'برندینگ', 'slug' => 'برندینگ', 'description' => 'توضیح']]);

    $html = $this->get('/fa/services/'.rawurlencode('برندینگ'))->assertOk()->getContent();

    expect(array_column(jsonLd($html)['@graph'], '@type'))->toContain('Service', 'BreadcrumbList');
});

it('serves robots.txt with an absolute sitemap URL', function (): void {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('Disallow: /admin', false)
        ->assertSee('Sitemap: '.url('/sitemap.xml'), false);
});

it('lists only canonical locale URLs in the sitemap, with x-default', function (): void {
    Project::factory()->published()->create()->setTranslations(['fa' => ['title' => 'پروژه', 'slug' => 'پروژه']]);

    $this->get('/sitemap.xml')->assertOk()->assertSee(url('/sitemap-fa.xml'), false);

    $xml = $this->get('/sitemap-fa.xml')->assertOk()->getContent();

    expect($xml)
        ->toContain('<loc>'.url('/fa/about').'</loc>')
        ->toContain('hreflang="x-default"');

    preg_match_all('#<loc>([^<]+)</loc>#', $xml, $locs);

    foreach ($locs[1] as $loc) {
        expect($loc)->toStartWith(url('/fa'));
    }
});

describe('canonical host redirect', function (): void {
    beforeEach(function (): void {
        config(['app.canonical_redirect' => true, 'app.url' => 'https://example.com']);
    });

    it('sends www, http and trailing-slash variants to the canonical URL in one 301', function (string $from): void {
        $this->get($from)->assertStatus(301)->assertRedirect('https://example.com/fa/about?x=1');
    })->with([
        'http://www.example.com/fa/about?x=1',
        'https://www.example.com/fa/about?x=1',
        'http://example.com/fa/about?x=1',
        'https://example.com/fa/about/?x=1',
        'http://www.example.com/fa/about/?x=1',
    ]);

    it('lets canonical requests through, including behind a TLS proxy', function (): void {
        seoPage('about');

        $this->get('https://example.com/fa/about')->assertOk();
        $this->get('http://example.com/fa/about', ['X-Forwarded-Proto' => 'https'])->assertOk();
    });

    it('does not redirect the health check', function (): void {
        $this->get('http://10.0.0.5/up')->assertOk();
    });
});
