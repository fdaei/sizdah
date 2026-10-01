<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Converts a stored image path into the MediaImage shape the frontend expects
 * (resources/js/types/index.ts).
 *
 * Dimensions come from the Figma frames — see docs/ASSET-MANIFEST.md. They are
 * emitted as width/height attributes so the browser reserves space before the
 * image loads, which is what keeps the scroll animations from causing layout
 * shift (Phase 6 requirement: no CLS).
 */
final class MediaTransformer
{
    /**
     * Intrinsic dimensions per usage context, taken from the design.
     *
     * @var array<string, array{int, int}>
     */
    private const DIMENSIONS = [
        'project.cover' => [448, 448],      // 1362:7211 square card
        'project.banner' => [1203, 624],     // 1323:7605 case-study banner
        'project.showcase' => [400, 500],    // content showcase item
        'project.beforeafter' => [560, 360],
        'service' => [604, 786],             // 1323:7224 portrait
        'post.cover' => [736, 414],          // 1353:7935 listing card
        'post.hero' => [1248, 624],          // 1352:7391 article hero
        'team' => [294, 294],                // 992:2644 member card
        'testimonial' => [48, 48],           // 1419:9251 avatar
        'client' => [120, 40],               // 1419:9205 logo
        'page.hero' => [1440, 904],          // 1419:9193
        'page.about' => [420, 420],          // 951:3598
        'section' => [1248, 624],            // generic section image
    ];

    /**
     * @return array{src: string, alt: string, width: int, height: int}|null
     */
    public static function make(
        ?string $path,
        ?string $alt = null,
        string $context = 'section',
    ): ?array {
        if ($path === null || $path === '') {
            return null;
        }

        [$width, $height] = self::DIMENSIONS[$context] ?? self::DIMENSIONS['section'];

        $url = self::url($path);
        $variants = self::variants($path, $width);

        return [
            'src' => $url,
            ...$variants,
            'alt' => $alt ?? '',
            'width' => $width,
            'height' => $height,
        ];
    }

    /** Return only variants that are already present; never emit broken URLs. */
    private static function variants(string $path, int $width): array
    {
        $disk = Storage::disk('public');
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $base = substr($path, 0, -(strlen($extension) + 1));
        $points = array_values(array_unique([400, 736, 1200, $width]));
        $result = [];

        foreach (['avif', 'webp'] as $format) {
            $items = [];
            foreach ($points as $point) {
                $candidate = "{$base}-{$point}.{$format}";
                if ($disk->exists($candidate)) {
                    $items[] = self::url($candidate) . " {$point}w";
                }
            }
            if ($items !== []) {
                $result[$format] = implode(', ', $items);
            }
        }

        $items = [];
        foreach ($points as $point) {
            $candidate = "{$base}-{$point}.{$extension}";
            if ($disk->exists($candidate)) {
                $items[] = self::url($candidate) . " {$point}w";
            }
        }
        if ($items !== []) {
            $result['srcset'] = implode(', ', $items);
        }
        $result['sizes'] = $width >= 1000
            ? '(max-width: 768px) 100vw, 612px'
            : '(max-width: 768px) 100vw, 400px';

        return $result;
    }

    /**
     * Absolute URL for a stored path. Already-absolute values (a CDN URL typed
     * into admin) are returned untouched.
     */
    public static function url(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
