<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 | Phase 5 (content tools):
 |  - internal_links: "keyword → URL" auto-linking rules, per locale.
 |  - posts.toc_enabled: per-article TOC override (null = follow global setting).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_links', function (Blueprint $table): void {
            $table->id();
            $table->string('locale', 5)->index();
            $table->string('keyword', 190);
            $table->string('url', 500);
            $table->unsignedTinyInteger('max_per_page')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['locale', 'keyword']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->boolean('toc_enabled')->nullable()->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn('toc_enabled');
        });
        Schema::dropIfExists('internal_links');
    }
};
