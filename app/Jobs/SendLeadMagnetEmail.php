<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\LeadMagnetEmailStatus;
use App\Mail\LeadMagnetMail;
use App\Models\LeadMagnetRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Emails a lead-magnet file and records the outcome on its request row, so
 * the admin panel can show pending / sent / failed per reader.
 *
 * Queued so the visitor's form submission returns immediately. Retried with
 * backoff on SMTP errors; the row only turns `failed` once retries run out.
 */
final class SendLeadMagnetEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [60, 300];

    public function __construct(
        public readonly LeadMagnetRequest $leadRequest,
    ) {}

    public function handle(): void
    {
        $request = $this->leadRequest;

        if (! $request->hasFile() || ! Storage::disk('local')->exists((string) $request->file_path)) {
            // Retrying cannot bring a deleted file back.
            $request->update([
                'email_status' => LeadMagnetEmailStatus::Failed,
                'email_error' => 'Lead magnet file not found: '.($request->file_path ?: '(none)'),
            ]);

            return;
        }

        $request->increment('email_attempts');

        try {
            Mail::to($request->email, $request->name)
                ->locale($request->locale)
                ->send(new LeadMagnetMail($request));
        } catch (Throwable $e) {
            // Keep the latest error visible while retries are still pending.
            $request->update(['email_error' => Str::limit($e->getMessage(), 1000)]);

            throw $e;
        }

        $request->update([
            'email_status' => LeadMagnetEmailStatus::Sent,
            'emailed_at' => now(),
            'email_error' => null,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        $this->leadRequest->update([
            'email_status' => LeadMagnetEmailStatus::Failed,
            'email_error' => Str::limit((string) ($exception?->getMessage() ?? 'Unknown error'), 1000),
        ]);
    }
}
