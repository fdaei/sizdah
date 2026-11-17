<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_ratings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            // HMAC of IP + user agent: identifies repeat voters without storing PII.
            $table->char('voter_hash', 64);
            $table->timestamp('approved_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['service_id', 'voter_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_ratings');
    }
};
