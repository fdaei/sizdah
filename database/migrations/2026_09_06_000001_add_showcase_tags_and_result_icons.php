<?php

declare(strict_types=1);

use App\Enums\SectionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Two additions the case-study frame (336:5374) needs and the schema could not
 * express.
 *
 * 1. `project_images.tag` — 430:5201 puts a FilterChips row beside the content
 *    showcase heading ("همه" / "برندینگ" / "مارکتینگ"). Filtering a flat image
 *    list needs a per-image label; this is that label. Nullable on purpose: the
 *    chip row only renders once at least one image on the project carries a tag,
 *    so untagged projects keep the plain gallery and never show dead controls.
 *
 * 2. `section_items.icon` on Results items — each of the frame's five cream
 *    result tiles (615:6045…615:6160) carries its own 32px brand glyph. The
 *    column already existed but was never populated for this section type. The
 *    values are artwork keys resolved against resources/images/sizdah/work/
 *    (the same "stable across locales" role `Service::icon` plays for the Home
 *    orbit), not lucide names.
 *
 * The backfill matches on the item's title in ANY locale, not just `en`. The
 * site is fa-only (config/locales.php), so the seeded result items carry a fa
 * translation and no en one — keying on `en` alone made this half of the
 * migration a silent no-op. The two fa labels with no frame counterpart
 * (بازشناسی برند, یکدستی بصری, on the placeholder projects) map to nothing and
 * keep a null icon, which Work/Show.vue renders as a tile without a glyph.
 */
return new class extends Migration
{
    /** @var array<string, string> Result label (any locale) => artwork key. */
    private const ICONS = [
        'roi' => 'roi',
        'reach' => 'reach',
        'interaction' => 'interaction',
        'follower' => 'follower',
        'view' => 'view',
        'بازگشت سرمایه' => 'roi',
        'دسترسی' => 'reach',
        'تعامل' => 'interaction',
        'دنبال‌کننده' => 'follower',
        'بازدید' => 'view',
    ];

    public function up(): void
    {
        Schema::table('project_images', function (Blueprint $table): void {
            $table->string('tag', 50)
                ->nullable()
                ->after('path')
                ->comment('Showcase filter label — 430:5201');

            $table->index(['project_id', 'tag']);
        });

        $resultItemIds = DB::table('section_items')
            ->join('page_sections', 'page_sections.id', '=', 'section_items.page_section_id')
            ->where('page_sections.type', SectionType::Results->value)
            ->pluck('section_items.id');

        if ($resultItemIds->isEmpty()) {
            return;
        }

        $rows = DB::table('section_item_translations')
            ->whereIn('section_item_id', $resultItemIds)
            ->get(['section_item_id', 'title']);

        $resolved = [];

        foreach ($rows as $row) {
            // One item can carry a row per locale; the first locale whose label
            // is in the map wins, so re-enabling en/ar cannot overwrite a hit.
            if (isset($resolved[$row->section_item_id])) {
                continue;
            }

            $key = self::ICONS[mb_strtolower(trim((string) $row->title))] ?? null;

            if ($key !== null) {
                $resolved[$row->section_item_id] = $key;
                DB::table('section_items')->where('id', $row->section_item_id)->update(['icon' => $key]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('project_images', function (Blueprint $table): void {
            $table->dropIndex(['project_id', 'tag']);
            $table->dropColumn('tag');
        });

        DB::table('section_items')
            ->whereIn('icon', array_values(self::ICONS))
            ->update(['icon' => null]);
    }
};
