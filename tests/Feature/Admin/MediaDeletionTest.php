<?php

declare(strict_types=1);

use App\Filament\Resources\MediaResource\Pages\ListMedia;
use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use App\Services\Media\MediaUsage;
use Database\Seeders\RolePermissionSeeder;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function (): void {
    Storage::fake('public');
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
});

function deletionMedia(string $path): Media
{
    Storage::disk('public')->put($path, 'image');
    return Media::create(['path' => $path, 'name' => basename($path), 'mime' => 'image/jpeg', 'type' => 'image', 'size' => 5]);
}

it('blocks deletion and identifies the setting that uses an image', function (): void {
    $media = deletionMedia('library/photo.jpg');
    Setting::updateOrCreate(['key' => 'image_top_home_mobile'], ['group' => 'general', 'value' => ['value' => $media->path], 'is_translatable' => false]);
    expect(MediaUsage::find($media))->toContain('تنظیمات سایت پس‌زمینه صفحه اصلی، موبایل (/admin/manage-settings) — مقدار تنظیم');
    Livewire::test(ListMedia::class)->callTableAction(DeleteAction::class, $media);
    expect(Media::find($media->id))->not->toBeNull();
    Storage::disk('public')->assertExists($media->path);
});

it('removes an unused file even when it was uploaded outside the library', function (): void {
    $media = deletionMedia('posts/unused.jpg');
    Livewire::test(ListMedia::class)->callTableAction(DeleteAction::class, $media);
    expect(Media::find($media->id))->toBeNull();
    Storage::disk('public')->assertMissing($media->path);
});

it('detects references inside article content and keeps them in bulk deletion', function (): void {
    $used = deletionMedia('library/in-content.jpg');
    $unused = deletionMedia('library/unused.jpg');
    $post = \App\Models\Post::factory()->create();
    $post->setTranslations(['fa' => ['title' => 'نمونه', 'slug' => 'example', 'content' => '<p><img src="/storage/library/in-content.jpg"></p>']]);

    expect(implode(' ', MediaUsage::find($used)))->toContain('مقاله')->toContain('/admin/posts/'.$post->id.'/edit');
    Livewire::test(ListMedia::class)->callTableBulkAction('deleteUnused', [$used, $unused]);
    expect(Media::find($used->id))->not->toBeNull()
        ->and(Media::find($unused->id))->toBeNull();
    Storage::disk('public')->assertExists($used->path);
    Storage::disk('public')->assertMissing($unused->path);
});
