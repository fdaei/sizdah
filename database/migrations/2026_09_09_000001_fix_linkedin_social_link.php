<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Replace the invalid bare-domain LinkedIn URL with the public company profile.
 *
 * Existing installations keep social links in the database, so updating the
 * seeder alone would leave the footer and contact-page link broken until a
 * manual reseed.
 */
return new class extends Migration
{
    private const BROKEN_URL = 'https://linkedin.com/company/sizdahmarketing';

    private const PROFILE_URL = 'https://www.linkedin.com/company/sizdah-marketing/';

    public function up(): void
    {
        DB::table('social_links')
            ->where('platform', 'linkedin')
            ->where('url', self::BROKEN_URL)
            ->update(['url' => self::PROFILE_URL]);
    }

    public function down(): void
    {
        DB::table('social_links')
            ->where('platform', 'linkedin')
            ->where('url', self::PROFILE_URL)
            ->update(['url' => self::BROKEN_URL]);
    }
};
