<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-item, per-locale SEO metadata (Yoast/Rank Math parity).
 *
 * One row per (seoable, locale): each translation is its own URL, so every
 * field — robots and schema included — is scoped to that locale's URL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table): void {
            $table->id();
            $table->morphs('seoable');
            $table->string('locale', 8);

            // Snippet
            $table->string('title', 250)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('focus_keyword', 191)->nullable();
            $table->json('secondary_keywords')->nullable();
            $table->boolean('cornerstone')->default(false);

            // Advanced
            $table->string('canonical_url', 500)->nullable();
            $table->boolean('robots_index')->nullable(); // null = type default
            $table->boolean('robots_follow')->nullable();
            $table->json('robots_advanced')->nullable();
            $table->string('breadcrumb_title', 191)->nullable();

            // Social
            $table->string('og_title', 250)->nullable();
            $table->string('og_description', 500)->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('og_type', 32)->nullable();
            $table->string('twitter_title', 250)->nullable();
            $table->string('twitter_description', 500)->nullable();
            $table->string('twitter_image', 500)->nullable();
            $table->string('twitter_card', 32)->nullable();

            // Schema
            $table->string('schema_type', 64)->nullable();
            $table->string('article_type', 64)->nullable();
            $table->json('schema_data')->nullable();
            $table->longText('custom_schema')->nullable();

            // Sitemap
            $table->boolean('exclude_from_sitemap')->default(false);
            $table->decimal('sitemap_priority', 2, 1)->nullable();
            $table->string('sitemap_changefreq', 16)->nullable();

            // Cached analysis (0-100), recomputed on save
            $table->unsignedTinyInteger('seo_score')->nullable();
            $table->unsignedTinyInteger('readability_score')->nullable();

            $table->timestamps();

            $table->unique(['seoable_type', 'seoable_id', 'locale']);
            $table->index('seo_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
