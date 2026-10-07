<?php

declare(strict_types=1);

use App\Enums\LeadMagnetEmailStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per lead-magnet form submission — unlike `newsletter_subscriptions`
 * (unique per address), a reader who asks for two articles' files gets two
 * rows, each with its own email delivery status.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_magnet_requests', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('newsletter_subscription_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name', 200)->nullable();
            $table->string('email', 200);
            $table->string('locale', 5);
            $table->string('source', 50)->nullable()->comment('home|article|contact');

            // Snapshot of the file at request time, so later edits to the
            // article don't rewrite what this reader was sent.
            $table->string('file_path', 500)->nullable();
            $table->string('file_name', 255)->nullable();

            $table->string('email_status', 20)->default(LeadMagnetEmailStatus::Skipped->value);
            $table->unsignedTinyInteger('email_attempts')->default(0);
            $table->text('email_error')->nullable();
            $table->timestamp('emailed_at')->nullable();

            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index(['post_id', 'created_at']);
            $table->index(['email_status', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_magnet_requests');
    }
};
