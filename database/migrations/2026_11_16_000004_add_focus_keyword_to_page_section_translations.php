<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-section focus keyphrase. Empty = the section is checked against the
 * parent page's focus keyword (see App\Services\Seo\SectionSeoAnalyzer).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_section_translations', function (Blueprint $table): void {
            $table->string('focus_keyword', 191)->nullable()->after('image_alt');
        });
    }

    public function down(): void
    {
        Schema::table('page_section_translations', function (Blueprint $table): void {
            $table->dropColumn('focus_keyword');
        });
    }
};
