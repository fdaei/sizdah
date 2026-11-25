<?php

declare(strict_types=1);

use App\Filament\Resources\MediaResource\Pages\CreateMedia;
use App\Models\Media;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('stores an editor-prepared image with its original bytes and format', function (string $extension): void {
    Storage::fake('public');
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $upload = UploadedFile::fake()->image('seo-cover.'.$extension, 80, 100);
    $original = file_get_contents($upload->getRealPath());

    Livewire::test(CreateMedia::class)
        ->fillForm(['path' => $upload])
        ->call('create')
        ->assertHasNoFormErrors();

    $media = Media::query()->sole();
    $disk = Storage::disk('public');
    expect(pathinfo($media->path, PATHINFO_EXTENSION))->toBe($extension)
        ->and($disk->get($media->path))->toBe($original)
        ->and($disk->allFiles('library'))->toBe([$media->path])
        ->and($media->size)->toBe(strlen($original));
})->with(['jpg', 'png']);
