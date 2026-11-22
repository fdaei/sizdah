<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Setting;
use App\Models\User;
use App\Filament\Resources\PostTagResource\Pages\CreatePostTag;
use App\Filament\Resources\PostTagResource\Pages\EditPostTag;
use App\Services\Sitemap\SitemapBuilder;
use App\Support\SiteSettings;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;

function archiveTag(string $slug): PostTag
{
    $tag = PostTag::create();
    $tag->setTranslations(['fa' => ['name' => 'برچسب تست', 'slug' => $slug]]);

    return $tag;
}

it('serves a tag archive with its own seo and only published tagged articles', function (): void {
    $tag = archiveTag('marketing');
    $tag->saveSeo(['fa' => ['title' => 'عنوان برچسب', 'description' => 'توضیح برچسب']]);
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله منتشر', 'slug' => 'published']]);
    $post->tags()->attach($tag);
    $draft = Post::factory()->create();
    $draft->setTranslations(['fa' => ['title' => 'پیش نویس', 'slug' => 'draft']]);
    $draft->tags()->attach($tag);

    $this->get('/fa/insights/tag/marketing')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Insights/Index')->where('heading.title', 'برچسب تست')
        ->where('posts.total', 1)->where('posts.data.0.title', 'مقاله منتشر')
        ->where('seo.canonical', url('/fa/insights/tag/marketing')));
    expect($this->get('/fa/insights/tag/marketing')->getContent())->toContain('عنوان برچسب')->toContain('توضیح برچسب');
    $this->get('/fa/insights/tag/missing')->assertNotFound();
});

it('includes indexable tags with published posts in the sitemap and honors the noindex setting', function (): void {
    $tag = archiveTag('marketing');
    $post = Post::factory()->published()->create();
    $post->tags()->attach($tag);
    SitemapBuilder::bust();

    expect($this->get('/sitemap-tags-1.xml')->assertOk()->getContent())->toContain('/fa/insights/tag/marketing');
    Setting::updateOrCreate(['key' => 'seo_noindex_post_tag'], ['group' => 'seo', 'value' => ['value' => true], 'is_translatable' => false]);
    SiteSettings::flush();
    expect($this->get('/fa/insights/tag/marketing')->getContent())->toContain('content="noindex, follow" inertia="robots"')
        ->and($this->get('/sitemap.xml')->getContent())->not->toContain('sitemap-tags-1.xml');
});

it('redirects old tag slugs when they change', function (): void {
    $tag = archiveTag('old-tag');
    $tag->setTranslations(['fa' => ['name' => 'برچسب تست', 'slug' => 'new-tag']]);

    $this->get('/fa/insights/tag/old-tag')->assertRedirect('/fa/insights/tag/new-tag')->assertStatus(301);
});

it('links from the server-rendered article to its tag archive', function (): void {
    config(['inertia.ssr.enabled' => false]);
    $tag = archiveTag('marketing');
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'article']]);
    $post->tags()->attach($tag);

    expect($this->get('/fa/insights/article')->assertOk()->getContent())
        ->toContain('href="'.url('/fa/insights/tag/marketing').'"')
        ->toContain('برچسب تست');
});

it('saves and updates tag seo through the admin form', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);

    Livewire::test(CreatePostTag::class)
        ->fillForm([
            'translations' => ['fa' => ['name' => 'بازاریابی', 'slug' => 'marketing']],
            'seo' => ['fa' => ['title' => 'عنوان ویژه', 'description' => 'توضیح ویژه', 'robots_index' => '0']],
        ])->call('create')->assertHasNoFormErrors();

    $tag = PostTag::query()->firstOrFail();
    expect($tag->seoFor('fa')->title)->toBe('عنوان ویژه')
        ->and($tag->seoFor('fa')->robots_index)->toBeFalse();
    $this->get('/fa/insights/tag/marketing')->assertOk()->assertSee('عنوان ویژه')->assertSee('noindex, follow');

    Livewire::test(EditPostTag::class, ['record' => $tag->id])
        ->fillForm(['seo' => ['fa' => ['title' => 'عنوان تازه', 'robots_index' => '1']]])
        ->call('save')->assertHasNoFormErrors();

    expect($tag->fresh()->seoFor('fa')->title)->toBe('عنوان تازه')
        ->and($tag->fresh()->seoFor('fa')->robots_index)->toBeTrue();
    $this->get('/fa/insights/tag/marketing')->assertOk()->assertSee('عنوان تازه')->assertSee('index, follow');
});

it('rejects a slug that becomes empty after normalization in the admin form', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('admin');
    $this->actingAs($user);

    Livewire::test(CreatePostTag::class)
        ->fillForm(['translations' => ['fa' => ['name' => 'نام', 'slug' => '---']]])
        ->call('create')->assertHasFormErrors(['translations.fa.slug']);

    expect(PostTag::count())->toBe(0);
});
