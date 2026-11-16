<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Services\Seo\SchemaNodes;
use App\Support\SiteSettings;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function graphTypes(string $html): array
{
    return array_map(fn ($t) => is_array($t) ? implode('+', $t) : $t, array_column(jsonLd($html)['@graph'], '@type'));
}

it('adds FAQPage from active FAQs on the home page', function (): void {
    seoPage('home', 'خانه');
    $faq = Faq::query()->create(['is_active' => true, 'sort_order' => 1]);
    $faq->setTranslations(['fa' => ['question' => 'هزینه چقدر است؟', 'answer' => 'بستگی به پروژه دارد.']]);

    $graph = jsonLd($this->get('/fa')->getContent())['@graph'];
    $faqPage = collect($graph)->firstWhere('@type', 'FAQPage');

    expect($faqPage['mainEntity'][0])->toMatchArray([
        '@type' => 'Question',
        'name' => 'هزینه چقدر است؟',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'بستگی به پروژه دارد.'],
    ]);
});

it('adds Person nodes for the team on the about page, linked to the organization', function (): void {
    seoPage('about', 'درباره ما');
    $m = TeamMember::query()->create(['is_active' => true, 'sort_order' => 1]);
    $m->setTranslations(['fa' => ['name' => 'سارا', 'role' => 'مدیر هنری']]);

    $person = collect(jsonLd($this->get('/fa/about')->getContent())['@graph'])->firstWhere('@type', 'Person');

    expect($person['name'])->toBe('سارا')
        ->and($person['jobTitle'])->toBe('مدیر هنری')
        ->and($person['worksFor']['@id'])->toEndWith('/#organization');
});

it('emits testimonial Reviews only when the toggle is on, never AggregateRating', function (): void {
    seoPage('home', 'خانه');
    $t = Testimonial::query()->create(['is_active' => true, 'sort_order' => 1]);
    $t->setTranslations(['fa' => ['author_name' => 'علی', 'quote' => 'عالی بود']]);

    expect(graphTypes($this->get('/fa')->getContent()))->not->toContain('Review');

    Setting::updateOrCreate(['key' => 'seo_schema_reviews'], ['group' => 'seo', 'value' => ['value' => true], 'is_translatable' => false]);
    SiteSettings::flush();

    $types = graphTypes($this->get('/fa')->getContent());
    expect($types)->toContain('Review')->not->toContain('AggregateRating');
});

it('detects YouTube and Aparat embeds as VideoObjects', function (): void {
    $html = '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe><iframe src="https://www.aparat.com/video/video/embed/videohash/abc12/vt/frame"></iframe>';

    $videos = SchemaNodes::videos($html, 'https://x.test/a', 'عنوان', null, 'https://x.test/c.jpg', '2026-01-01T00:00:00+00:00');

    expect($videos)->toHaveCount(2)
        ->and($videos[0]['thumbnailUrl'])->toBe('https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg')
        ->and($videos[1]['embedUrl'])->toContain('videohash/abc12')
        ->and($videos[1]['thumbnailUrl'])->toBe('https://x.test/c.jpg')
        ->and($videos[0]['uploadDate'])->toBe('2026-01-01T00:00:00+00:00');

    // No thumbnail available for Aparat -> skipped rather than emitting an invalid VideoObject.
    expect(SchemaNodes::videos($html, 'u', 'n', null, null, null))->toHaveCount(1);
});

it('adds VideoObject to an article page with an embedded video', function (): void {
    $post = Post::factory()->create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay(), 'cover_path' => 'posts/c.webp']);
    $post->setTranslations(['fa' => ['title' => 'ویدیو', 'slug' => 'ویدیو', 'content' => '<p>x</p><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe>']]);

    expect(graphTypes($this->get('/fa/insights/ویدیو')->getContent()))->toContain('BlogPosting', 'VideoObject');
});
