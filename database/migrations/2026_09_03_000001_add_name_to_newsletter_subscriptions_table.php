<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the "نام و نام خانوادگی" (full name) field the lead-magnet modal draws
 * (Figma 416:5431) alongside email — previously dropped per GAPS G51 since
 * nothing read it, now added back on explicit user request. See GAPS G54.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletter_subscriptions', function (Blueprint $table): void {
            $table->string('name', 200)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_subscriptions', function (Blueprint $table): void {
            $table->dropColumn('name');
        });
    }
};
