<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Cheshmeh case study uses the «چرا سیزده» strategy quadrant in place of
 * the generic «چه چیزی تحویل دادیم» deliverables block. Keep the old records
 * intact so an editor can restore them by rolling the migration back.
 */
return new class extends Migration
{
    public function up(): void
    {
        $projectId = DB::table('project_translations')
            ->whereIn('slug', ['cheshmeh-beauty', 'چشمه-بیوتی'])
            ->value('project_id');

        if ($projectId === null) {
            return;
        }

        DB::table('page_sections')
            ->where('sectionable_type', 'App\\Models\\Project')
            ->where('sectionable_id', $projectId)
            ->where('type', 'deliverables')
            ->update(['is_visible' => false]);
    }

    public function down(): void
    {
        $projectId = DB::table('project_translations')
            ->whereIn('slug', ['cheshmeh-beauty', 'چشمه-بیوتی'])
            ->value('project_id');

        if ($projectId === null) {
            return;
        }

        DB::table('page_sections')
            ->where('sectionable_type', 'App\\Models\\Project')
            ->where('sectionable_id', $projectId)
            ->where('type', 'deliverables')
            ->update(['is_visible' => true]);
    }
};
