<?php

declare(strict_types=1);

namespace App\Models\Translations;

use App\Services\Redirects\SlugRedirector;
use Illuminate\Database\Eloquent\Model;

/**
 * Translation row. One per (post, locale).
 */
final class PostTranslation extends Model
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
        'content',
        'seo_title',
        'seo_description',
        'cover_alt',
    ];
}
