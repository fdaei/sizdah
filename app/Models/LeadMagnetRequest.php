<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LeadMagnetEmailStatus;
use App\Jobs\SendLeadMagnetEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One lead-magnet form submission and the delivery state of its email.
 * Created by SubmissionHandler, sent by SendLeadMagnetEmail.
 */
final class LeadMagnetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'newsletter_subscription_id',
        'name',
        'email',
        'locale',
        'source',
        'file_path',
        'file_name',
        'email_status',
        'email_attempts',
        'email_error',
        'emailed_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'email_status' => LeadMagnetEmailStatus::class,
            'email_attempts' => 'integer',
            'emailed_at' => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class)->withTrashed();
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(NewsletterSubscription::class, 'newsletter_subscription_id');
    }

    public function hasFile(): bool
    {
        return $this->file_path !== null && $this->file_path !== '';
    }

    /**
     * Queue the email again (admin "Resend"). Uses the article's CURRENT file
     * when it has one, so a corrected upload reaches readers who got the old
     * one or none.
     *
     * @return bool  false when there is no file to send
     */
    public function resend(): bool
    {
        $post = $this->post;

        if ($post !== null && filled($post->lead_magnet_path)) {
            $this->file_path = $post->lead_magnet_path;
            $this->file_name = $post->lead_magnet_name;
        }

        if (! $this->hasFile()) {
            return false;
        }

        $this->email_status = LeadMagnetEmailStatus::Pending;
        $this->email_error = null;
        $this->save();

        SendLeadMagnetEmail::dispatch($this);

        return true;
    }
}
