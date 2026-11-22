<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Post;
use App\Models\Service;
use App\Models\Setting;
use App\Services\Seo\IndexNow;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

function enableIndexNow(): string
{
    $key = IndexNow::ensureKey();
    Setting::updateOrCreate(['key' => 'seo_indexnow_enabled'], ['group' => 'seo', 'value' => ['value' => true], 'is_translatable' => false]);
    SiteSettings::flush();

    return $key;
}

it('generates a 32-char hex key once and serves it as /{key}.txt', function (): void {
    $key = IndexNow::ensureKey();

    expect($key)->toMatch('/^[a-f0-9]{32}$/')->and(IndexNow::ensureKey())->toBe($key);
    $this->get("/{$key}.txt")->assertOk()->assertSeeText($key);
    $this->get('/'.str_repeat('a', 32).'.txt')->assertNotFound();
});

it('pings IndexNow when a published post is saved in production', function (): void {
    Http::fake();
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    $post = Post::factory()->create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay()]);
    $post->setTranslations(['fa' => ['title' => 'ت', 'slug' => 'ت', 'content' => '<p>x</p>']]);
    $post->touch();
    app()->terminate();

    Http::assertSent(fn ($r): bool => $r->url() === IndexNow::ENDPOINT
        && str_contains(json_encode($r->data(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), '/fa/insights/'));
});

it('never pings outside production, for drafts, or when disabled', function (): void {
    Http::fake();
    enableIndexNow();

    Post::factory()->create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay()])->touch();
    app()->detectEnvironment(fn (): string => 'production');
    Post::factory()->create(['status' => PublicationStatus::Draft, 'published_at' => null])->touch();
    app()->terminate();

    Http::assertNothingSent();
});

it('does not break creating a published post before its translations exist', function (): void {
    Http::fake();
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    $post = Post::factory()->create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay()]);

    expect($post->exists)->toBeTrue();
    app()->terminate();
    Http::assertNothingSent();
});

it('notifies IndexNow about published service URLs on translation changes', function (): void {
    Http::fake();
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay(), 'sort_order' => 1]);
    $service->setTranslations(['fa' => ['title' => 'خدمت', 'slug' => 'indexnow-service']]);

    app()->terminate();
    Http::assertSent(fn ($request): bool => in_array(url('/fa/services/indexnow-service'), $request->data()['urlList'] ?? [], true));
});

it('records unsuccessful IndexNow responses without interrupting the editor', function (): void {
    Http::fake([IndexNow::ENDPOINT => Http::response('', 429)]);
    Log::shouldReceive('warning')->once()->with('IndexNow rejected the submission', ['status' => 429, 'url_count' => 1]);
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    IndexNow::submit([url('/fa/services/test')]);
    app()->terminate();
});

it('notifies IndexNow when published content is unpublished or deleted', function (): void {
    Http::fake();
    $unpublished = Post::factory()->published()->create();
    $unpublished->setTranslations(['fa' => ['title' => 'اول', 'slug' => 'unpublished-post']]);
    $deleted = Post::factory()->published()->create();
    $deleted->setTranslations(['fa' => ['title' => 'دوم', 'slug' => 'deleted-post']]);
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    $unpublished->unpublish();
    $deleted->delete();
    app()->terminate();

    Http::assertSent(fn ($r): bool => in_array(url('/fa/insights/unpublished-post'), $r->data()['urlList'] ?? [], true));
    Http::assertSent(fn ($r): bool => in_array(url('/fa/insights/deleted-post'), $r->data()['urlList'] ?? [], true));
});

it('does not notify IndexNow when an unpublished draft is edited', function (): void {
    Http::fake();
    $draft = Post::factory()->create();
    $draft->setTranslations(['fa' => ['title' => 'پیش نویس', 'slug' => 'draft-post']]);
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    $draft->touch();
    $draft->delete();
    app()->terminate();

    Http::assertNothingSent();
});

it('notifies the old address when a published item is permanently deleted', function (): void {
    Http::fake();
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'permanently-removed']]);
    $post->unsetRelation('translations');
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    $post->forceDelete();
    app()->terminate();

    Http::assertSent(fn ($r): bool => in_array(url('/fa/insights/permanently-removed'), $r->data()['urlList'] ?? [], true));
});

it('does not notify IndexNow about rolled back changes', function (): void {
    Http::fake();
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    DB::beginTransaction();
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'آزمایشی', 'slug' => 'rolled-back']]);
    DB::rollBack();
    app()->terminate();

    Http::assertNothingSent();
});

it('notifies IndexNow after a successful transaction commits', function (): void {
    Http::fake();
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    DB::transaction(function (): void {
        $post = Post::factory()->published()->create();
        $post->setTranslations(['fa' => ['title' => 'منتشر', 'slug' => 'committed-post']]);
    });
    app()->terminate();

    Http::assertSent(fn ($r): bool => in_array(url('/fa/insights/committed-post'), $r->data()['urlList'] ?? [], true));
});

it('notifies both old and new URLs after renaming published content', function (): void {
    Http::fake();
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'old-slug']]);
    Setting::updateOrCreate(['key' => 'seo_slug_redirect'], ['group' => 'seo', 'value' => ['value' => false], 'is_translatable' => false]);
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    $post->setTranslations(['fa' => ['slug' => 'new-slug']]);
    app()->terminate();

    Http::assertSent(fn ($r): bool => in_array(url('/fa/insights/old-slug'), $r->data()['urlList'] ?? [], true));
    Http::assertSent(fn ($r): bool => in_array(url('/fa/insights/new-slug'), $r->data()['urlList'] ?? [], true));
});

it('does not notify either URL when a slug rename is rolled back', function (): void {
    Http::fake();
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'old-slug']]);
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    DB::beginTransaction();
    $post->setTranslations(['fa' => ['slug' => 'new-slug']]);
    DB::rollBack();
    app()->terminate();

    Http::assertNothingSent();
});

it('batches repeated content changes without submitting duplicate URLs', function (): void {
    Http::fake();
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'batched']]);
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');

    $post->setTranslations(['fa' => ['title' => 'عنوان تازه']]);
    $post->saveSeo(['fa' => ['description' => 'توضیح تازه']]);
    $post->touch();
    app()->terminate();

    Http::assertSentCount(1);
    Http::assertSent(fn ($r): bool => $r->data()['urlList'] === [url('/fa/insights/batched')]);
});

it('splits large IndexNow submissions into batches of at most ten thousand', function (): void {
    Http::fake();
    enableIndexNow();
    app()->detectEnvironment(fn (): string => 'production');
    $urls = array_map(fn (int $i): string => url('/fa/insights/post-'.$i), range(1, 10001));

    IndexNow::submit($urls);
    app()->terminate();

    Http::assertSentCount(2);
    $sizes = Http::recorded()->map(fn ($pair): int => count($pair[0]->data()['urlList']))->all();
    expect($sizes)->toBe([10000, 1]);
});
