<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Translations\PostCategoryTranslation;
use App\Traits\HasSeo;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Blog category. Doubles as the listing filter chips (Figma 1363:7500).
 */
final class PostCategory extends Model
{
    use HasFactory;
    use HasSeo;
    use HasTranslations;

    protected $fillable = ['sort_order'];

    /** @var array<int, string> */
    protected array $translatable = ['name', 'slug', 'description'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function translationModel(): string
    {
        return PostCategoryTranslation::class;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $locale = app()->getLocale();

        return $this->newQuery()
            ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', (string) $value))
            ->withTranslations()
            ->with('seoMeta')
            ->first();
    }

    public function url(?string $locale = null, bool $absolute = true): string
    {
        $locale ??= app()->getLocale();

        return route('insights.category', [
            'locale' => $locale,
            'category' => (string) ($this->getTranslation('slug', $locale) ?? $this->getTranslation('slug', config('locales.fallback'))),
        ], $absolute);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
