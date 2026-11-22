<?php

declare(strict_types=1);

use App\Filament\Pages\ManageSeoSettings;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Support\SiteSettings;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function seoSetting(string $key, mixed $value, bool $translatable = false): void
{
    Setting::updateOrCreate(['key' => $key], [
        'group' => 'seo',
        'value' => $translatable ? ['fa' => $value] : ['value' => $value],
        'is_translatable' => $translatable,
    ]);
    SiteSettings::flush();
}

it('serves a custom robots.txt and always appends one absolute Sitemap line', function (): void {
    seoSetting('seo_robots_txt', "User-agent: *\r\nDisallow: /tmp\r\nSitemap: http://wrong.example/s.xml");

    $body = $this->get('/robots.txt')->assertOk()->getContent();

    expect($body)->toContain('Disallow: /tmp')
        ->not->toContain('wrong.example')
        ->and(substr_count($body, 'Sitemap:'))->toBe(1)
        ->and($body)->toContain('Sitemap: '.url('/sitemap.xml'));
});

it('renders webmaster verification metas and custom head/body code', function (): void {
    seoPage('about', 'درباره ما');
    seoSetting('seo_verify_google', 'abc123');
    seoSetting('seo_verify_bing', 'BING42');
    seoSetting('seo_head_code', '<script>window.headCode=1</script>');
    seoSetting('seo_body_code', '<script>window.bodyCode=1</script>');

    expect($this->get('/fa/about')->getContent())
        ->toContain('<meta name="google-site-verification" content="abc123">')
        ->toContain('<meta name="msvalidate.01" content="BING42">')
        ->toContain('<script>window.headCode=1</script>')
        ->toContain('<script>window.bodyCode=1</script>');
});

it('extracts the code from a pasted verification meta tag', function (): void {
    expect(ManageSeoSettings::extractContent('<meta name="google-site-verification" content="XyZ_9-a" />'))->toBe('XyZ_9-a')
        ->and(ManageSeoSettings::extractContent('  XyZ_9-a '))->toBe('XyZ_9-a')
        ->and(ManageSeoSettings::extractContent(''))->toBeNull();
});

it('noindexes the whole site when "discourage search engines" is on', function (): void {
    seoPage('about', 'درباره ما');
    seoSetting('seo_noindex_site', true);

    expect($this->get('/fa/about')->getContent())->toContain('content="noindex, follow" inertia="robots"');
});

it('noindexes paginated pages only when the setting is on', function (): void {
    seoPage('insights', 'مقالات');

    expect($this->get('/fa/insights?page=2')->getContent())->toContain('content="index, follow');

    seoSetting('seo_noindex_paginated', true);
    expect($this->get('/fa/insights?page=2')->getContent())->toContain('content="noindex, follow" inertia="robots"');
});

it('applies the title separator and the posts/pages templates', function (): void {
    // No legacy seo_title: a per-item title would (correctly) win over the type template.
    Page::factory()->create(['key' => 'about'])->setTranslations(['fa' => ['title' => 'درباره ما']]);
    seoSetting('seo_title_separator', '|');
    seoSetting('seo_title_template_website', '%title% %sep% برند', true);

    expect($this->get('/fa/about')->getContent())->toContain('<title inertia>درباره ما | برند</title>');
});

it('builds a LocalBusiness knowledge graph with address, geo and hours', function (): void {
    seoPage('about', 'درباره ما');
    seoSetting('seo_kg_business_type', 'ProfessionalService');
    seoSetting('seo_kg_locality', 'کرمان', true);
    seoSetting('seo_kg_country', 'IR');
    seoSetting('seo_kg_lat', '30.28');
    seoSetting('seo_kg_lng', '57.07');
    seoSetting('seo_kg_hours', [['days' => ['Saturday', 'Sunday'], 'opens' => '09:00:00', 'closes' => '17:00:00']]);

    $org = collect(jsonLd($this->get('/fa/about')->getContent())['@graph'])
        ->first(fn (array $n): bool => str_ends_with($n['@id'] ?? '', '#organization'));

    expect($org['@type'])->toBe(['Organization', 'ProfessionalService'])
        ->and($org['address']['addressLocality'])->toBe('کرمان')
        ->and($org['geo']['latitude'])->toBe(30.28)
        ->and($org['openingHoursSpecification'][0])->toMatchArray(['opens' => '09:00', 'closes' => '17:00']);
});

it('switches the knowledge graph to a Person', function (): void {
    seoPage('about', 'درباره ما');
    seoSetting('seo_kg_type', 'Person');

    $types = array_column(jsonLd($this->get('/fa/about')->getContent())['@graph'], '@type');

    expect($types)->toContain('Person')->not->toContain('Organization');
});

it('drops BreadcrumbList when breadcrumbs are disabled and uses the custom home label', function (): void {
    seoPage('about', 'درباره ما');
    seoSetting('seo_breadcrumb_home', 'سیزده', true);

    $crumbs = collect(jsonLd($this->get('/fa/about')->getContent())['@graph'])->firstWhere('@type', 'BreadcrumbList');
    expect($crumbs['itemListElement'][0]['name'])->toBe('سیزده');

    seoSetting('seo_breadcrumbs_enabled', false);
    expect(array_column(jsonLd($this->get('/fa/about')->getContent())['@graph'], '@type'))->not->toContain('BreadcrumbList');
});

it('lets admins open the SEO settings page', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/seo-settings')->assertOk()->assertSee('تنظیمات سئو');
});

it('provides an up-to-date guide for the public archives and team schema', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/seo-guide')->assertOk()
        ->assertSee('دسته‌بندی‌های پروژه آرشیو عمومی دارند')
        ->assertSee('صفحه سئوی مستقلی ندارند')
        ->assertDontSee('هنوز صفحه‌ی عمومی ندارند');
});

it('preserves inactive locale values and hidden redirect settings when saving', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    Setting::updateOrCreate(['key' => 'seo_default_title'], [
        'group' => 'seo', 'value' => ['fa' => 'قبلی', 'en' => 'English title'], 'is_translatable' => true,
    ]);
    seoSetting('seo_slug_redirect_status', '308');

    Livewire::test(ManageSeoSettings::class)
        ->fillForm(['seo_default_title' => ['fa' => 'جدید'], 'seo_slug_redirect' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Setting::where('key', 'seo_default_title')->firstOrFail()->value)
        ->toMatchArray(['fa' => 'جدید', 'en' => 'English title'])
        ->and(Setting::where('key', 'seo_slug_redirect_status')->firstOrFail()->value['value'])->toBe('308');
});
