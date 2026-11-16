<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * WordPress-style revisions: a snapshot of a translation's editable text
 * taken before each change (App\Models\ContentRevision).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_revisions', function (Blueprint $table): void {
            $table->id();
            $table->morphs('revisionable');
            $table->string('locale', 8);
            $table->json('data');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['revisionable_type', 'revisionable_id', 'locale', 'id'], 'content_revisions_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
    }
};
