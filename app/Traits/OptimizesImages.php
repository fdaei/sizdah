<?php

declare(strict_types=1);

namespace App\Traits;

use App\Jobs\OptimizeImage;
use App\Services\ImageOptimizer;
use Illuminate\Database\Eloquent\Model;

/**
 * Generates high-quality responsive variants whenever an image column changes
 * (see App\Services\ImageOptimizer). The original upload is never modified.
 *
 * The consuming model declares `protected array $optimizedImages = ['cover_path', ...]`.
 */
trait OptimizesImages
{
    public static function bootOptimizesImages(): void
    {
        static::saved(function (Model $model): void {
            foreach ($model->optimizedImages ?? [] as $attribute) {
                $changed = $model->wasChanged($attribute)
                    || ($model->wasRecentlyCreated && $model->getChanges() === []);
                if (! $changed) {
                    continue;
                }

                $path = $model->getAttribute($attribute);
                // `saved` fires before syncOriginal(), so this is the old value.
                $previous = $model->wasRecentlyCreated ? null : $model->getOriginal($attribute);

                if (is_string($path) && $path !== '') {
                    OptimizeImage::dispatch($path, is_string($previous) && $previous !== '' ? $previous : null);
                } elseif (is_string($previous) && $previous !== '') {
                    ImageOptimizer::purge($previous); // image removed
                }
            }
        });
    }
}
