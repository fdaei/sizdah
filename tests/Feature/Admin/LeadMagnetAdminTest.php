<?php

declare(strict_types=1);

use App\Enums\LeadMagnetEmailStatus;
use App\Models\LeadMagnetRequest;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

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
