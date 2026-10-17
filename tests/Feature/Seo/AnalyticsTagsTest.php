<?php

declare(strict_types=1);

use App\Models\Page;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
    Page::factory()->create(['key' => 'home']);
});

it('renders GTM and GA4 snippets when analytics is enabled', function (): void {
    config([
        'services.google.analytics_enabled' => true,
        'services.google.tag_manager_id' => 'GTM-TPQ229H9',
        'services.google.analytics_id' => 'G-2P5DDJGQH3',
    ]);

    $html = $this->get('/fa')->assertOk()->getContent();

    expect($html)
        ->toContain("'dataLayer',\"GTM-TPQ229H9\"")
        ->toContain('googletagmanager.com/ns.html?id=GTM-TPQ229H9')
        ->toContain('googletagmanager.com/gtag/js?id=G-2P5DDJGQH3')
        ->toContain("gtag('config', \"G-2P5DDJGQH3\")");

    // GTM head snippet before </head>, noscript iframe inside <body>
    expect(strpos($html, 'gtm.js'))->toBeLessThan(strpos($html, '</head>'))
        ->and(strpos($html, 'ns.html?id='))->toBeGreaterThan(strpos($html, '<body'));
});

it('renders no tracking snippets when analytics is disabled', function (): void {
    config(['services.google.analytics_enabled' => false]);

    expect($this->get('/fa')->assertOk()->getContent())
        ->not->toContain('googletagmanager.com');
});

it('drops the gtag snippet when the GA4 id is empty', function (): void {
    config([
        'services.google.analytics_enabled' => true,
        'services.google.analytics_id' => null,
    ]);

    expect($this->get('/fa')->assertOk()->getContent())
        ->toContain('gtm.js')
        ->not->toContain('gtag/js');
});
