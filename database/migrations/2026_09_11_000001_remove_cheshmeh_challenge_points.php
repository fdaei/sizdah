<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Remove the three challenge bullets from the Cheshmeh case study.
 *
 * The seed data is updated as well, but this keeps an existing database in
 * sync without requiring a full reseed.
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

        DB::table('project_translations')
            ->where('project_id', $projectId)
            ->update([
                'challenge_points' => json_encode([], JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Content migrations are intentionally irreversible.
    }
};
