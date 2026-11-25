<?php

declare(strict_types=1);

namespace App\Filament\Support;

/** Compatibility for existing application bootstrapping. */
final class WebpUploads
{
    public static function register(): void
    {
        // Use Filament's original-file storage. JPEG / PNG uploads must retain
        // the editor's bytes and format rather than being converted to WebP.
    }
}
