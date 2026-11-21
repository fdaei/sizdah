<?php

declare(strict_types=1);

namespace App\Models\Translations;

use App\Services\Redirects\SlugRedirector;
use App\Support\NavigationBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Translation row. One per (parent, locale) — enforced by a composite unique
 * index in the migration.
 */
final class PostCategoryTranslation extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        self::updating(fn (self $t) => SlugRedirector::handle($t));
        // Names/slugs feed generated category submenus.
        self::saved(fn () => NavigationBuilder::flush());
        self::deleted(fn () => NavigationBuilder::flush());
    }
}
