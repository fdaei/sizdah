<?php

declare(strict_types=1);

use App\Models\PostCategory;
use App\Models\Redirect;
use App\Models\Setting;
use App\Support\SiteSettings;

function slugSetting(string $key, mixed $value): void
{
    Setting::updateOrCreate(['key' => $key], ['group' => 'seo', 'value' => ['value' => $value], 'is_translatable' => false]);
    SiteSettings::flush();
}

function renameCategory(): void
{
    $category = PostCategory::create(['sort_order' => 0]);
    $category->setTranslations(['fa' => ['name' => 'قدیم', 'slug' => 'old-cat']]);
    $category->setTranslations(['fa' => ['name' => 'قدیم', 'slug' => 'new-cat']]);
}

it('redirects a renamed category slug with 301 by default', function (): void {
    renameCategory();

    $r = Redirect::query()->where('origin', 'auto')->firstOrFail();

    expect($r->status_code)->toBe(301)
        ->and($r->source_path)->toContain('old-cat')
        ->and($r->destination_path)->toContain('new-cat');
});

it('uses the configured status code', function (): void {
    slugSetting('seo_slug_redirect_status', '308');
    renameCategory();

    expect(Redirect::query()->where('origin', 'auto')->value('status_code'))->toBe(308);
});

it('creates nothing when switched off', function (): void {
    slugSetting('seo_slug_redirect', false);
    renameCategory();

    expect(Redirect::query()->where('origin', 'auto')->count())->toBe(0);
});
