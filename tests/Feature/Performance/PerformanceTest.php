<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Filament\Pages\ManagePerformance;
use App\Models\Client;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use App\Services\ContentTransformer;
use App\Services\Performance\HtmlMinifier;
use App\Support\Performance;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

it('does not cache pages rendered from changes that are rolled back', function (): void {
    perf(['perf_page_cache' => true]);
    $page = Page::query()->first();
    $page->setTranslations(['fa' => ['title' => 'عنوان اصلی']]);
    $this->get('/fa')->assertSee('عنوان اصلی');

    DB::beginTransaction();
    $page->setTranslations(['fa' => ['title' => 'عنوان موقت']]);
    $this->get('/fa')->assertSee('عنوان موقت')->assertHeaderMissing('X-Page-Cache');
    DB::rollBack();

    $this->get('/fa')->assertSee('عنوان اصلی')->assertDontSee('عنوان موقت');
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

it('renders Persian UI on the server without sending a translation dictionary', function (): void {
    $this->get('/fa')->assertOk()->assertViewHas('page', fn ($page) => ! isset($page['props']['translations']) && $page['props']['locale']['current'] === 'fa');
});

it('loads only listing fields for article translations', function (): void {
    $post = Post::factory()->create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay()]);
    $post->setTranslations(['fa' => ['title' => 'عنوان آزمایشی', 'slug' => 'test-title', 'excerpt' => 'خلاصه', 'content' => '<p>متن بلند مقاله</p>']]);

    $loaded = Post::query()->forListing()->whereKey($post->id)->firstOrFail();
    $translation = $loaded->translations->firstWhere('locale', 'fa');
    expect($translation->getAttribute('title'))->toBe('عنوان آزمایشی')
        ->and($translation->getAttribute('excerpt'))->toBe('خلاصه')
        ->and(array_key_exists('content', $translation->getAttributes()))->toBeFalse();
});

it('discovers the native enhancement module and responsive hero in the initial HTML', function (): void {
    $vite = app(Vite::class);
    $html = $this->get('/fa')->assertOk()->getContent();
    $module = $vite->asset('resources/js/site.ts');
    $art = json_decode(file_get_contents(resource_path('views/site/assets.json')), true);
    expect($html)->toContain($module)
        ->toContain('as="image" href="'.$art['sizdah/home/hero-bg-mobile.webp'].'" media="(max-width:767px)" fetchpriority="high"')
        ->toContain('Peyda-SemiBold.woff2" as="font"')
        ->not->toContain('data-page=', 'window.Ziggy', '/css/filament/', '/js/filament/', '/livewire/');
});

it('preloads CMS hero overrides with the same responsive precedence as Vue', function (): void {
    foreach (['top_home_mobile' => 'mobile.avif', 'top_home_tablet' => 'tablet.avif', 'top_home_desktop' => 'desktop.avif'] as $key => $file) {
        Setting::query()->create(['key' => 'image_'.$key, 'group' => 'images', 'is_translatable' => true, 'value' => ['fa' => $file]]);
    }
    $html = $this->get('/fa')->assertOk()->getContent();
    foreach (['mobile', 'tablet', 'desktop'] as $size) {
        $url = url(Storage::disk('public')->url($size.'.avif'));
        expect($html)->toContain('as="image" href="'.$url.'"');
    }
    $this->get('/fa')->assertHeader('X-Page-Cache', 'HIT');
});

it('does not send broken client upload URLs to the browser', function (): void {
    Storage::fake('public');
    $client = Client::query()->create(['logo_path' => 'clients/missing.svg']);
    $client->setTranslations(['fa' => ['name' => 'کلینیک پارس']]);

    expect(ContentTransformer::client($client)['logo'])->toBeNull();
    Storage::disk('public')->put('clients/present.svg', '<svg/>');
    $client->logo_path = 'clients/present.svg';
    expect(ContentTransformer::client($client)['logo'])->toEndWith('/storage/clients/present.svg');
    $client->logo_path = 'https://cdn.example.com/client.svg';
    expect(ContentTransformer::client($client)['logo'])->toBe('https://cdn.example.com/client.svg');
});
