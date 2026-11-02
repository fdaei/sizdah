<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Keeps CTA content independent from how the button is laid out.
 *
 * Null values preserve the current design: arrows trail their labels and the
 * primary hero CTA appears before the secondary CTA.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_sections', function (Blueprint $table): void {
            $table->string('primary_cta_icon_position', 10)->nullable()->after('primary_cta_icon');
            $table->string('secondary_cta_icon_position', 10)->nullable()->after('secondary_cta_icon');
            $table->string('cta_order', 20)->nullable()->after('secondary_cta_icon_position');
        });
    }

    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $table): void {
            $table->dropColumn([
                'primary_cta_icon_position',
                'secondary_cta_icon_position',
                'cta_order',
            ]);
        });
    }
};
