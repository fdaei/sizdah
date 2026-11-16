<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Copies the legacy translation-table seo_title / seo_description columns
 * into seo_meta. The old columns are left in place (and still read as a
 * fallback by SeoBuilder) until a later migration drops them.
 */
return new class extends Migration
{
    /** morph class => [translation table, foreign key] */
    private const SOURCES = [
        'App\\Models\\Page' => ['page_translations', 'page_id'],
        'App\\Models\\Post' => ['post_translations', 'post_id'],
        'App\\Models\\Project' => ['project_translations', 'project_id'],
    ];

    public function up(): void
    {
        $now = now();

        foreach (self::SOURCES as $class => [$table, $key]) {
            DB::table($table)
                ->where(fn ($q) => $q->whereNotNull('seo_title')->orWhereNotNull('seo_description'))
                ->orderBy('id')
                ->each(function (object $row) use ($class, $key, $now): void {
                    DB::table('seo_meta')->updateOrInsert(
                        ['seoable_type' => $class, 'seoable_id' => $row->{$key}, 'locale' => $row->locale],
                        [
                            'title' => $row->seo_title ? $row->seo_title.' %sep% %sitename%' : null,
                            'description' => $row->seo_description ?: null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    );
                });
        }
    }

    public function down(): void
    {
        // Source columns were never modified; nothing to restore.
    }
};
