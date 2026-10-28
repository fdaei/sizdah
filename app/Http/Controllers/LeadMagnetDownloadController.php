<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\LeadMagnetRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Streams a lead-magnet file straight to the visitor who just filled the form.
 *
 * The file lives on the private `local` disk, so the only way in is the
 * short-lived signed URL NewsletterController flashes after a submission —
 * the `signed` middleware on the route rejects anything else.
 */
final class LeadMagnetDownloadController extends Controller
{
    public function __invoke(string $locale, LeadMagnetRequest $leadMagnetRequest): StreamedResponse
    {
        $path = (string) $leadMagnetRequest->file_path;

        abort_unless($leadMagnetRequest->hasFile() && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download(
            $path,
            $leadMagnetRequest->file_name ?: basename($path),
        );
    }
}
