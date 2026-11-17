<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Category archive intro text — shown on /insights/category/{slug}, analysed
 * by the SEO tab and used as the meta-description fallback.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_category_translations', function (Blueprint $table): void {
            $table->text('description')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('post_category_translations', function (Blueprint $table): void {
            $table->dropColumn('description');
        });
    }
};
