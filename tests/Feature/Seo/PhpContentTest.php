<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Enums\SectionType;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Inertia\Ssr\Gateway;
use Inertia\Ssr\Response;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function phpContentXPath(string $html): DOMXPath
{
    $document = new DOMDocument;
    $previous = libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8">'.$html);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);

    return new DOMXPath($document);
}

it('renders public page headings and navigation as real HTML without Node', function (string $key, string $path): void {
    $page = Page::factory()->create(['key' => $key]);
    $page->setTranslations(['fa' => ['title' => 'عنوان خواندنی', 'content' => '<p>متن حقوقی کامل</p>']]);

    $html = $this->get($path)->assertOk()->getContent();
    $xpath = phpContentXPath($html);

    expect($xpath->query('//*[@id="app" and @data-php-content]')->length)->toBe(1)
        ->and($xpath->query('//main[@id="main"]//h1')->length)->toBe(1)
        ->and($xpath->evaluate('string(//main//h1)'))->toBe('عنوان خواندنی')
        ->and($xpath->query('//header//a[contains(@href, "/fa/insights")]')->length)->toBe(1)
        ->and($xpath->query('//footer//a[contains(@href, "/fa/privacy-policy")]')->length)->toBe(1)
        ->and($xpath->evaluate('string(//header)'))->toContain('مقالات', 'نمونه‌کارها')
        ->and($xpath->evaluate('string(//footer)'))->toContain('حریم خصوصی', 'شرایط استفاده');
})->with([
    ['home', '/fa'], ['about', '/fa/about'], ['services', '/fa/services'],
    ['work', '/fa/work'], ['insights', '/fa/insights'], ['contact', '/fa/contact'],
    ['privacy-policy', '/fa/privacy-policy'], ['terms', '/fa/terms'],
]);

it('renders visible editorial sections and omits hidden ones', function (): void {
    $page = Page::factory()->create(['key' => 'home']);
    $hero = $page->sections()->create(['type' => SectionType::Hero, 'is_visible' => true]);
    $hero->setTranslations(['fa' => ['title' => 'عنوان اصلی', 'description' => 'توضیح بخش اصلی']]);
    $story = $page->sections()->create(['type' => SectionType::Story, 'is_visible' => true]);
    $story->setTranslations(['fa' => ['title' => 'داستان ما', 'content' => '<p>متن <strong>داستان</strong></p>']]);
    $hidden = $page->sections()->create(['type' => SectionType::WhyUs, 'is_visible' => false]);
    $hidden->setTranslations(['fa' => ['title' => 'بخش مخفی']]);

    $xpath = phpContentXPath($this->get('/fa')->assertOk()->getContent());
    expect($xpath->evaluate('string(//main//h1)'))->toBe('عنوان اصلی')
        ->and($xpath->evaluate('string(//main)'))->toContain('توضیح بخش اصلی', 'داستان ما')->not->toContain('بخش مخفی')
        ->and($xpath->evaluate('string(//main//strong)'))->toBe('داستان');
});

it('renders article body, headings and related links outside the Inertia JSON', function (): void {
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => [
        'title' => 'مقاله اصلی', 'slug' => 'php-article', 'excerpt' => 'خلاصه مقاله',
        'content' => '<h2>سرفصل مقاله</h2><p>متن کامل و قابل ایندکس</p><a href="/fa/contact">تماس</a>',
    ]]);

    $xpath = phpContentXPath($this->get('/fa/insights/php-article')->assertOk()->getContent());

    expect($xpath->evaluate('string(//main//h1)'))->toBe('مقاله اصلی')
        ->and($xpath->evaluate('string(//main//article)'))->toContain('خلاصه مقاله', 'متن کامل و قابل ایندکس')
        ->and($xpath->query('//main//h2[text()="سرفصل مقاله"]')->length)->toBe(1)
        ->and($xpath->query('//main//a[@href="/fa/contact"]')->length)->toBe(1);
});

it('renders project detail and service features without JavaScript', function (): void {
    $project = Project::factory()->published()->create();
    $project->setTranslations(['fa' => ['title' => 'پروژه ما', 'slug' => 'php-project', 'challenge' => 'چالش واقعی', 'results_summary' => 'نتیجه واقعی']]);
    $service = Service::create(['status' => PublicationStatus::Published, 'published_at' => now(), 'sort_order' => 1]);
    $service->setTranslations(['fa' => ['title' => 'خدمت ما', 'slug' => 'php-service', 'description' => 'شرح خدمت', 'features' => ['ویژگی اصلی']]]);

    $projectXPath = phpContentXPath($this->get('/fa/work/php-project')->assertOk()->getContent());
    $serviceXPath = phpContentXPath($this->get('/fa/services/php-service')->assertOk()->getContent());

    expect($projectXPath->evaluate('string(//main)'))->toContain('پروژه ما', 'چالش واقعی', 'نتیجه واقعی')
        ->and($serviceXPath->evaluate('string(//main)'))->toContain('خدمت ما', 'شرح خدمت', 'ویژگی اصلی');
});

it('renders crawlable listing and pagination links and excludes unpublished articles', function (): void {
    for ($i = 1; $i <= 10; $i++) {
        Post::factory()->published()->create()->setTranslations(['fa' => ['title' => 'مقاله '.$i, 'slug' => 'article-'.$i]]);
    }
    Post::factory()->create()->setTranslations(['fa' => ['title' => 'پیش‌نویس مخفی', 'slug' => 'hidden-draft']]);

    $xpath = phpContentXPath($this->get('/fa/insights')->assertOk()->getContent());
    expect($xpath->query('//main//article//a[contains(@href, "/fa/insights/article-")]')->length)->toBe(9)
        ->and($xpath->query('//main//a[@rel="next" and contains(@href, "page=2")]')->length)->toBe(1)
        ->and($xpath->evaluate('string(//main)'))->not->toContain('پیش‌نویس مخفی');

    $this->get('/fa/insights/hidden-draft')->assertNotFound();
});

it('serves the same visible content to crawlers and people and keeps Inertia visits as JSON', function (): void {
    $post = Post::factory()->published()->create();
    $post->setTranslations(['fa' => ['title' => 'محتوای مشترک', 'slug' => 'same-content', 'content' => '<p>برای همه</p>']]);
    $humanHtml = $this->get('/fa/insights/same-content', ['User-Agent' => 'Mozilla/5.0'])->getContent();
    $human = phpContentXPath($humanHtml);
    $crawler = phpContentXPath($this->get('/fa/insights/same-content', ['User-Agent' => 'Googlebot'])->getContent());

    expect($human->evaluate('string(//main)'))->toBe($crawler->evaluate('string(//main)'));

    $data = json_decode($human->evaluate('string(//*[@id="app"]/@data-page)'), true);
    $this->get('/fa/insights/same-content', ['X-Inertia' => 'true', 'X-Inertia-Version' => $data['version']])
        ->assertOk()->assertHeader('X-Inertia', 'true')
        ->assertJsonPath('component', 'Insights/Show')
        ->assertJsonPath('props.post.content', '<p>برای همه</p>');
});

it('escapes plain text and keeps missing pages as readable noindex 404s', function (): void {
    $page = Page::factory()->create(['key' => 'about']);
    $page->setTranslations(['fa' => ['title' => '<img src=x onerror=alert(1)>']]);
    $xpath = phpContentXPath($this->get('/fa/about')->assertOk()->getContent());

    expect($xpath->evaluate('string(//main//h1)'))->toBe('<img src=x onerror=alert(1)>')
        ->and($xpath->query('//main//h1/img')->length)->toBe(0);

    $html = $this->get('/fa/unknown-page')->assertNotFound()->getContent();
    expect(phpContentXPath($html)->query('//main//h1')->length)->toBe(1)
        ->and($html)->toContain('content="noindex, follow"');
});

it('supports the Inertia script element transport without exposing JSON as content', function (): void {
    config(['inertia.use_script_element_for_initial_page' => true]);
    $page = Page::factory()->create(['key' => 'about']);
    $page->setTranslations(['fa' => ['title' => 'عنوان انتقال']]);

    $xpath = phpContentXPath($this->get('/fa/about')->assertOk()->getContent());
    $data = json_decode($xpath->evaluate('string(//script[@data-page="app"])'), true);

    expect($data['component'])->toBe('About')
        ->and($xpath->evaluate('string(//main//h1)'))->toBe('عنوان انتقال');
});

it('keeps the PHP reading view when the optional renderer is unavailable', function (): void {
    config(['inertia.ssr.enabled' => true]);
    $this->mock(Gateway::class)
        ->shouldReceive('dispatch')->once()->andReturnNull();

    $xpath = phpContentXPath($this->get('/fa')->assertOk()->getContent());

    expect($xpath->query('//*[@id="app" and @data-php-content]//main')->length)->toBe(1);
});

it('preserves optional Vue SSR output without also rendering the PHP reading view', function (): void {
    config(['inertia.ssr.enabled' => true]);
    $this->mock(Gateway::class)->shouldReceive('dispatch')->once()->andReturn(
        new Response('', '<div id="app"><main id="main"><h1>Vue SSR</h1></main></div>'),
    );

    $xpath = phpContentXPath($this->get('/fa')->assertOk()->getContent());

    expect($xpath->evaluate('string(//main//h1)'))->toBe('Vue SSR')
        ->and($xpath->query('//*[@data-php-content]')->length)->toBe(0);
});
