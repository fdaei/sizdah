<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Filament\Resources\ServiceResource\Pages\EditService;
use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Support\Seo\SeoContext;
use App\Models\Service;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use App\Support\SiteSettings;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;
use Illuminate\Support\Facades\Storage;

it('edits a service slug and seo in the panel and redirects the old public address', function (): void {
    config(['inertia.ssr.enabled' => false]);
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay(), 'sort_order' => 1]);
    $service->setTranslations(['fa' => ['title' => 'خدمت', 'slug' => 'old-service']]);

    Livewire::test(EditService::class, ['record' => $service->id])
        ->fillForm(['translations' => ['fa' => ['slug' => 'new-service']], 'seo' => ['fa' => ['title' => 'عنوان خدمت']]])
        ->call('save')->assertHasNoFormErrors();

    $this->get('/fa/services/old-service')->assertRedirect('/fa/services/new-service')->assertStatus(301);
    $this->get('/fa/services/new-service')->assertOk()->assertSee('عنوان خدمت');
});

it('analyzes titles using the same type-specific templates as the public site', function (): void {
    foreach (['website' => '%title% | صفحه', 'article' => '%title% | مقاله'] as $type => $template) {
        Setting::updateOrCreate(['key' => 'seo_title_template_'.$type], [
            'group' => 'seo', 'value' => ['fa' => $template], 'is_translatable' => true,
        ]);
    }
    SiteSettings::flush();

    expect((new SeoContext)->analysisInput(['title' => 'نمونه'], [], 'fa', null)['title'])->toBe('نمونه | صفحه')
        ->and((new SeoContext(isArticle: true))->analysisInput(['title' => 'نمونه'], [], 'fa', null)['title'])->toBe('نمونه | مقاله');
});

it('shows the public page path in the SEO snippet preview', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $page = Page::factory()->create(['key' => 'about']);
    $page->setTranslations(['fa' => ['title' => 'درباره ما']]);

    Livewire::test(EditPage::class, ['record' => $page->id])
        ->assertSee('fa › about');
});

it('uses the public fallback title, description and image in social previews', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    Storage::fake('public');
    Storage::disk('public')->put('posts/preview.webp', 'placeholder');
    $post = Post::factory()->published()->create(['cover_path' => 'posts/preview.webp']);
    $post->setTranslations(['fa' => [
        'title' => 'عنوان مقاله', 'slug' => 'social-preview', 'excerpt' => 'خلاصه مقاله',
        'seo_title' => 'عنوان قدیمی سئو', 'seo_description' => 'توضیح قدیمی سئو',
    ]]);

    Livewire::test(EditPost::class, ['record' => $post->id])
        ->assertSee('عنوان قدیمی سئو')
        ->assertSee('توضیح قدیمی سئو')
        ->assertSee('/storage/posts/preview.webp');
});
