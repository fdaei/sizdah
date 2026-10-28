<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Home projects_showcase section's `content` overrides the open row's link
 * label. Installations that stored the old "مشاهده مطالعه موردی" copy there
 * would never show "جزییات پروژه", so clear it and let `work.project_details`
 * drive the label again.
 */
return new class extends Migration
{
    public function up(): void
    {
        $pageId = DB::table('pages')->where('key', 'home')->value('id');

        if ($pageId === null) {
            return;
        }

        $sectionId = DB::table('page_sections')
            ->where('sectionable_type', Page::class)
            ->where('sectionable_id', $pageId)
            ->where('type', 'projects_showcase')
            ->value('id');

        if ($sectionId === null) {
            return;
        }

        DB::table('page_section_translations')
            ->where('page_section_id', $sectionId)
            ->where('content', 'like', '%مطالعه موردی%')
            ->update([
                'content' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Content migrations are intentionally irreversible because editors may
        // update this field in Filament after deployment.
    }
};
