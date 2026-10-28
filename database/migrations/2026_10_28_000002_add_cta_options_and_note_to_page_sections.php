<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lets editors control each section button's look from Filament.
 *
 * `*_cta_variant` picks the CtaButton variant and `*_cta_icon` shows or hides
 * the arrow. Both are nullable: null keeps the design default the Vue component
 * hard-codes for that slot, so existing sections render exactly as before.
 *
 * `note` is the handwritten aside under the home hero buttons. It used to be
 * fixed chrome in lang/{locale}/home.php; existing home heroes are backfilled
 * with that copy so nothing disappears on deploy.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_sections', function (Blueprint $table): void {
            $table->string('primary_cta_variant', 20)->nullable()->after('content_color');
            $table->string('primary_cta_icon', 20)->nullable()->after('primary_cta_variant');
            $table->string('secondary_cta_variant', 20)->nullable()->after('primary_cta_icon');
            $table->string('secondary_cta_icon', 20)->nullable()->after('secondary_cta_variant');
        });

        Schema::table('page_section_translations', function (Blueprint $table): void {
            $table->string('note', 300)->nullable()->after('secondary_cta_url');
        });

        $pageId = DB::table('pages')->where('key', 'home')->value('id');

        if ($pageId === null) {
            return;
        }

        $sectionIds = DB::table('page_sections')
            ->where('sectionable_type', Page::class)
            ->where('sectionable_id', $pageId)
            ->where('type', 'hero')
            ->pluck('id');

        DB::table('page_section_translations')
            ->whereIn('page_section_id', $sectionIds)
            ->whereNull('note')
            ->get(['id', 'locale'])
            ->each(fn (object $row) => DB::table('page_section_translations')
                ->where('id', $row->id)
                ->update([
                    'note' => trans('home.hero.note', [], $row->locale),
                    'updated_at' => now(),
                ]));
    }

    public function down(): void
    {
        Schema::table('page_section_translations', function (Blueprint $table): void {
            $table->dropColumn('note');
        });

        Schema::table('page_sections', function (Blueprint $table): void {
            $table->dropColumn([
                'primary_cta_variant',
                'primary_cta_icon',
                'secondary_cta_variant',
                'secondary_cta_icon',
            ]);
        });
    }
};
