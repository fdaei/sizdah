<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\HasLocalisedSlugs;
use App\Models\Translations\PostTagTranslation;
use App\Traits\HasSeo;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class PostTag extends Model implements HasLocalisedSlugs
{
    use HasFactory;
    use HasSeo;
    use HasTranslations;

    protected $fillable = [];

    /** @var array<int, string> */
    protected array $translatable = ['name', 'slug'];

    public function translationModel(): string
    {
        return PostTagTranslation::class;
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_post_tag');
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

        return route('insights.tag', ['locale' => $locale, 'tag' => $this->slugForLocale($locale)], $absolute);
    }
}
