<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Post;
use App\Models\Setting;
use App\Services\Sitemap\SitemapBuilder;
use App\Support\SiteSettings;

function sitemapPost(string $slug, array $seo = [], PublicationStatus $status = PublicationStatus::Published): Post
{
    $post = Post::factory()->create(['status' => $status, 'published_at' => $status === PublicationStatus::Published ? now()->subDay() : null, 'cover_path' => 'posts/c.webp']);
    $post->setTranslations(['fa' => ['title' => $slug, 'slug' => $slug, 'content' => '<p><img src="/storage/content/in-body.webp" alt="x"></p>']]);
    if ($seo !== []) {
        $post->saveSeo(['fa' => $seo]);
    }

    return $post;
}

it('serves an index of per-type sitemaps with lastmod', function (): void {
    sitemapPost('یک');

    $xml = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')->getContent();

    expect($xml)->toContain('<sitemapindex')
        ->toContain(url('/sitemap-pages-1.xml'))
        ->toContain(url('/sitemap-posts-1.xml'))
        ->toContain('<lastmod>');
});

it('lists published posts with alternates, x-default and absolute images', function (): void {
    sitemapPost('منتشر');
    sitemapPost('پیش-نویس', status: PublicationStatus::Draft);

    $xml = $this->get('/sitemap-posts-1.xml')->assertOk()->getContent();

    // Sitemap URLs are percent-encoded, as the protocol requires.
    expect($xml)->toContain('/fa/insights/'.rawurlencode('منتشر'))
        ->not->toContain(rawurlencode('پیش-نویس'))
        ->toContain('hreflang="fa-IR"')
        ->toContain('hreflang="x-default"')
        ->toContain('<image:loc>'.url('/storage/posts/c.webp').'</image:loc>')
        ->toContain('<image:loc>'.url('/storage/content/in-body.webp').'</image:loc>');
    expect(simplexml_load_string($xml))->not->toBeFalse();
});

it('leaves out noindex, excluded and canonicalised items, and honours priority overrides', function (): void {
    sitemapPost('بی-ایندکس', ['robots_index' => false]);
    sitemapPost('حذف-شده', ['exclude_from_sitemap' => true]);
    sitemapPost('کنونیکال', ['canonical_url' => 'https://example.com/x']);
    sitemapPost('مهم', ['sitemap_priority' => 0.9, 'sitemap_changefreq' => 'daily']);

    $xml = $this->get('/sitemap-posts-1.xml')->getContent();

    expect($xml)->not->toContain(rawurlencode('بی-ایندکس'))->not->toContain(rawurlencode('حذف-شده'))->not->toContain(rawurlencode('کنونیکال'))
        ->toContain('<priority>0.9</priority>')->toContain('<changefreq>daily</changefreq>');
});

it('refreshes the cache when content changes', function (): void {
    $this->get('/sitemap-posts-1.xml');
    sitemapPost('تازه');

    expect($this->get('/sitemap-posts-1.xml')->getContent())->toContain('/fa/insights/'.rawurlencode('تازه'));
});

it('paginates at 1000 URLs and 404s pages past the end', function (): void {
    expect(SitemapBuilder::PER_PAGE)->toBe(1000);
    $this->get('/sitemap-posts-2.xml')->assertNotFound();
    $this->get('/sitemap-nope-1.xml')->assertStatus(302); // not a sitemap route: bare-path locale redirect
});

it('empties the sitemap index when the whole site is noindex', function (): void {
    sitemapPost('هر');
    Setting::updateOrCreate(['key' => 'seo_noindex_site'], ['group' => 'seo', 'value' => ['value' => true], 'is_translatable' => false]);
    SiteSettings::flush();

    expect($this->get('/sitemap.xml')->getContent())->not->toContain('<sitemap>');
});

it('outputs absolute og:image URLs', function (): void {
    config(['inertia.ssr.enabled' => false]);
    sitemapPost('تصویر');

    expect($this->get('/fa/insights/تصویر')->getContent())
        ->toContain('<meta property="og:image" content="'.url('/storage/posts/c.webp').'"');
});
