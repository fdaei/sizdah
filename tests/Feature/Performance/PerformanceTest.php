<?php

declare(strict_types=1);

use App\Filament\Pages\ManagePerformance;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Services\Performance\HtmlMinifier;
use App\Support\Performance;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
    Page::factory()->create(['key' => 'home']);
});

function perf(array $values): void
{
    foreach ($values as $key => $value) {
        Setting::query()->updateOrCreate(['key' => $key], ['group' => 'performance', 'is_translatable' => false, 'value' => ['value' => $value]]);
    }
}

it('minifies HTML without touching pre, textarea, script, style or Vue SSR anchors', function (): void {
    $html = "<div>\n   <!-- note -->\n  <p>a</p>\n\n<!--[--><span>x</span><!--]-->\n<pre>  keep\n  this </pre><textarea>  t  </textarea>"
        .'<script>  var  a = 1; // <!-- c --></script><style> a  {  } </style></div>';

    $out = HtmlMinifier::minify($html);

    expect($out)
        ->not->toContain('note')
        ->toContain('<!--[-->')->toContain('<!--]-->')
        ->toContain("<pre>  keep\n  this </pre>")
        ->toContain('<textarea>  t  </textarea>')
        ->toContain('<script>  var  a = 1; // <!-- c --></script>')
        ->toContain('<style> a  {  } </style>')
        ->not->toContain("\n\n");
});

it('leaves HTML untouched when minify is off and minifies when on', function (): void {
    $plain = $this->get('/fa')->getContent();

    perf(['perf_minify_html' => true]);
    $min = $this->get('/fa')->getContent();

    expect(strlen($min))->toBeLessThan(strlen($plain))
        ->and($min)->toContain('application/ld+json');
});

it('serves anonymous pages from the page cache and busts it when content changes', function (): void {
    perf(['perf_page_cache' => true]);

    $this->get('/fa')->assertHeader('X-Page-Cache', 'MISS');
    $this->get('/fa')->assertHeader('X-Page-Cache', 'HIT');

    Page::query()->first()->touch();

    $this->get('/fa')->assertHeader('X-Page-Cache', 'MISS');
});

it('never shares a CSRF token through the page cache', function (): void {
    perf(['perf_page_cache' => true]);

    $first = $this->get('/fa')->getContent();
    preg_match('#name="csrf-token" content="([^"]+)"#', $first, $a);

    $this->flushSession();
    $second = $this->get('/fa');
    preg_match('#name="csrf-token" content="([^"]+)"#', $second->getContent(), $b);

    $second->assertHeader('X-Page-Cache', 'HIT');
    expect($b[1])->not->toBe($a[1])
        ->and($second->getContent())->not->toContain('__PAGE_CACHE_CSRF__');
});

it('does not cache for logged-in users', function (): void {
    perf(['perf_page_cache' => true]);

    $this->actingAs(User::factory()->create())->get('/fa')->assertHeaderMissing('X-Page-Cache');
});

it('renders admin resource hints and drops unsafe URLs', function (): void {
    perf([
        'perf_preconnect' => ['https://cdn.example.com', 'javascript:alert(1)'],
        'perf_preload' => [['url' => '/img/hero.avif', 'as' => 'image', 'type' => 'image/avif']],
        'perf_prefetch' => ['/fa/contact'],
    ]);

    expect($this->get('/fa')->getContent())
        ->toContain('<link rel="preconnect" href="https://cdn.example.com" crossorigin>')
        ->toContain('<link rel="preload" href="/img/hero.avif" as="image" type="image/avif"')
        ->toContain('<link rel="prefetch" href="/fa/contact">')
        ->not->toContain('javascript:alert');
});

it('lets an admin save performance settings and purge the cache', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)->test(ManagePerformance::class)
        ->set('data.perf_minify_html', true)
        ->call('save')
        ->assertHasNoErrors()
        ->callAction('purge');

    expect(Performance::minifyHtml())->toBeTrue();
});
