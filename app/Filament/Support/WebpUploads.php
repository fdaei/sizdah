<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/** Store ordinary raster uploads as WebP; keep vectors and animations intact. */
final class WebpUploads
{
    public static function register(): void
    {
        FileUpload::configureUsing(function (FileUpload $upload): void {
            $upload->saveUploadedFileUsing(function (FileUpload $component, TemporaryUploadedFile $file): ?string {
                if (! $file->exists()) {
                    return null;
                }

                $mime = $file->getMimeType();
                if (in_array($mime, ['image/jpeg', 'image/png', 'image/avif'], true)
                    && function_exists('imagewebp') && function_exists('imagecreatefromstring')) {
                    $image = @imagecreatefromstring($file->get());
                    if ($image !== false) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, false);
                        imagesavealpha($image, true);
                        // Preserve the orientation of camera JPEGs.
                        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
                            $orientation = @exif_read_data($file->getRealPath())['Orientation'] ?? 1;
                            if (in_array($orientation, [2, 4, 5, 7], true)) {
                                imageflip($image, IMG_FLIP_HORIZONTAL);
                            }
                            $angle = match ($orientation) { 3, 4 => 180, 5, 6 => -90, 7, 8 => 90, default => 0 };
                            if ($angle !== 0) {
                                $image = imagerotate($image, $angle, 0);
                            }
                        }
                        ob_start();
                        $success = imagewebp($image, null, 90);
                        $bytes = ob_get_clean();
                        imagedestroy($image);
                        if ($success && is_string($bytes) && $bytes !== '') {
                            $name = pathinfo($component->getUploadedFileNameForStorage($file), PATHINFO_FILENAME).'-'.Str::lower(Str::random(6)).'.webp';
                            $path = trim($component->getDirectory().'/'.$name, '/');
                            if ($component->getDisk()->put($path, $bytes, $component->getVisibility())) {
                                return $path;
                            }
                        }
                    }
                }

                $method = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

                return $file->{$method}($component->getDirectory(), $component->getUploadedFileNameForStorage($file), $component->getDiskName());
            });
        }, isImportant: true);
    }
}
