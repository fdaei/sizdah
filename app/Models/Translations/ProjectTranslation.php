<?php

declare(strict_types=1);

namespace App\Models\Translations;

use App\Services\Redirects\SlugRedirector;
use Illuminate\Database\Eloquent\Model;

/**
 * Translation row. One per (project, locale).
 */
final class ProjectTranslation extends Model
{
    protected static function booted(): void
    {
        // Auto 301 from the old URL when a published item's slug changes.
        self::updating(fn (self $t) => SlugRedirector::handle($t));
    }

    protected $fillable = [
        'locale',
        'title',
        'slug',
        'subtitle',
        'excerpt',
        'challenge',
        'challenge_points',
        'results_summary',
        'seo_title',
        'seo_description',
        'cover_alt',
    ];

    protected function casts(): array
    {
        return [
            'challenge_points' => 'array',
        ];
    }
}
