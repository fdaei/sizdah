<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\LeadMagnetRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * The article's lead-magnet file, sent to the reader who asked for it.
 *
 * Not queued itself — SendLeadMagnetEmail is the queued unit, so it can
 * record whether this send succeeded on the LeadMagnetRequest row.
 */
final class LeadMagnetMail extends Mailable
{
    public function __construct(
        public readonly LeadMagnetRequest $leadRequest,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->postTitle() !== ''
                ? __('forms.lead_magnet_mail.subject_with_title', ['title' => $this->postTitle()])
                : __('forms.lead_magnet_mail.subject'),
        );
    }

    public function content(): Content
    {
        $post = $this->leadRequest->post;

        return new Content(
            view: 'mail.lead-magnet',
            with: [
                'name' => $this->leadRequest->name,
                'postTitle' => $this->postTitle(),
                'postUrl' => $post !== null && ! $post->trashed() && $post->isPublished()
                    ? $post->url($this->leadRequest->locale)
                    : null,
                'hasAttachment' => $this->leadRequest->hasFile(),
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if (! $this->leadRequest->hasFile()) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('local', (string) $this->leadRequest->file_path)
                ->as($this->leadRequest->file_name ?: basename((string) $this->leadRequest->file_path)),
        ];
    }

    private function postTitle(): string
    {
        return (string) ($this->leadRequest->post?->getTranslation('title', $this->leadRequest->locale) ?? '');
    }
}
