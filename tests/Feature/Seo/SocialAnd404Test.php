<?php

declare(strict_types=1);

use App\Models\NotFoundLog;
use App\Services\Seo\OgImageCheck;

it('accepts a 1200×630 share image and warns about small or mis-proportioned ones', function (): void {
    expect(OgImageCheck::warning(1200, 630))->toBeNull()
        ->and(OgImageCheck::warning(2400, 1260))->toBeNull()
        ->and(OgImageCheck::warning(600, 315))->toContain('کوچک‌تر')
        ->and(OgImageCheck::warning(1200, 1200))->toContain('نسبت')
        ->and(OgImageCheck::warning(null, null))->toBeNull();
});

it('reads real image dimensions from disk', function (): void {
    $path = tempnam(sys_get_temp_dir(), 'og').'.png';
    imagepng(imagecreatetruecolor(1200, 630), $path);

    expect(OgImageCheck::dimensions($path))->toBe([1200, 630])
        ->and(OgImageCheck::dimensions('/nope.png'))->toBe([null, null]);
})->skip(! function_exists('imagepng'), 'gd not installed');

it('flags crawler 404s as bots and real browsers as humans', function (): void {
    $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1)')->get('/fa/missing-bot-page');
    $this->withHeader('User-Agent', 'Mozilla/5.0 (X11; Linux x86_64) Chrome/130.0 Safari/537.36')->get('/fa/missing-human-page');

    expect(NotFoundLog::query()->where('path', 'like', '%missing-bot-page')->value('is_bot'))->toBeTrue()
        ->and(NotFoundLog::query()->where('path', 'like', '%missing-human-page')->value('is_bot'))->toBeFalse();
});
