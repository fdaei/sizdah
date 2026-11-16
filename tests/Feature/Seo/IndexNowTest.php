<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Post;
use App\Models\Setting;
use App\Services\Seo\IndexNow;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Http;

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
