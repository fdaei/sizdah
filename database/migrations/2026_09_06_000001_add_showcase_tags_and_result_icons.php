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
 * The backfill matches on the item's English title because that string is the
 * one the seeder keeps stable across all six projects; the fa/ar titles are
 * translations and differ.
 */
return new class extends Migration
{
    /** @var array<string, string> English result label => artwork key. */
    private const ICONS = [
        'roi' => 'roi',
        'reach' => 'reach',
        'interaction' => 'interaction',
        'follower' => 'follower',
        'view' => 'view',
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

        $titles = DB::table('section_item_translations')
            ->whereIn('section_item_id', $resultItemIds)
            ->where('locale', 'en')
            ->pluck('title', 'section_item_id');

        foreach ($titles as $itemId => $title) {
            $key = self::ICONS[mb_strtolower(trim((string) $title))] ?? null;

            if ($key !== null) {
                DB::table('section_items')->where('id', $itemId)->update(['icon' => $key]);
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
