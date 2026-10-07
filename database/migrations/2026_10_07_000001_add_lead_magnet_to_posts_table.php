<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-article lead magnet: the file a reader gets when they fill the
 * in-article checklist form (LeadMagnetModal), and whether it is emailed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            // Stored on the private `local` disk — reachable only as a mail attachment.
            $table->string('lead_magnet_path', 500)->nullable()->after('reading_minutes');
            $table->string('lead_magnet_name', 255)->nullable()->after('lead_magnet_path');
            $table->boolean('lead_magnet_send_email')->default(false)->after('lead_magnet_name');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn(['lead_magnet_path', 'lead_magnet_name', 'lead_magnet_send_email']);
        });
    }
};
