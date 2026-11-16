<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Attaches per-locale SEO metadata (seo_meta) to a content model.
 *
 * Eager-load `seoMeta` alongside translations in show queries; seoFor()
 * reads from the loaded relation when present and never falls back across
 * locales — another locale's title/canonical would be wrong for this URL.
 */
trait HasSeo
{
    public static function bootHasSeo(): void
    {
        static::deleted(function ($model): void {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return; // keep SEO data while soft-deleted
            }
            $model->seoMeta()->delete();
        });
    }

    public function seoMeta(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'seoable');
    }

    /** Active-locale row, for eager loading. */
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable')->where('locale', app()->getLocale());
    }

    public function seoFor(?string $locale = null): ?SeoMeta
    {
        $locale ??= app()->getLocale();

        $rows = $this->relationLoaded('seoMeta') ? $this->seoMeta : $this->seoMeta()->get();

        return $rows->firstWhere('locale', $locale);
    }

    /**
     * @param  array<string, array<string, mixed>>  $data  locale => fields
     * @param  array<string, array{seo_score: ?int, readability_score: ?int}>  $scores
     */
    public function saveSeo(array $data, array $scores = []): void
    {
        foreach ($data as $locale => $fields) {
            if (! array_key_exists($locale, config('locales.supported'))) {
                continue;
            }

            $fields = array_intersect_key($fields, array_flip(SeoMeta::EDITABLE));
            $fields += $scores[$locale] ?? [];

            $this->seoMeta()->updateOrCreate(['locale' => $locale], $fields);
        }

        $this->unsetRelation('seoMeta');
    }
}
