<?php

declare(strict_types=1);

namespace App\Models\Translations;

use App\Models\ContentRevision;
use Illuminate\Database\Eloquent\Model;

/**
 * Translation row. One per (parent, locale) — enforced by a composite unique
 * index in the migration.
 */
final class PageTranslation extends Model
{
    protected static function booted(): void
    {
        // WordPress-style revisions: snapshot the text before each edit.
        self::updating(fn (self $t) => ContentRevision::capture($t));
    }

    protected $fillable = [
        'locale',
        'title',
        'subtitle',
        'description',
        'content',
        'seo_title',
        'seo_description',
    ];
}
