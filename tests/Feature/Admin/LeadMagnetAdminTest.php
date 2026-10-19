<?php

declare(strict_types=1);

use App\Enums\LeadMagnetEmailStatus;
use App\Filament\Pages\ManageHomeLeadMagnet;
use App\Models\LeadMagnetRequest;
use App\Models\Post;
use App\Models\User;
use App\Support\HomeLeadMagnet;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

it('lists lead magnet requests with their email status for an editor', function (): void {
    $user = User::factory()->create();
    $user->assignRole('editor');

    LeadMagnetRequest::factory()->create([
        'email' => 'failed-reader@example.com',
        'email_status' => LeadMagnetEmailStatus::Failed,
        'email_error' => 'SMTP 550',
    ]);

    $this->actingAs($user)
        ->get('/admin/lead-magnet-requests')
        ->assertOk()
        ->assertSee('failed-reader@example.com');
});

it('shows the lead magnet fields and requests on the article edit page', function (): void {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $post = Post::factory()->published()->create()
        ->setTranslations(['fa' => ['title' => 'مقاله', 'slug' => 'maghale']]);

    $this->actingAs($user)
        ->get("/admin/posts/{$post->id}/edit")
        ->assertOk()
        ->assertSee('lead_magnet_send_email');
});

it('lets an admin set the home lead magnet file and email toggle', function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('lead-magnets/home.pdf', '%PDF-1.4 test');

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin/home-lead-magnet')
        ->assertOk();

    Livewire::actingAs($user)
        ->test(ManageHomeLeadMagnet::class)
        ->set('data.path', ['lead-magnets/home.pdf'])
        ->set('data.send_email', true)
        ->call('save')
        ->assertHasNoErrors();

    expect(HomeLeadMagnet::path())->toBe('lead-magnets/home.pdf')
        ->and(HomeLeadMagnet::emails())->toBeTrue();
});

it('requires a file when home email delivery is on', function (): void {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Livewire::actingAs($user)
        ->test(ManageHomeLeadMagnet::class)
        ->set('data.send_email', true)
        ->call('save')
        ->assertHasErrors(['data.path']);

    expect(HomeLeadMagnet::emails())->toBeFalse();
});

it('hides the home lead magnet page from editors', function (): void {
    $user = User::factory()->create();
    $user->assignRole('editor');

    $this->actingAs($user)
        ->get('/admin/home-lead-magnet')
        ->assertForbidden();
});
