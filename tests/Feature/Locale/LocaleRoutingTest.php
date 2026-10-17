<?php

declare(strict_types=1);

use App\Models\Page;

it('redirects a bare path to the default locale', function (): void {
    $this->get('/')->assertRedirect('/fa');
});

it('redirects a bare path to a previously selected locale via session', function (): void {
    $this->withSession(['locale' => 'fa'])
        ->get('/about')
        ->assertRedirect('/fa/about');
});

it('serves each supported locale prefix', function (string $locale): void {
    Page::factory()->create(['key' => 'home'])->setTranslations([
        $locale => ['title' => 'Test title'],
    ]);

    $this->get("/{$locale}")->assertOk();
})->with(['fa']);

it('redirects an unsupported prefix as a bare path before returning not-found', function (string $locale): void {
    $this->get("/{$locale}")->assertRedirect("/fa/{$locale}");
    $this->get("/fa/{$locale}")->assertNotFound();
})->with(['en', 'ar', 'xx']);

it('ignores an unsupported locale saved in the session', function (string $locale): void {
    $this->withSession(['locale' => $locale])
        ->get('/about')
        ->assertRedirect('/fa/about');
})->with(['en', 'ar', 'xx']);

it('preserves query parameters when redirecting a bare path', function (): void {
    $this->get('/work?industry=food')->assertRedirect('/fa/work?industry=food');
});

it('sets rtl for persian', function (): void {
    Page::factory()->create(['key' => 'home'])->setTranslations([
        'fa' => ['title' => 'تست'],
    ]);

    $this->get('/fa')->assertOk()->assertSee('dir="rtl"', false);
});

it('returns not-found for an unknown path already prefixed with a supported locale', function (): void {
    $this->get('/fa/nonexistent')->assertNotFound();
});
