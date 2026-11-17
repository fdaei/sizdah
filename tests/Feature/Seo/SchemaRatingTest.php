<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceRating;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function ratedService(): Service
{
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now(), 'sort_order' => 1]);
    $service->setTranslations(['fa' => ['title' => 'سئو', 'slug' => 'seo-service', 'description' => 'توضیح']]);

    return $service;
}

/** @return array<string, mixed>|null the Service node of the page's JSON-LD */
function serviceNode(string $html): ?array
{
    preg_match('#<script type="application/ld\+json"[^>]*>(.*?)</script>#s', $html, $m);
    $graph = json_decode($m[1] ?? 'null', true)['@graph'] ?? [];

    return collect($graph)->firstWhere('@type', 'Service');
}

it('stores a pending rating and ignores a repeat vote from the same visitor', function (): void {
    $service = ratedService();

    $this->post('/fa/services/seo-service/rate', ['rating' => 5])->assertRedirect()->assertSessionHas('success', __('forms.rating_thanks'));
    $this->post('/fa/services/seo-service/rate', ['rating' => 1])->assertSessionHas('success', __('forms.rating_duplicate'));

    expect(ServiceRating::query()->where('service_id', $service->id)->pluck('rating')->all())->toBe([5])
        ->and(ServiceRating::query()->first()->approved_at)->toBeNull();
});

it('validates the rating range', function (): void {
    ratedService();

    $this->post('/fa/services/seo-service/rate', ['rating' => 9])->assertSessionHasErrors('rating');
});

it('rate-limits rating submissions', function (): void {
    ratedService();

    foreach (range(1, 5) as $i) {
        $this->withHeader('User-Agent', "agent-{$i}")->post('/fa/services/seo-service/rate', ['rating' => 4]);
    }

    $this->post('/fa/services/seo-service/rate', ['rating' => 4])->assertStatus(429);
});

it('emits AggregateRating only from approved ratings', function (): void {
    $service = ratedService();
    ServiceRating::create(['service_id' => $service->id, 'rating' => 5, 'voter_hash' => str_repeat('a', 64)]);

    expect(serviceNode($this->get('/fa/services/seo-service')->getContent()))->not->toHaveKey('aggregateRating');

    ServiceRating::create(['service_id' => $service->id, 'rating' => 4, 'voter_hash' => str_repeat('b', 64), 'approved_at' => now()]);
    ServiceRating::create(['service_id' => $service->id, 'rating' => 5, 'voter_hash' => str_repeat('c', 64), 'approved_at' => now()]);

    expect(serviceNode($this->get('/fa/services/seo-service')->getContent())['aggregateRating'])->toBe([
        '@type' => 'AggregateRating',
        'ratingValue' => 4.5,
        'ratingCount' => 2,
        'bestRating' => 5,
        'worstRating' => 1,
    ]);
});

it('turns page schema off or replaces it with custom JSON-LD', function (): void {
    $page = Page::factory()->create(['key' => 'about']);
    $page->setTranslations(['fa' => ['title' => 'درباره ما']]);

    $page->saveSeo(['fa' => ['schema_data' => ['mode' => 'off']]]);
    expect($this->get('/fa/about')->getContent())->not->toContain('application/ld+json');

    $page->saveSeo(['fa' => [
        'schema_data' => ['mode' => 'replace'],
        'custom_schema' => '{"@context":"https://schema.org","@type":"Event","name":"رویداد"}',
    ]]);

    preg_match('#<script type="application/ld\+json"[^>]*>(.*?)</script>#s', $this->get('/fa/about')->getContent(), $m);
    expect(array_column(json_decode($m[1], true)['@graph'], '@type'))->toBe(['Event']);
});

it('lets an admin approve ratings from the moderation queue', function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $admin = \App\Models\User::factory()->create();
    $admin->assignRole('admin');
    $rating = ServiceRating::create(['service_id' => ratedService()->id, 'rating' => 3, 'voter_hash' => str_repeat('d', 64)]);

    $this->actingAs($admin)->get('/admin/service-ratings')->assertOk();

    \Livewire\Livewire::actingAs($admin)
        ->test(\App\Filament\Resources\ServiceRatingResource\Pages\ListServiceRatings::class)
        ->callTableAction('approve', $rating);

    expect($rating->fresh()->approved_at)->not->toBeNull();
});
