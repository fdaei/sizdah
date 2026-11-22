<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\HasLocalisedSlugs;
use App\Models\Translations\IndustryTranslation;
use App\Traits\HasSeo;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Project industry tag. Figma: 1362:7211 card tag, 1323:7576 meta row.
 */
final class Industry extends Model implements HasLocalisedSlugs
{
    use HasFactory;
    use HasSeo;
    use HasTranslations;

    protected $fillable = ['sort_order'];

    /** @var array<int, string> */
    protected array $translatable = ['name', 'slug'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function translationModel(): string
    {
        return IndustryTranslation::class;
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->newQuery()
            ->whereHas('translations', fn ($q) => $q->where('locale', app()->getLocale())->where('slug', (string) $value))
            ->withTranslations()->with('seoMeta')->first();
    }

    public function slugForLocale(string $locale): string
    {
        return (string) ($this->getTranslation('slug', $locale) ?? $this->getTranslation('slug', config('locales.fallback')));
    }

    public function url(?string $locale = null, bool $absolute = true): string
    {
        $locale ??= app()->getLocale();

        return route('work.industry', ['locale' => $locale, 'industry' => $this->slugForLocale($locale)], $absolute);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
