<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forbidden_logs', function (Blueprint $table): void {
            $table->id();
            $table->char('path_hash', 40)->unique();
            $table->string('path', 1000);
            $table->string('referrer', 1000)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->boolean('is_bot')->default(false);
            $table->unsignedInteger('hits')->default(1);
            $table->timestamp('last_seen_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forbidden_logs');
    }
};
