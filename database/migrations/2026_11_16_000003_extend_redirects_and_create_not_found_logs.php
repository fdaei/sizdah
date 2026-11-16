<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Redirection module (Rank Math "Redirections" parity):
 *  - redirects: exact/regex matching, notes, origin (manual/auto/import)
 *  - not_found_logs: 404 monitor (path, referrer, hits, last seen)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('redirects', function (Blueprint $table): void {
            $table->string('match_type', 10)->default('exact')->after('destination_path');
            $table->string('origin', 10)->default('manual')->after('match_type');
            $table->string('notes', 500)->nullable()->after('origin');
            $table->string('destination_path', 500)->nullable()->change(); // 410/451 have no target
        });

        Schema::create('not_found_logs', function (Blueprint $table): void {
            $table->id();
            $table->char('path_hash', 40)->unique();
            $table->string('path', 1000);
            $table->string('referrer', 1000)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->unsignedInteger('hits')->default(1);
            $table->timestamp('last_seen_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('not_found_logs');

        Schema::table('redirects', function (Blueprint $table): void {
            $table->dropColumn(['match_type', 'origin', 'notes']);
        });
    }
};
