<?php

declare(strict_types=1);

use App\Enums\LeadMagnetEmailStatus;
use App\Jobs\SendLeadMagnetEmail;
use App\Mail\LeadMagnetMail;
use App\Models\LeadMagnetRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('lead-magnets/checklist.pdf', '%PDF-1.4 test');
});

function leadMagnetPost(array $attributes = []): Post
{
    return Post::factory()->published()->create(array_merge([
        'lead_magnet_path' => 'lead-magnets/checklist.pdf',
        'lead_magnet_name' => 'content-checklist.pdf',
        'lead_magnet_send_email' => true,
    ], $attributes))->setTranslations(['fa' => ['title' => 'مقاله تست', 'slug' => 'test-post-'.uniqid()]]);
}

it('logs the request and queues the article file email', function (): void {
    Queue::fake();
    $post = leadMagnetPost();

    $this->from('/fa')->post('/fa/newsletter', [
        'name' => 'نسیم',
        'email' => 'reader@example.com',
        'source' => 'article',
        'post_id' => $post->id,
    ])->assertRedirect('/fa');

    $request = LeadMagnetRequest::query()->sole();

    expect($request->post_id)->toBe($post->id)
        ->and($request->name)->toBe('نسیم')
        ->and($request->file_name)->toBe('content-checklist.pdf')
        ->and($request->email_status)->toBe(LeadMagnetEmailStatus::Pending);

    Queue::assertPushed(SendLeadMagnetEmail::class, fn (SendLeadMagnetEmail $job): bool => $job->leadRequest->is($request));
});

it('records but does not email when the article has email turned off', function (): void {
    Queue::fake();
    $post = leadMagnetPost(['lead_magnet_send_email' => false]);

    $this->from('/fa')->post('/fa/newsletter', [
        'email' => 'reader@example.com',
        'source' => 'article',
        'post_id' => $post->id,
    ]);

    expect(LeadMagnetRequest::query()->sole()->email_status)->toBe(LeadMagnetEmailStatus::Skipped);
    Queue::assertNothingPushed();
});

it('logs a request again for an address already on the list', function (): void {
    Queue::fake();
    $post = leadMagnetPost();

    foreach ([1, 2] as $_) {
        $this->from('/fa')->post('/fa/newsletter', [
            'email' => 'reader@example.com',
            'source' => 'article',
            'post_id' => $post->id,
        ]);
    }

    expect(LeadMagnetRequest::query()->count())->toBe(2);
    Queue::assertPushed(SendLeadMagnetEmail::class, 2);
});

it('ignores a post id that is not a published article', function (): void {
    Queue::fake();
    $draft = Post::factory()->create([
        'lead_magnet_path' => 'lead-magnets/checklist.pdf',
        'lead_magnet_send_email' => true,
    ]);

    $this->from('/fa')->post('/fa/newsletter', [
        'email' => 'reader@example.com',
        'source' => 'article',
        'post_id' => $draft->id,
    ]);

    $request = LeadMagnetRequest::query()->sole();
    expect($request->post_id)->toBeNull()
        ->and($request->email_status)->toBe(LeadMagnetEmailStatus::Skipped);
    Queue::assertNothingPushed();
});

it('marks the request sent once the mail goes out, with the file attached', function (): void {
    Mail::fake();
    $post = leadMagnetPost();

    $request = LeadMagnetRequest::factory()->create([
        'post_id' => $post->id,
        'email' => 'reader@example.com',
        'file_path' => 'lead-magnets/checklist.pdf',
        'file_name' => 'content-checklist.pdf',
        'email_status' => LeadMagnetEmailStatus::Pending,
    ]);

    (new SendLeadMagnetEmail($request))->handle();

    Mail::assertSent(LeadMagnetMail::class, function (LeadMagnetMail $mail): bool {
        $attachments = $mail->attachments();

        return $mail->hasTo('reader@example.com')
            && count($attachments) === 1
            && $attachments[0]->as === 'content-checklist.pdf';
    });

    $request->refresh();
    expect($request->email_status)->toBe(LeadMagnetEmailStatus::Sent)
        ->and($request->emailed_at)->not->toBeNull()
        ->and($request->email_attempts)->toBe(1);
});

it('marks the request failed when the file is missing', function (): void {
    Mail::fake();

    $request = LeadMagnetRequest::factory()->create([
        'file_path' => 'lead-magnets/gone.pdf',
        'email_status' => LeadMagnetEmailStatus::Pending,
    ]);

    (new SendLeadMagnetEmail($request))->handle();

    Mail::assertNothingSent();
    expect($request->refresh()->email_status)->toBe(LeadMagnetEmailStatus::Failed)
        ->and($request->email_error)->toContain('gone.pdf');
});

it('marks the request failed with the error once retries run out', function (): void {
    $request = LeadMagnetRequest::factory()->create([
        'file_path' => 'lead-magnets/checklist.pdf',
        'email_status' => LeadMagnetEmailStatus::Pending,
    ]);

    (new SendLeadMagnetEmail($request))->failed(new RuntimeException('SMTP 550 mailbox unavailable'));

    expect($request->refresh()->email_status)->toBe(LeadMagnetEmailStatus::Failed)
        ->and($request->email_error)->toBe('SMTP 550 mailbox unavailable');
});

it('resends with the article current file', function (): void {
    Queue::fake();
    Storage::disk('local')->put('lead-magnets/v2.pdf', '%PDF-1.4 v2');
    $post = leadMagnetPost(['lead_magnet_path' => 'lead-magnets/v2.pdf', 'lead_magnet_name' => 'v2.pdf']);

    $request = LeadMagnetRequest::factory()->create([
        'post_id' => $post->id,
        'file_path' => 'lead-magnets/checklist.pdf',
        'email_status' => LeadMagnetEmailStatus::Failed,
        'email_error' => 'boom',
    ]);

    expect($request->resend())->toBeTrue();

    $request->refresh();
    expect($request->file_path)->toBe('lead-magnets/v2.pdf')
        ->and($request->email_status)->toBe(LeadMagnetEmailStatus::Pending)
        ->and($request->email_error)->toBeNull();
    Queue::assertPushed(SendLeadMagnetEmail::class);
});
