<?php

declare(strict_types=1);

use App\Models\InternalLink;
use App\Models\Post;
use App\Models\Setting;
use App\Services\Content\ArticlePipeline;
use App\Services\Content\AutoLinker;
use App\Services\Content\HtmlFragment;
use App\Services\Content\LazyMedia;
use App\Services\Content\TableOfContents;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function contentSetting(string $key, mixed $value): void
{
    Setting::query()->updateOrCreate(['key' => $key], ['group' => 'content_tools', 'is_translatable' => false, 'value' => ['value' => $value]]);
}

// ── TOC ──────────────────────────────────────────────────────────────

it('builds Persian-safe unique heading ids and an outline', function (): void {
    $fragment = new HtmlFragment('<h2>مقدمه سئو</h2><p>x</p><h3>نیم‌فاصله‌ها</h3><h2>مقدمه سئو</h2><h2 id="custom">دارای شناسه</h2><h4>نادیده</h4>');

    $toc = TableOfContents::apply($fragment, ['h2', 'h3']);

    expect($toc)->toBe([
        ['id' => 'مقدمه-سئو', 'text' => 'مقدمه سئو', 'level' => 2],
        ['id' => 'نیم-فاصله-ها', 'text' => 'نیم‌فاصله‌ها', 'level' => 3],
        ['id' => 'مقدمه-سئو-2', 'text' => 'مقدمه سئو', 'level' => 2],
        ['id' => 'custom', 'text' => 'دارای شناسه', 'level' => 2],
    ])->and($fragment->html())->toContain('<h2 id="مقدمه-سئو" tabindex="-1">مقدمه سئو</h2>');
});

it('normalises Arabic yeh/kaf and diacritics in ids', function (): void {
    expect(TableOfContents::slug('كتاب عَلي'))->toBe('کتاب-علی')
        ->and(TableOfContents::slug('!!!'))->toBe('section');
});

it('shows the TOC only above the heading threshold and honours the per-post override', function (): void {
    $html = '<h2>یک</h2><h2>دو</h2><h2>سه</h2>';

    expect(ArticlePipeline::render($html, 'fa', null, null)['toc'])->toHaveCount(3)
        ->and(ArticlePipeline::render('<h2>یک</h2>', 'fa', null, null)['toc'])->toBe([])
        ->and(ArticlePipeline::render($html, 'fa', null, false)['toc'])->toBe([]);

    contentSetting('toc_enabled', false);
    expect(ArticlePipeline::render($html, 'fa', null, null)['toc'])->toBe([])
        ->and(ArticlePipeline::render($html, 'fa', null, true)['toc'])->toHaveCount(3);
});

// ── Auto internal links ─────────────────────────────────────────────

it('links keywords across ZWNJ, Arabic letters and case, respecting per-keyword limits', function (): void {
    $fragment = new HtmlFragment('<p>بهینه سازی سایت مهم است. بهینه‌سازی سايت را جدی بگیرید. SEO Tips و seo tips</p>');

    $count = AutoLinker::apply($fragment, [
        ['keyword' => 'بهینه‌سازی سایت', 'url' => '/fa/services/seo', 'max' => 2],
        ['keyword' => 'seo tips', 'url' => '/fa/insights/tips', 'max' => 1],
    ], null, 10);

    $html = $fragment->html();

    expect($count)->toBe(3)
        ->and(substr_count($html, 'href="/fa/services/seo"'))->toBe(2)
        ->and($html)->toContain('<a href="/fa/insights/tips" class="auto-link">SEO Tips</a>')
        ->and(substr_count($html, '/fa/insights/tips'))->toBe(1);
});

it('never links inside headings, existing links, code, or to the page itself', function (): void {
    $fragment = new HtmlFragment('<h2>سئو</h2><a href="/x">سئو</a><code>سئو</code><pre>سئو</pre><p>سئوکار و سئو</p>');

    AutoLinker::apply($fragment, [['keyword' => 'سئو', 'url' => '/fa/seo', 'max' => 5]], null, 10);
    $html = $fragment->html();

    expect($html)->toContain('<h2>سئو</h2>')
        ->toContain('<a href="/x">سئو</a>')
        ->toContain('<code>سئو</code>')
        // Whole-word only: "سئوکار" is not linked, the standalone word is.
        ->toContain('<p>سئوکار و <a href="/fa/seo" class="auto-link">سئو</a></p>');

    $self = new HtmlFragment('<p>سئو</p>');
    expect(AutoLinker::apply($self, [['keyword' => 'سئو', 'url' => 'https://site.test/fa/seo/', 'max' => 1]], '/fa/seo', 10))->toBe(0);
});

it('caps the total number of automatic links per article', function (): void {
    $fragment = new HtmlFragment('<p>الف ب ج</p>');

    expect(AutoLinker::apply($fragment, [
        ['keyword' => 'الف', 'url' => '/a', 'max' => 1],
        ['keyword' => 'ب', 'url' => '/b', 'max' => 1],
        ['keyword' => 'ج', 'url' => '/c', 'max' => 1],
    ], null, 2))->toBe(2);
});

// ── Lazy media ──────────────────────────────────────────────────────

it('lazy-loads media, keeps authored LCP hints, and swaps YouTube for a facade', function (): void {
    $fragment = new HtmlFragment('<img src="/a.jpg" alt="a"><img src="/hero.jpg" alt="h" fetchpriority="high">'
        .'<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="ویدیو"></iframe><iframe src="https://www.aparat.com/video/video/embed/videohash/abc"></iframe>');

    LazyMedia::apply($fragment, true, 'پخش ویدیو: :title');
    $html = $fragment->html();

    expect($html)->toContain('<img src="/a.jpg" alt="a" loading="lazy" decoding="async">')
        ->toContain('<img src="/hero.jpg" alt="h" fetchpriority="high">')
        ->toContain('data-embed="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?autoplay=1"')
        ->toContain('aria-label="پخش ویدیو: ویدیو"')
        ->toContain('https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg')
        ->not->toContain('youtube.com/embed')
        ->toContain('aparat.com/video/video/embed/videohash/abc" loading="lazy"');
});

// ── End to end ──────────────────────────────────────────────────────

it('ships TOC and auto links on the article page without touching stored content', function (): void {
    $stored = '<h2>اول</h2><p>درباره برندینگ</p><h2>دوم</h2><h2>سوم</h2>';
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'pipeline-article', 'content' => $stored]]);
    InternalLink::create(['locale' => 'fa', 'keyword' => 'برندینگ', 'url' => '/fa/services/branding', 'max_per_page' => 1]);

    $this->get('/fa/insights/pipeline-article')->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('post.toc.0.id', 'اول')
            ->where('post.toc', fn ($toc) => count($toc) === 3)
            ->where('post.content', fn (string $html) => str_contains($html, '<a href="/fa/services/branding" class="auto-link">برندینگ</a>')));

    expect($post->fresh()->getTranslation('content', 'fa'))->toBe($stored);
});

it('picks up a new link rule without a manual cache clear', function (): void {
    expect(ArticlePipeline::render('<p>طراحی سایت</p>', 'fa', null, null)['html'])->not->toContain('auto-link');

    InternalLink::create(['locale' => 'fa', 'keyword' => 'طراحی سایت', 'url' => '/fa/web', 'max_per_page' => 1]);

    expect(ArticlePipeline::render('<p>طراحی سایت</p>', 'fa', null, null)['html'])->toContain('href="/fa/web"');
});

it('renders the new admin screens for an admin', function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $admin = \App\Models\User::factory()->create();
    $admin->assignRole('admin');
    $post = Post::factory()->create();
    $post->setTranslations(['fa' => ['title' => 'م', 'slug' => 'admin-smoke']]);

    $this->actingAs($admin);
    foreach (['/admin/internal-links', '/admin/internal-links/create', '/admin/manage-content-tools',
        '/admin/manage-performance', '/admin/manage-ui-strings', '/admin/service-ratings', "/admin/posts/{$post->id}/edit"] as $url) {
        $this->get($url)->assertOk();
    }

    \Livewire\Livewire::test(\App\Filament\Resources\InternalLinkResource\Pages\CreateInternalLink::class)
        ->fillForm(['keyword' => 'سئو', 'url' => '/fa/seo', 'locale' => 'fa', 'max_per_page' => 1, 'is_active' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    \Livewire\Livewire::test(\App\Filament\Pages\ManageContentTools::class)
        ->set('data.toc_min_headings', 2)
        ->call('save')
        ->assertHasNoErrors();

    expect(InternalLink::query()->where('keyword', 'سئو')->exists())->toBeTrue()
        ->and(\App\Support\ContentTools::get('toc_min_headings'))->toBe(2);
});
