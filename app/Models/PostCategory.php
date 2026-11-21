<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Translations\PostCategoryTranslation;
use App\Traits\HasSeo;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Support\NavigationBuilder;
use InvalidArgumentException;

/**
 * Blog category. Doubles as the listing filter chips (Figma 1363:7500).
 *
 * Categories form a tree through `parent_id`; the table is small, so tree
 * walks load every category once instead of recursing in SQL.
 */
final class PostCategory extends Model
{
    use HasFactory;
    use HasSeo;
    use HasTranslations;

    /** Guards tree walks against a corrupted (cyclic) parent chain. */
    public const MAX_DEPTH = 10;

    protected $fillable = ['sort_order', 'parent_id'];

    /** @var array<int, string> */
    protected array $translatable = ['name', 'slug', 'description'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'parent_id' => 'integer'];
    }

    public function translationModel(): string
    {
        return PostCategoryTranslation::class;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    protected static function booted(): void
    {
        self::saving(function (self $category): void {
            if ($category->parent_id !== null && $category->exists
                && in_array($category->parent_id, [$category->id, ...$category->descendantIds()], true)) {
                throw new InvalidArgumentException('A category cannot be placed under itself or one of its subcategories.');
            }
        });

        // Generated category submenus (MenuItem::children_source).
        self::saved(fn () => NavigationBuilder::flush());
        self::deleted(fn () => NavigationBuilder::flush());
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Ancestors from the root down to (not including) this category.
     *
     * @return list<self>
     */
    public function ancestors(): array
    {
        $chain = [];
        $current = $this;

        for ($i = 0; $i < self::MAX_DEPTH && $current->parent_id !== null; $i++) {
            $current = $current->relationLoaded('parent')
                ? $current->parent
                : $current->parent()->with('translations')->first();

            if ($current === null) {
                break;
            }

            array_unshift($chain, $current);
        }

        return $chain;
    }

    /** @return list<int> IDs of every category below this one. */
    public function descendantIds(): array
    {
        $parents = self::query()->pluck('parent_id', 'id')->all();
        $found = [];
        $frontier = [(int) $this->id];

        for ($depth = 0; $depth < self::MAX_DEPTH && $frontier !== []; $depth++) {
            $next = [];
            foreach ($parents as $childId => $parentId) {
                if ($parentId !== null && in_array((int) $parentId, $frontier, true) && ! in_array((int) $childId, $found, true)) {
                    $found[] = $next[] = (int) $childId;
                }
            }
            $frontier = $next;
        }

        return $found;
    }

    /**
     * Top-level categories with `children` set recursively — for menus.
     *
     * @return Collection<int, self>
     */
    public static function tree(): Collection
    {
        $byParent = self::query()->ordered()->with('translations')->get()
            ->groupBy(fn (self $c): string => (string) $c->parent_id);

        $attach = function (self $node, int $depth) use (&$attach, $byParent): void {
            $children = $depth < self::MAX_DEPTH ? ($byParent[(string) $node->id] ?? new Collection) : new Collection;
            $children->each(fn (self $child) => $attach($child, $depth + 1));
            $node->setRelation('children', $children->values());
        };

        $roots = ($byParent[''] ?? new Collection)->values();
        $roots->each(fn (self $root) => $attach($root, 1));

        return $roots;
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
