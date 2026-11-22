<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\SeoMeta;

/*
 | Phase A: per-item seo_meta drives the server-rendered head, with the
 | legacy translation columns as fallback.
 */

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function aboutWithMeta(array $meta): Page
{
    $page = seoPage('about', 'درباره ما');
    $page->saveSeo(['fa' => $meta]);

    return $page;
}

it('prefers seo_meta title (template) and description over legacy columns', function (): void {
    aboutWithMeta(['title' => 'درباره سیزده %sep% %sitename%', 'description' => 'توضیح جدید']);

    $html = $this->get('/fa/about')->getContent();

    expect($html)
        ->toContain('<title inertia>درباره سیزده — ')
        ->toContain('content="توضیح جدید" inertia="description"');
});

it('renders noindex, nofollow and advanced robots flags', function (): void {
    aboutWithMeta(['robots_index' => false, 'robots_follow' => false, 'robots_advanced' => ['flags' => ['noarchive']]]);

    expect($this->get('/fa/about')->getContent())
        ->toContain('<meta name="robots" content="noindex, nofollow, noarchive" inertia="robots">');
});

it('uses a canonical override and strips tracking params from self-canonicals', function (): void {
    seoPage('contact', 'تماس');
    expect($this->get('/fa/contact?utm_source=x&gclid=y')->getContent())
        ->toContain('<link rel="canonical" href="'.url('/fa/contact').'" inertia="canonical">');

    aboutWithMeta(['canonical_url' => 'https://example.com/elsewhere']);
    expect($this->get('/fa/about')->getContent())
        ->toContain('<link rel="canonical" href="https://example.com/elsewhere" inertia="canonical">');
});

it('uses the cleaned canonical consistently in metadata and structured data', function (): void {
    aboutWithMeta(['canonical_url' => url('/fa/about').'?utm_source=test&ref=kept']);
    $html = $this->get('/fa/about')->getContent();
    $expected = url('/fa/about').'?ref=kept';
    $graph = jsonLd($html)['@graph'];

    expect($html)->toContain('href="'.e($expected).'" inertia="canonical"')
        ->not->toContain('utm_source=test')
        ->and(collect($graph)->firstWhere('@type', 'AboutPage')['url'])->toBe($expected);
});

it('outputs social overrides and merges valid custom JSON-LD into the graph', function (): void {
    aboutWithMeta([
        'og_title' => 'عنوان فیسبوک',
        'twitter_title' => 'عنوان ایکس',
        'twitter_card' => 'summary',
        'schema_type' => 'ProfilePage',
        'custom_schema' => '{"@context":"https://schema.org","@type":"Event","name":"وبینار"}',
    ]);

    $html = $this->get('/fa/about')->getContent();
    $graph = jsonLd($html)['@graph'];

    expect($html)
        ->toContain('content="عنوان فیسبوک" inertia="og:title"')
        ->toContain('content="عنوان ایکس" inertia="twitter:title"')
        ->toContain('content="summary" inertia="twitter:card"');
    expect(array_column($graph, '@type'))->toContain('ProfilePage', 'Event');
    expect(collect($graph)->firstWhere('@type', 'Event'))->not->toHaveKey('@context');
});

it('ignores invalid custom JSON-LD instead of breaking the page', function (): void {
    aboutWithMeta(['custom_schema' => '{not json']);

    expect(jsonLd($this->get('/fa/about')->getContent())['@graph'])->not->toBeEmpty();
});

it('keeps one seo_meta row per locale and deletes it with the model', function (): void {
    $page = aboutWithMeta(['title' => 'a']);
    $page->saveSeo(['fa' => ['title' => 'b'], 'xx' => ['title' => 'unsupported']]);

    expect(SeoMeta::where('seoable_id', $page->id)->count())->toBe(1)
        ->and($page->seoFor('fa')->title)->toBe('b');

    $page->delete();
    expect(SeoMeta::count())->toBe(0);
});
