<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('not_found_logs', function (Blueprint $table): void {
            $table->boolean('is_bot')->default(false)->after('user_agent')->index();
        });
    }

    public function down(): void
    {
        Schema::table('not_found_logs', function (Blueprint $table): void {
            $table->dropColumn('is_bot');
        });
    }
};
