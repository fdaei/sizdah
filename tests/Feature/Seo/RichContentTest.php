<?php

declare(strict_types=1);

use App\Filament\Support\Editor\Blocks\AparatBlock;
use App\Support\RichContent;

it('passes legacy HTML through untouched', function (): void {
    $html = '<h2>عنوان</h2><p>متن [[lead_magnet]]</p><h5>ریز</h5>';

    expect(RichContent::render($html))->toBe($html)
        ->and(RichContent::render(null))->toBe('');
});

it('extracts the Aparat hash from URLs and rejects junk', function (string $in, ?string $hash): void {
    expect(AparatBlock::hash($in))->toBe($hash);
})->with([
    ['https://www.aparat.com/v/abc123', 'abc123'],
    ['https://www.aparat.com/video/video/embed/videohash/Xy9Z/vt/frame', 'Xy9Z'],
    ['abc123', 'abc123'],
    ['https://evil.com/<script>', null],
]);

it('renders an Aparat block as a lazy iframe', function (): void {
    $html = '<p>قبل</p><tiptap-block data-type="aparat" data-data="'.e(json_encode(['url' => 'https://www.aparat.com/v/abc123', 'title' => 'ویدیو'])).'"></tiptap-block>';

    expect(RichContent::render($html))
        ->toContain('https://www.aparat.com/video/video/embed/videohash/abc123/vt/frame')
        ->toContain('loading="lazy"')
        ->toContain('<p>قبل</p>');
});

it('recovers plain text from legacy editor documents', function (): void {
    $document = json_encode(['type' => 'doc', 'content' => [
        ['type' => 'paragraph', 'content' => [
            ['type' => 'text', 'text' => 'فکر می‌کنیم،'],
            ['type' => 'hardBreak'],
            ['type' => 'text', 'text' => 'می‌سازیم'],
        ]],
        ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'متن دوم']]],
    ]]);

    expect(RichContent::plain($document))->toBe("فکر می‌کنیم،\nمی‌سازیم\nمتن دوم")
        ->and(RichContent::plain('متن ساده'))->toBe('متن ساده')
        ->and(RichContent::plain('{"other":true}'))->toBe('{"other":true}');
});
