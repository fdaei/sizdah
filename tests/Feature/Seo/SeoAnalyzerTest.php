<?php

declare(strict_types=1);

use App\Services\Seo\SeoAnalyzer;

function statusOf(array $checks, string $id): ?string
{
    return collect($checks)->firstWhere('id', $id)['status'] ?? null;
}

it('normalises Arabic letters, diacritics and ZWNJ in Persian text', function (): void {
    expect(SeoAnalyzer::normalize('بازاريابي‌ديجيتال'))->toBe('بازاریابی دیجیتال')
        ->and(SeoAnalyzer::normalize('كتابِ'))->toBe('کتاب');
});

it('finds the keyphrase in title, intro, meta and slug', function (): void {
    $r = SeoAnalyzer::analyze([
        'keyword' => 'بازاریابی دیجیتال',
        'title' => 'بازاریابی دیجیتال چیست',
        'description' => 'همه چیز درباره بازاریابی دیجیتال',
        'slug' => 'بازاریابی-دیجیتال',
        'content' => '<p>بازاریابی‌دیجیتال یعنی رشد.</p>',
    ])['seo']['checks'];

    expect(statusOf($r, 'keyphrase_title'))->toBe('good')
        ->and(statusOf($r, 'keyphrase_intro'))->toBe('good')
        ->and(statusOf($r, 'keyphrase_meta'))->toBe('good')
        ->and(statusOf($r, 'keyphrase_slug'))->toBe('good');
});

it('flags missing keyphrase, missing alt text and keyword stuffing', function (): void {
    expect(statusOf(SeoAnalyzer::analyze(['content' => '<p>x</p>'])['seo']['checks'], 'keyphrase'))->toBe('bad');

    $r = SeoAnalyzer::analyze([
        'keyword' => 'سئو',
        'content' => '<p>'.str_repeat('سئو ', 50).'</p><img src="a.jpg">',
    ])['seo']['checks'];

    expect(statusOf($r, 'images'))->toBe('bad')
        ->and(statusOf($r, 'keyphrase_density'))->toBe('bad');
});

it('counts internal and outbound links', function (): void {
    config(['app.url' => 'https://sizdahmarketing.com']);
    $r = SeoAnalyzer::analyze([
        'content' => '<p><a href="/fa/about">a</a> <a href="https://sizdahmarketing.com/fa">b</a> <a href="https://x.com">c</a></p>',
    ])['seo']['checks'];

    expect(collect($r)->firstWhere('id', 'internal_links')['text'])->toContain('2')
        ->and(statusOf($r, 'outbound_links'))->toBe('good');
});

it('scores readability with Persian transition words and long sentences', function (): void {
    $good = str_repeat('<p>این جمله کوتاه است. بنابراین خواندن آن ساده است. همچنین مفید است.</p>', 6);
    $long = '<p>'.str_repeat('این یک جمله بسیار بسیار طولانی است که هیچ پایانی ندارد و ادامه پیدا می‌کند و باز هم ادامه دارد ', 6).'.</p>';

    $g = SeoAnalyzer::analyze(['content' => $good])['readability'];
    $l = SeoAnalyzer::analyze(['content' => $long])['readability'];

    expect(statusOf($g['checks'], 'transition_words'))->toBe('good')
        ->and(statusOf($l['checks'], 'sentence_length'))->toBe('bad')
        ->and($g['score'])->toBeGreaterThan($l['score']);
});

it('demands more text for cornerstone content', function (): void {
    $content = '<p>'.str_repeat('کلمه ', 400).'</p>';

    expect(statusOf(SeoAnalyzer::analyze(['content' => $content])['seo']['checks'], 'text_length'))->toBe('good')
        ->and(statusOf(SeoAnalyzer::analyze(['content' => $content, 'cornerstone' => true])['seo']['checks'], 'text_length'))->toBe('bad');
});

it('flags an H1 inside the body (Yoast single title)', function (): void {
    expect(statusOf(SeoAnalyzer::analyze(['content' => '<h2>a</h2><p>b</p>'])['seo']['checks'], 'single_title'))->toBe('good')
        ->and(statusOf(SeoAnalyzer::analyze(['content' => '<h1>a</h1><p>b</p>'])['seo']['checks'], 'single_title'))->toBe('bad');
});
