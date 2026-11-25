<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Retained for existing model declarations. Saving an image no longer queues
 * resizing or re-encoding: the uploaded file is already prepared by the editor.
 */
trait OptimizesImages
{
    public static function bootOptimizesImages(): void
    {
        // No automatic image processing on model saves.
    }
}
