<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Auto internal-linking rule: occurrences of `keyword` in article bodies of
 * `locale` are linked to `url` at render time (stored content is never changed).
 */
final class InternalLink extends Model
{
    private const CACHE_KEY = 'internal_links.';

    protected $fillable = ['locale', 'keyword', 'url', 'max_per_page', 'is_active'];

    protected function casts(): array
    {
        return ['max_per_page' => 'integer', 'is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        $flush = fn (self $link) => Cache::forget(self::CACHE_KEY.$link->locale);
        self::saved($flush);
        self::deleted($flush);
    }

    /**
     * Active rules for a locale, longest keyword first so "سئو محلی" wins over "سئو".
     *
     * @return list<array{keyword: string, url: string, max: int}>
     */
    public static function rulesFor(string $locale): array
    {
        return Cache::rememberForever(self::CACHE_KEY.$locale, fn (): array => self::query()
            ->where('locale', $locale)
            ->where('is_active', true)
            ->get(['keyword', 'url', 'max_per_page'])
            ->sortByDesc(fn (self $l): int => mb_strlen($l->keyword))
            ->map(fn (self $l): array => ['keyword' => $l->keyword, 'url' => $l->url, 'max' => max(1, $l->max_per_page)])
            ->values()
            ->all());
    }
}
