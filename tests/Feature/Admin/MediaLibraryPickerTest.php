<?php

declare(strict_types=1);

use App\Filament\Pages\ManageSettings;
use App\Models\Media;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('reuses the original library file when selecting a page background', function (): void {
    Storage::fake('public');
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $path = 'library/2026/11/original-image.jpg';
    Storage::disk('public')->put($path, 'image contents');
    $media = Media::query()->create([
        'path' => $path, 'name' => 'original-image.jpg', 'mime' => 'image/jpeg',
        'type' => 'image', 'size' => 14,
    ]);

    Livewire::test(ManageSettings::class)
        ->callFormComponentAction('image_top_home_desktop', 'fromMediaLibrary', ['media' => $media->id])
        ->set('data.contact_details', [])
        ->call('save')
        ->assertHasNoErrors();

    expect(\App\Support\SiteSettings::get('image_top_home_desktop'))->toBe($path)
        ->and(Media::query()->count())->toBe(1)
        ->and(Storage::disk('public')->allFiles())->toBe([$path]);
});
