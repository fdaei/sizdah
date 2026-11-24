<?php

declare(strict_types=1);

use App\Filament\Pages\ManageSettings;
use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('previews unused images and rechecks references before deleting them', function (): void {
    Storage::fake('public');
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $make = function (string $path): Media {
        Storage::disk('public')->put($path, 'image');
        return Media::create(['path' => $path, 'name' => basename($path), 'mime' => 'image/jpeg', 'type' => 'image', 'size' => 5]);
    };
    $used = $make('library/used.jpg');
    $unused = $make('library/unused.jpg');
    $laterUsed = $make('library/later.jpg');
    Setting::updateOrCreate(['key' => 'image_top_home_mobile'], ['group' => 'general', 'value' => ['value' => $used->path], 'is_translatable' => false]);

    $page = Livewire::test(ManageSettings::class)->mountAction('cleanUnusedImages');
    expect($page->get('unusedImageIds'))->toContain($unused->id, $laterUsed->id)->not->toContain($used->id);
    Setting::updateOrCreate(['key' => 'image_top_about_mobile'], ['group' => 'general', 'value' => ['value' => $laterUsed->path], 'is_translatable' => false]);
    $page->callMountedAction()->assertHasNoErrors();

    Storage::disk('public')->assertExists($used->path);
    Storage::disk('public')->assertExists($laterUsed->path);
    Storage::disk('public')->assertMissing($unused->path);
    expect(Media::find($unused->id))->toBeNull()->and(Media::find($laterUsed->id))->not->toBeNull();
});
