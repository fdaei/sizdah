<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LeadMagnetEmailStatus;
use App\Enums\SubmissionStatus;
use App\Http\Requests\ContactSubmissionRequest;
use App\Http\Requests\NewsletterSubscriptionRequest;
use App\Jobs\SendLeadMagnetEmail;
use App\Models\ContactSubmission;
use App\Models\LeadMagnetRequest;
use App\Models\NewsletterSubscription;
use App\Models\Post;
use App\Notifications\ContactSubmissionReceived;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * Persists public form submissions and dispatches notifications.
 *
 * Kept out of the controllers so both the Contact page and the Home-embedded
 * form go through identical logic, and so the behaviour is unit-testable
 * without an HTTP round trip.
 */
final class SubmissionHandler
{
    /**
     * Store a contact submission and notify the team.
     */
    public function handleContact(ContactSubmissionRequest $request): ContactSubmission
    {
        $submission = DB::transaction(function () use ($request): ContactSubmission {
            return ContactSubmission::create([
                'name' => $request->validated('name'),
                'brand_name' => $request->validated('brand_name'),
                'phone' => $request->validated('phone'),
                'email' => $request->validated('email'),
                'message' => $request->validated('message'),
                'service_ids' => $request->validated('service_ids') ?? [],
                'service_titles' => $request->serviceTitles(),
                'status' => SubmissionStatus::New,
                'locale' => app()->getLocale(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'referrer' => substr((string) $request->header('referer'), 0, 500),
            ]);
        });

        $recipient = config('mail.contact_notification_address');

        if (is_string($recipient) && $recipient !== '') {
            // Queued — the visitor gets their success state immediately.
            Notification::route('mail', $recipient)
                ->notify(new ContactSubmissionReceived($submission));
        }

        return $submission;
    }

    /**
     * Subscribe an address and log the lead-magnet request it came with.
     *
     * Every submission gets its own LeadMagnetRequest row (even from an
     * address already on the list), and — when the article it came from has
     * a file and email delivery enabled in the admin — a queued email.
     *
     * @return bool  true when newly subscribed, false when already on the list
     */
    public function handleNewsletter(NewsletterSubscriptionRequest $request): bool
    {
        [$subscription, $wasNew] = $this->subscribe($request);

        $this->recordLeadMagnetRequest($request, $subscription);

        return $wasNew;
    }

    /**
     * @return array{0: NewsletterSubscription, 1: bool}
     */
    private function subscribe(NewsletterSubscriptionRequest $request): array
    {
        $email = strtolower((string) $request->validated('email'));

        $existing = NewsletterSubscription::query()
            ->where('email', $email)
            ->first();

        if ($existing !== null) {
            // Previously unsubscribed addresses are quietly reactivated.
            if (! $existing->isActive()) {
                if ($request->validated('name')) {
                    $existing->name = $request->validated('name');
                }

                $existing->resubscribe();

                return [$existing, true];
            }

            return [$existing, false];
        }

        $subscription = NewsletterSubscription::create([
            'email' => $email,
            'name' => $request->validated('name'),
            'locale' => app()->getLocale(),
            'source' => $request->validated('source') ?? 'home',
            'ip_address' => $request->ip(),
        ]);

        return [$subscription, true];
    }

    private function recordLeadMagnetRequest(
        NewsletterSubscriptionRequest $request,
        NewsletterSubscription $subscription,
    ): LeadMagnetRequest {
        $postId = $request->validated('post_id');

        $post = $postId === null
            ? null
            : Post::query()->published()->find((int) $postId);

        $sendsEmail = $post?->emailsLeadMagnet() ?? false;

        $leadRequest = LeadMagnetRequest::create([
            'post_id' => $post?->getKey(),
            'newsletter_subscription_id' => $subscription->getKey(),
            'name' => $request->validated('name'),
            'email' => $subscription->email,
            'locale' => app()->getLocale(),
            'source' => $request->validated('source') ?? 'home',
            'file_path' => $post?->lead_magnet_path,
            'file_name' => $post?->lead_magnet_name,
            'email_status' => $sendsEmail
                ? LeadMagnetEmailStatus::Pending
                : LeadMagnetEmailStatus::Skipped,
            'ip_address' => $request->ip(),
        ]);

        if ($sendsEmail) {
            // Queued — the visitor gets their success state immediately.
            SendLeadMagnetEmail::dispatch($leadRequest);
        }

        return $leadRequest;
    }
}
