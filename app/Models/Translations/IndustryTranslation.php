<?php

declare(strict_types=1);

namespace App\Models\Translations;

use App\Services\Redirects\SlugRedirector;
use Illuminate\Database\Eloquent\Model;

/**
 * Translation row. One per (parent, locale) — enforced by a composite unique
 * index in the migration.
 */
final class IndustryTranslation extends Model
{
    protected static function booted(): void
    {
        self::updating(fn (self $translation) => SlugRedirector::handle($translation));
    }

    protected $fillable = [
        'locale',
        'name',
        'slug',
    ];
}
