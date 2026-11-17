<?php

declare(strict_types=1);

namespace App\Services;

use GdImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Generates high-quality responsive variants for an uploaded image.
 *
 * The original upload is never touched — it stays byte-for-byte what the admin
 * uploaded. Next to it we write `{base}-{width}.{webp,avif,ext}` files, the
 * exact naming MediaTransformer::variants() looks for, so the frontend
 * <picture> elements pick them up automatically.
 *
 * Quality rules:
 *  - never upscale (a width larger than the source is skipped);
 *  - downscale with GD's bicubic filter in true colour, alpha preserved;
 *  - encode at high quality (see QUALITY) — visually lossless for photos;
 *  - SVG/GIF are left alone (vector / animation would be destroyed).
 */
final class ImageOptimizer
{
    /** Widths kept in sync with MediaTransformer::BREAKPOINTS. */
    public const WIDTHS = MediaTransformer::BREAKPOINTS;

    private const QUALITY = ['jpg' => 95, 'webp' => 95, 'avif' => 85];

    private const RASTER = ['jpg', 'jpeg', 'png', 'webp'];

    public static function isSupported(): bool
    {
        return extension_loaded('gd') && function_exists('imagecreatefromstring');
    }

    /** @return list<string> paths written */
    public static function optimize(string $path, string $disk = 'public'): array
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($extension, self::RASTER, true) || str_starts_with($path, 'http')) {
            return [];
        }

        if (! self::isSupported()) {
            Log::warning('ImageOptimizer: GD extension missing, variants not generated.', ['path' => $path]);

            return [];
        }

        $storage = Storage::disk($disk);
        if (! $storage->exists($path)) {
            return [];
        }

        $source = @imagecreatefromstring((string) $storage->get($path));
        if (! $source instanceof GdImage) {
            return [];
        }

        $source = self::applyExifOrientation($source, $storage->path($path), $extension);
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $base = substr($path, 0, -(strlen($extension) + 1));
        $written = [];

        foreach (self::WIDTHS as $width) {
            if ($width > $sourceWidth) {
                continue;
            }

            $height = (int) max(1, round($sourceHeight * $width / $sourceWidth));
            $resized = self::resize($source, $width, $height);

            foreach (self::formats($extension) as $format) {
                $target = "{$base}-{$width}.{$format}";
                $binary = self::encode($resized, $format);
                if ($binary !== null) {
                    $storage->put($target, $binary);
                    $written[] = $target;
                }
            }

            imagedestroy($resized);
        }

        imagedestroy($source);

        return $written;
    }

    /** Remove every variant generated for $path (used when an image is replaced). */
    public static function purge(string $path, string $disk = 'public'): void
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($extension, self::RASTER, true)) {
            return;
        }

        $base = substr($path, 0, -(strlen($extension) + 1));
        $targets = [];
        foreach (self::WIDTHS as $width) {
            foreach (self::formats($extension) as $format) {
                $targets[] = "{$base}-{$width}.{$format}";
            }
        }

        Storage::disk($disk)->delete($targets);
    }

    /** @return list<string> */
    private static function formats(string $extension): array
    {
        $formats = [$extension === 'jpeg' ? 'jpeg' : $extension];
        if (function_exists('imagewebp') && $extension !== 'webp') {
            $formats[] = 'webp';
        }
        if (function_exists('imageavif')) {
            $formats[] = 'avif';
        }

        return array_values(array_unique($formats));
    }

    private static function resize(GdImage $source, int $width, int $height): GdImage
    {
        $target = imagecreatetruecolor($width, $height);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        imagefill($target, 0, 0, imagecolorallocatealpha($target, 0, 0, 0, 127));

        if (imagesx($source) === $width) {
            imagecopy($target, $source, 0, 0, 0, 0, $width, $height);

            return $target;
        }

        // Two-step: GD's bicubic scale is sharper than imagecopyresampled for big
        // reductions; fall back to resampled if it fails on this build.
        $scaled = imagescale($source, $width, $height, IMG_BICUBIC);
        if ($scaled instanceof GdImage) {
            imagecopy($target, $scaled, 0, 0, 0, 0, $width, $height);
            imagedestroy($scaled);
        } else {
            imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, imagesx($source), imagesy($source));
        }

        return $target;
    }

    private static function encode(GdImage $image, string $format): ?string
    {
        ob_start();
        $ok = match ($format) {
            'jpg', 'jpeg' => (function () use ($image): bool {
                imageinterlace($image, true); // progressive JPEG

                return imagejpeg($image, null, self::QUALITY['jpg']);
            })(),
            'png' => imagepng($image, null, 9),
            'webp' => imagewebp($image, null, self::QUALITY['webp']),
            'avif' => imageavif($image, null, self::QUALITY['avif'], 6),
            default => false,
        };
        $binary = (string) ob_get_clean();

        return $ok && $binary !== '' ? $binary : null;
    }

    /** Phone photos carry rotation in EXIF; bake it in so variants aren't sideways. */
    private static function applyExifOrientation(GdImage $image, string $file, string $extension): GdImage
    {
        if (! in_array($extension, ['jpg', 'jpeg'], true) || ! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = (int) (@exif_read_data($file)['Orientation'] ?? 1);
        $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;
        if ($angle === 0) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);
        if ($rotated instanceof GdImage) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }
}
