<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Enums\SectionType;
use App\Models\Post;
use App\Models\Page;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Services\Sitemap\SitemapBuilder;
use App\Support\SiteSettings;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

it('omits missing legal pages and lists images from published legal content', function (): void {
    $empty = $this->get('/sitemap-pages-1.xml')->getContent();
    expect($empty)->not->toContain('/fa/privacy-policy')->not->toContain('/fa/terms');

    $page = Page::factory()->create(['key' => 'privacy-policy']);
    $page->setTranslations(['fa' => [
        'title' => 'حریم خصوصی',
        'content' => '<p><img src="/storage/legal/policy.webp" alt="policy"></p>',
    ]]);
    $xml = $this->get('/sitemap-pages-1.xml')->getContent();

    expect($xml)->toContain('/fa/privacy-policy')->toContain(url('/storage/legal/policy.webp'))
        ->not->toContain('/fa/terms');
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

it('uses the matching language content and decodes image URLs for each locale', function (): void {
    config(['locales.supported.en' => config('locales.inactive.en')]);
    $post = Post::factory()->published()->create();
    $post->setTranslations([
        'fa' => ['title' => 'فارسی', 'slug' => 'farsi', 'content' => '<img src="/fa-image.webp?a=1&amp;b=2"><img data-src="/not-rendered.webp">'],
        'en' => ['title' => 'English', 'slug' => 'english', 'content' => '<img src="https://cdn.example.com/en.webp">'],
    ]);

    $xml = simplexml_load_string($this->get('/sitemap-posts-1.xml')->getContent());
    $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    $xml->registerXPathNamespace('img', 'http://www.google.com/schemas/sitemap-image/1.1');
    $fa = $xml->xpath('//s:url[contains(s:loc, "/fa/insights/farsi")]/img:image/img:loc');
    $en = $xml->xpath('//s:url[contains(s:loc, "/en/insights/english")]/img:image/img:loc');

    expect(array_map('strval', $fa))->toBe([url('/fa-image.webp').'?a=1&b=2'])
        ->and(array_map('strval', $en))->toBe(['https://cdn.example.com/en.webp']);
});

it('includes visible project gallery images and refreshes them after changes', function (): void {
    $project = Project::factory()->published()->create(['banner_path' => 'projects/banner.webp']);
    $project->setTranslations(['fa' => ['title' => 'پروژه', 'slug' => 'gallery']]);
    $image = $project->images()->create(['path' => 'projects/first.webp', 'sort_order' => 0]);
    expect($this->get('/sitemap-projects-1.xml')->getContent())
        ->toContain(url('/storage/projects/banner.webp'))
        ->toContain(url('/storage/projects/first.webp'));

    $image->update(['path' => 'projects/updated.webp']);
    expect($this->get('/sitemap-projects-1.xml')->getContent())
        ->toContain(url('/storage/projects/updated.webp'))
        ->not->toContain(url('/storage/projects/first.webp'));
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

it('applies the category noindex setting to the page and sitemap', function (): void {
    config(['inertia.ssr.enabled' => false]);
    $category = PostCategory::create(['sort_order' => 0]);
    $category->setTranslations(['fa' => ['name' => 'سئو', 'slug' => 'seo']]);
    Post::factory()->published()->create(['post_category_id' => $category->id]);

    expect($this->get('/sitemap-categories-1.xml')->getContent())->toContain('/fa/insights/category/seo');
    Setting::updateOrCreate(['key' => 'seo_noindex_post_category'], ['group' => 'seo', 'value' => ['value' => true], 'is_translatable' => false]);
    SiteSettings::flush();

    expect($this->get('/fa/insights/category/seo')->getContent())->toContain('content="noindex, follow" inertia="robots"')
        ->and($this->get('/sitemap.xml')->getContent())->not->toContain('sitemap-categories-1.xml');
});

it('does not list categories containing only draft posts', function (): void {
    $category = PostCategory::create(['sort_order' => 0]);
    $category->setTranslations(['fa' => ['name' => 'پیش نویس', 'slug' => 'drafts']]);
    Post::factory()->create(['post_category_id' => $category->id]);

    expect($this->get('/sitemap-categories-1.xml')->getContent())->not->toContain('/fa/insights/category/drafts');
});

it('includes parent categories with published posts in their descendants', function (): void {
    $parent = PostCategory::create(['sort_order' => 0]);
    $parent->setTranslations(['fa' => ['name' => 'والد', 'slug' => 'parent']]);
    $child = PostCategory::create(['sort_order' => 0, 'parent_id' => $parent->id]);
    $child->setTranslations(['fa' => ['name' => 'فرزند', 'slug' => 'child']]);
    Post::factory()->published()->create(['post_category_id' => $child->id]);

    expect($this->get('/sitemap-categories-1.xml')->getContent())
        ->toContain('/fa/insights/category/parent')
        ->toContain('/fa/insights/category/child');
});

it('keeps a self canonical in the sitemap', function (): void {
    sitemapPost('self', ['canonical_url' => url('/fa/insights/self')]);

    expect($this->get('/sitemap-posts-1.xml')->getContent())->toContain('/fa/insights/self');
});

it('does not advertise a noindex translation as a sitemap alternate', function (): void {
    config(['locales.supported.en' => config('locales.inactive.en')]);
    $post = sitemapPost('persian');
    $post->setTranslations(['en' => ['title' => 'English', 'slug' => 'english']]);
    $post->saveSeo(['en' => ['robots_index' => false]]);

    $xml = $this->get('/sitemap-posts-1.xml')->getContent();
    expect($xml)->toContain('/fa/insights/persian')
        ->not->toContain('/en/insights/english')
        ->not->toContain('hreflang="en"');
});

it('preserves a zero sitemap priority', function (): void {
    sitemapPost('zero-priority', ['sitemap_priority' => 0]);

    expect($this->get('/sitemap-posts-1.xml')->getContent())->toContain('<priority>0.0</priority>');
});

it('refreshes the cache when content changes', function (): void {
    $this->get('/sitemap-posts-1.xml');
    sitemapPost('تازه');

    expect($this->get('/sitemap-posts-1.xml')->getContent())->toContain('/fa/insights/'.rawurlencode('تازه'));
});

it('updates lastmod when translated content or seo changes', function (): void {
    $this->travelTo(now()->subDays(3)->startOfSecond());
    $post = sitemapPost('freshness');
    $this->get('/sitemap-posts-1.xml');

    $this->travel(1)->days();
    $post->setTranslations(['fa' => ['title' => 'عنوان تازه']]);
    $expected = now()->toAtomString();
    expect($this->get('/sitemap-posts-1.xml')->getContent())->toContain('<lastmod>'.$expected.'</lastmod>');

    $this->travel(1)->days();
    $post->saveSeo(['fa' => ['description' => 'توضیحات تازه']]);
    expect($this->get('/sitemap-posts-1.xml')->getContent())->toContain('<lastmod>'.now()->toAtomString().'</lastmod>');
});

it('updates page lastmod after an owned section is edited or deleted', function (): void {
    $this->travelTo(now()->subDays(3)->startOfSecond());
    $page = Page::factory()->create(['key' => 'about']);
    $page->setTranslations(['fa' => ['title' => 'درباره ما']]);
    $section = $page->sections()->create(['type' => SectionType::Hero, 'sort_order' => 0, 'is_visible' => true]);
    $section->setTranslations(['fa' => ['title' => 'عنوان']]);
    $this->get('/sitemap-pages-1.xml');

    $this->travel(1)->days();
    $section->setTranslations(['fa' => ['title' => 'عنوان تازه']]);
    expect($this->get('/sitemap-pages-1.xml')->getContent())->toContain('<lastmod>'.now()->toAtomString().'</lastmod>');

    $this->travel(1)->days();
    $section->delete();
    expect($this->get('/sitemap-pages-1.xml')->getContent())->toContain('<lastmod>'.now()->toAtomString().'</lastmod>');
});

it('does not retain uncommitted settings and sitemap entries after rollback', function (): void {
    $this->get('/sitemap-posts-1.xml')->assertOk();
    DB::beginTransaction();
    sitemapPost('temporary-post');
    Setting::updateOrCreate(['key' => 'seo_noindex_post_category'], ['group' => 'seo', 'value' => ['value' => true], 'is_translatable' => false]);
    expect($this->get('/sitemap-posts-1.xml')->getContent())->toContain('temporary-post')
        ->and(SiteSettings::get('seo_noindex_post_category'))->toBeTrue();

    DB::rollBack();
    expect($this->get('/sitemap-posts-1.xml')->getContent())->not->toContain('temporary-post')
        ->and(SiteSettings::get('seo_noindex_post_category', null, false))->toBeFalse();
});

it('invalidates settings repopulated by another request before commit', function (): void {
    DB::beginTransaction();
    Setting::updateOrCreate(['key' => 'seo_noindex_post_category'], ['group' => 'seo', 'value' => ['value' => true], 'is_translatable' => false]);
    Cache::put('site.settings', ['seo_noindex_post_category' => ['value' => false]], 60);
    expect(SiteSettings::get('seo_noindex_post_category'))->toBeTrue();

    DB::commit();
    expect(SiteSettings::get('seo_noindex_post_category'))->toBeTrue();
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

it('serves a browser stylesheet and references it from the XML', function (): void {
    $this->get('/sitemap.xsl')->assertOk()->assertHeader('Content-Type', 'text/xsl; charset=UTF-8');
    expect($this->get('/sitemap.xml')->getContent())->toContain('<?xml-stylesheet type="text/xsl" href="'.url('/sitemap.xsl').'"?>');
});

it('drops disabled content types and images per the Sitemap admin settings', function (): void {
    sitemapPost('یک');
    Setting::updateOrCreate(['key' => 'sitemap_types'], ['group' => 'seo', 'value' => ['value' => ['pages']], 'is_translatable' => false]);
    Setting::updateOrCreate(['key' => 'sitemap_images'], ['group' => 'seo', 'value' => ['value' => false], 'is_translatable' => false]);
    SiteSettings::flush();
    SitemapBuilder::bust();

    expect($this->get('/sitemap.xml')->getContent())->not->toContain('sitemap-posts-1.xml')->toContain('sitemap-pages-1.xml');
    $this->get('/sitemap-posts-1.xml')->assertNotFound();
});

it('lets admins open the Sitemap page', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/sitemap')->assertOk()->assertSee('نقشه سایت')->assertSee(url('/sitemap.xml'));
});
