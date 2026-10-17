<?php

declare(strict_types=1);

use App\Models\NewsletterSubscription;

it('subscribes a new email address', function (): void {
    $response = $this->from('/fa')->post('/fa/newsletter', [
        'email' => 'lead@example.com',
        'source' => 'home',
    ]);

    $response->assertRedirect('/fa')->assertSessionHasNoErrors()->assertSessionHas('success');
    expect(NewsletterSubscription::query()->where('email', 'lead@example.com')->exists())->toBeTrue();
});

it('reactivates a previously unsubscribed address without creating a duplicate', function (): void {
    NewsletterSubscription::factory()->create([
        'email' => 'returning@example.com',
        'unsubscribed_at' => now()->subMonth(),
    ]);

    $this->from('/fa')->post('/fa/newsletter', ['email' => 'returning@example.com'])
        ->assertRedirect('/fa')->assertSessionHasNoErrors()->assertSessionHas('success');

    expect(NewsletterSubscription::query()->where('email', 'returning@example.com')->count())->toBe(1);
    expect(NewsletterSubscription::query()->where('email', 'returning@example.com')->first()->isActive())->toBeTrue();
});

it('rejects a malformed email address without subscribing', function (): void {
    $this->from('/fa')->post('/fa/newsletter', ['email' => 'not-an-email'])
        ->assertRedirect('/fa')->assertSessionHasErrors('email');

    expect(NewsletterSubscription::query()->count())->toBe(0);
});
