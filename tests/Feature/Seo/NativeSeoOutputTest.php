<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Enums\SectionType;
use App\Models\Page;
use App\Models\Service;

it('renders the full home heading and SEO metadata for visitors and speed-test crawlers', function (string $agent): void {
    $page = Page::factory()->create(['key' => 'home']);
    $page->setTranslations(['fa' => ['title' => 'آژانس سیزده', 'description' => 'توضیحات آژانس']]);
    $hero = $page->sections()->create(['type' => SectionType::Hero, 'is_visible' => true]);
    $hero->setTranslations(['fa' => ['title' => 'ما به مسیر', 'subtitle' => 'رشد و فروش', 'description' => 'کسب و کار شما کمک می‌کنیم']]);

    $html = $this->get('/fa', ['User-Agent' => $agent])->assertOk()->getContent();
    $dom = new DOMDocument;
    @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
    $xpath = new DOMXPath($dom);

    expect($xpath->query('//main')->length)->toBe(1)
        ->and($xpath->query('//h1')->length)->toBe(1)
        ->and($xpath->evaluate('string(//h1)'))->toContain('ما به مسیر', 'رشد و فروش', 'کسب و کار شما کمک می‌کنیم')
        ->and($xpath->query('//head/link[@rel="canonical"]')->length)->toBe(1)
        ->and($xpath->query('//head/link[@hreflang]')->length)->toBe(0)
        ->and($xpath->query('//head/meta[@name="description"]')->length)->toBe(1);
})->with(['Mozilla/5.0', 'Mozilla/5.0 Chrome-Lighthouse', 'Googlebot'])
    ->skip(env('SITE_RENDERER') === 'legacy', 'This regression targets the native PHP renderer.');

it('shows the original service image and one main landmark in native HTML', function (): void {
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now(), 'image_path' => 'services/editor-prepared.avif']);
    $service->setTranslations(['fa' => ['title' => 'برندینگ', 'slug' => 'branding', 'image_alt' => 'تصویر برندینگ']]);
    $html = $this->get('/fa/services/branding')->assertOk()->getContent();
    $dom = new DOMDocument;
    @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
    $xpath = new DOMXPath($dom);

    expect($xpath->query('//main')->length)->toBe(1)
        ->and($xpath->query('//img[@src="/storage/services/editor-prepared.avif" and @alt="تصویر برندینگ"]')->length)->toBe(1)
        ->and($xpath->evaluate('string(//head/meta[@property="og:image"]/@content)'))->toBe(url('/storage/services/editor-prepared.avif'));
})->skip(env('SITE_RENDERER') === 'legacy', 'This regression targets the native PHP renderer.');

it('reloads old browser tabs when a missing page is requested', function (): void {
    $this->get('/fa/work/missing-project', ['X-Inertia' => 'true'])
        ->assertStatus(409)->assertHeader('X-Inertia-Location', url('/fa/work/missing-project'));
})->skip(env('SITE_RENDERER') === 'legacy', 'This regression targets the native PHP renderer.');
