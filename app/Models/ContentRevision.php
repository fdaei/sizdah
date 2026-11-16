<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Translations\PageTranslation;
use App\Models\Translations\PostTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Snapshot of a translation's text before an edit (WordPress revisions).
 * Taken by the `updating` hook of Post/Page translations; the newest
 * KEEP per item+locale are kept.
 */
final class ContentRevision extends Model
{
    public const KEEP = 30;

    /** translation class => [parent class, foreign key, tracked attributes] */
    public const TRACKED = [
        PostTranslation::class => [Post::class, 'post_id', ['title', 'subtitle', 'excerpt', 'content']],
        PageTranslation::class => [Page::class, 'page_id', ['title', 'subtitle', 'description', 'content']],
    ];

    public $timestamps = false;

    protected $fillable = ['revisionable_type', 'revisionable_id', 'locale', 'data', 'user_id', 'created_at'];

    protected function casts(): array
    {
        return ['data' => 'array', 'created_at' => 'datetime'];
    }

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Called from a translation's `updating` event: store the pre-edit text. */
    public static function capture(Model $translation): void
    {
        $config = self::TRACKED[$translation::class] ?? null;
        if ($config === null) {
            return;
        }
        [$parentClass, $fk, $fields] = $config;

        if (! $translation->isDirty($fields)) {
            return;
        }

        $parent = new $parentClass;
        $type = $parent->getMorphClass();
        $id = $translation->getAttribute($fk);
        $locale = (string) $translation->getAttribute('locale');

        self::query()->create([
            'revisionable_type' => $type,
            'revisionable_id' => $id,
            'locale' => $locale,
            'data' => collect($fields)->mapWithKeys(fn (string $f): array => [$f => $translation->getOriginal($f)])->all(),
            'user_id' => auth()->id(),
            'created_at' => now(),
        ]);

        // Keep only the newest KEEP snapshots.
        $stale = self::query()
            ->where(['revisionable_type' => $type, 'revisionable_id' => $id, 'locale' => $locale])
            ->orderByDesc('id')
            ->skip(self::KEEP)
            ->take(PHP_INT_MAX)
            ->pluck('id');
        if ($stale->isNotEmpty()) {
            self::query()->whereIn('id', $stale)->delete();
        }
    }

    /** Write this snapshot back (the current text becomes a new revision). */
    public function restore(): void
    {
        $config = collect(self::TRACKED)->first(fn (array $c): bool => (new $c[0])->getMorphClass() === $this->revisionable_type);
        if ($config === null) {
            return;
        }
        [, $fk] = $config;
        $translationClass = array_search($config, self::TRACKED, true);

        $translationClass::query()
            ->where($fk, $this->revisionable_id)
            ->where('locale', $this->locale)
            ->first()
            ?->update($this->data);
    }
}
