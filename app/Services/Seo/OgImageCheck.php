<?php

declare(strict_types=1);

namespace App\Services\Seo;

/**
 * Validates a social-share image against the 1200×630 (1.91:1) guideline used
 * by Facebook, LinkedIn, Telegram and WhatsApp. Advisory only: it returns a
 * warning, never blocks the save.
 */
final class OgImageCheck
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    /** Ratio drift tolerated before warning (1.91 ± 3%). */
    private const RATIO_TOLERANCE = 0.03;

    public static function warning(?int $width, ?int $height): ?string
    {
        if ($width === null || $height === null || $width <= 0 || $height <= 0) {
            return null;
        }

        $problems = [];

        if ($width < self::WIDTH || $height < self::HEIGHT) {
            $problems[] = "ابعاد {$width}×{$height} کوچک‌تر از ۱۲۰۰×۶۳۰ است و ممکن است تار یا به‌صورت کارت کوچک نمایش داده شود.";
        }

        $ideal = self::WIDTH / self::HEIGHT;
        if (abs(($width / $height) - $ideal) / $ideal > self::RATIO_TOLERANCE) {
            $problems[] = 'نسبت تصویر ۱.۹۱:۱ نیست؛ شبکه‌ها تصویر را برش می‌دهند.';
        }

        return $problems === [] ? null : '⚠ '.implode(' ', $problems);
    }

    /** @return array{0: int|null, 1: int|null} */
    public static function dimensions(?string $absolutePath): array
    {
        if ($absolutePath === null || ! is_file($absolutePath)) {
            return [null, null];
        }

        $size = @getimagesize($absolutePath);

        return $size === false ? [null, null] : [(int) $size[0], (int) $size[1]];
    }
}
