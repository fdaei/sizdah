<?php

declare(strict_types=1);

use App\Enums\SectionType;
use App\Models\Page;
use App\Models\Post;
use Inertia\Ssr\Gateway;

function phpContentXPath(string $html): DOMXPath
{
    $document = new DOMDocument;
    $previous = libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8">'.$html);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);

    return new DOMXPath($document);
}

it('renders every public page as styled native HTML without a framework payload', function (string $key, string $path): void {
    $page = Page::factory()->create(['key' => $key]);
    $page->setTranslations(['fa' => ['title' => 'عنوان خواندنی', 'content' => '<p>متن حقوقی کامل</p>']]);
    $html = $this->get($path)->assertOk()->getContent();
    $xpath = phpContentXPath($html);
    expect($xpath->query('//main[@id="main"]')->length)->toBe(1)
        ->and($xpath->query('//main//h1')->length)->toBe(1)
        ->and($xpath->evaluate('string(//main//h1)'))->toBe('عنوان خواندنی')
        ->and($html)->not->toContain('data-page=')->not->toContain('data-php-content')->not->toContain('const Ziggy=')
        ->and($xpath->evaluate('string(//html/@lang)'))->toBe('fa-IR')
        ->and($xpath->evaluate('string(//html/@dir)'))->toBe('rtl');
})->with([
    ['home', '/fa'], ['about', '/fa/about'], ['services', '/fa/services'],
    ['work', '/fa/work'], ['insights', '/fa/insights'], ['contact', '/fa/contact'],
    ['privacy-policy', '/fa/privacy-policy'], ['terms', '/fa/terms'],
]);

it('renders the actual three-line home hero and CMS colours without JavaScript', function (): void {
    $page = Page::factory()->create(['key' => 'home']);
    $hero = $page->sections()->create(['type' => SectionType::Hero, 'is_visible' => true, 'title_color' => '#123456']);
    $hero->setTranslations(['fa' => ['title' => 'خط اول', 'subtitle' => 'خط دوم', 'description' => 'خط سوم', 'content' => 'توضیح هیرو', 'primary_cta_label' => 'گفتگو', 'primary_cta_url' => '/fa/contact']]);
    $html = $this->get('/fa')->assertOk()->getContent();
    $xpath = phpContentXPath($html);
    expect($xpath->query('//section[contains(@class, "home-hero")]//h1/span')->length)->toBe(3)
        ->and($xpath->evaluate('string(//h1)'))->toContain('خط اول', 'خط دوم', 'خط سوم')
        ->and($html)->toContain('color:#123456', 'توضیح هیرو', 'href="/fa/contact"');
});

it('keeps article body, table of contents and schema in the initial response', function (): void {
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'مقاله اصلی', 'slug' => 'native-article', 'content' => '<h2>سرفصل</h2><p>متن کامل</p>']]);
    $html = $this->get('/fa/insights/native-article')->assertOk()->getContent();
    $xpath = phpContentXPath($html);
    expect($xpath->evaluate('string(//main//h1)'))->toContain('مقاله اصلی')
        ->and($xpath->evaluate('string(//main)'))->toContain('سرفصل', 'متن کامل')
        ->and($xpath->query('//script[@type="application/ld+json"]')->length)->toBeGreaterThan(0);
});

it('never contacts a Node renderer even if the old SSR flag is enabled', function (): void {
    config(['inertia.ssr.enabled' => true]);
    $this->mock(Gateway::class)->shouldNotReceive('dispatch');
    Page::factory()->create(['key' => 'home']);
    $this->get('/fa')->assertOk()->assertSee('site-shell');
});

it('uses Persian only and omits language dictionaries and hreflang controls', function (): void {
    Page::factory()->create(['key' => 'home']);
    $response = $this->get('/fa', ['Accept-Language' => 'en-US,en']);
    $response->assertOk()->assertHeader('Content-Language', 'fa-IR')
        ->assertViewHas('page', fn ($page) => ! isset($page['props']['alternates'], $page['props']['translations'], $page['props']['locale']['supported']));
    expect($response->getContent())->not->toContain('hreflang=')->not->toContain('Components/LanguageSwitcher');
    expect(config('locales.admin'))->toBe(['fa']);
});

it('returns native noindex error pages and preserves the Persian locale', function (): void {
    $html = $this->get('/fa/missing-native-page')->assertNotFound()->getContent();
    expect(phpContentXPath($html)->query('//main//h1')->length)->toBe(1)
        ->and($html)->toContain('noindex, follow', 'lang="fa-IR"');
});

it('upgrades a browser tab from the previous Vue release with a full visit', function (): void {
    Page::factory()->create(['key' => 'home']);
    $this->get('/fa', ['X-Inertia' => 'true'])->assertStatus(409)->assertHeader('X-Inertia-Location', url('/fa'));
});
