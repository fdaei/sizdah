<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Compatibility for old callers and cleanup of previously generated variants.
 * Uploaded images are prepared by the editor and must not be re-encoded.
 */
final class ImageOptimizer
{
    public const WIDTHS = MediaTransformer::BREAKPOINTS;

    /** Automatic image generation is no longer supported. */
    public static function isSupported(): bool
    {
        return false;
    }

    /** @return list<string> */
    public static function optimize(string $path, string $disk = 'public'): array
    {
        // Keep already queued jobs / older integrations safe during upgrades.
        return [];
    }

    /** Remove legacy variants only when a caller explicitly cleans up a file. */
    public static function purge(string $path, string $disk = 'public'): void
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true)
            || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $base = substr($path, 0, -(strlen($extension) + 1));
        $targets = [];
        foreach (self::WIDTHS as $width) {
            foreach (array_unique([$extension, 'webp', 'avif']) as $format) {
                $targets[] = "{$base}-{$width}.{$format}";
            }
        }

        Storage::disk($disk)->delete($targets);
    }
}
