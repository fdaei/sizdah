<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscriptionRequest;
use App\Models\LeadMagnetRequest;
use App\Services\SubmissionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Lead-magnet signup endpoint. Figma 1419:9322.
 *
 * Besides the success message, flashes a short-lived signed download URL when
 * the request has a file, so LeadMagnetModal can start the download in the
 * browser (the email, when enabled, is sent independently).
 */
final class NewsletterController extends Controller
{
    public function __construct(
        private readonly SubmissionHandler $handler,
    ) {}

    public function __invoke(NewsletterSubscriptionRequest $request): RedirectResponse
    {
        [$wasNew, $leadRequest] = $this->handler->handleNewsletter($request);

        return back()
            ->with('success', $wasNew ? __('forms.newsletter.success') : __('forms.newsletter.already'))
            ->with('download', $this->downloadUrl($leadRequest));
    }

    private function downloadUrl(LeadMagnetRequest $leadRequest): ?string
    {
        if (! $leadRequest->hasFile() || ! Storage::disk('local')->exists((string) $leadRequest->file_path)) {
            return null;
        }

        return URL::temporarySignedRoute(
            'lead-magnet.download',
            now()->addMinutes(30),
            ['leadMagnetRequest' => $leadRequest->getKey()],
        );
    }
}
