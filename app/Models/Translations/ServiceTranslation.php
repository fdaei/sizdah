<?php

declare(strict_types=1);

namespace App\Models\Translations;

use App\Services\Redirects\SlugRedirector;
use Illuminate\Database\Eloquent\Model;

/**
 * Translation row. One per (service, locale).
 * `features` is the bullet list shown beside each service section.
 */
final class ServiceTranslation extends Model
{
    protected static function booted(): void
    {
        self::updating(fn (self $translation) => SlugRedirector::handle($translation));
    }

    protected $fillable = [
        'locale',
        'title',
        'slug',
        'description',
        'features',
        'image_alt',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
        ];
    }
}
