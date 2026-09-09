<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Bring contact-page settings and social chips in line with the current
 * Sizdah contact frame. Seeders do not remove records that were renamed, so
 * this keeps existing installations from rendering the old YouTube chip.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')
            ->where('key', 'contact_email')
            ->update(['value' => json_encode(['value' => 'Sizdahmarketing@gmail.com'])]);

        DB::table('settings')
            ->where('key', 'contact_location')
            ->update(['value' => json_encode([
                'en' => 'Kerman, Iran',
                'fa' => 'کرمان، ایران',
                'ar' => 'كرمان، إيران',
            ])]);

        DB::table('settings')
            ->where('key', 'contact_working_with')
            ->update(['value' => json_encode([
                'en' => 'Brands in Iran and Beyond',
                'fa' => 'برندهایی در ایران و فراتر',
                'ar' => 'علامات في إيران وخارجها',
            ])]);

        DB::table('social_links')
            ->where('platform', 'youtube')
            ->update(['is_active' => false, 'updated_at' => now()]);

        $links = [
            ['instagram', 'Instagram', 'https://instagram.com/sizdahmarketing', 'instagram', 1],
            ['linkedin', 'LinkedIn', 'https://www.linkedin.com/company/sizdah-marketing/', 'linkedin', 2],
            ['whatsapp', 'WhatsApp', 'https://wa.me/96777811213', 'message-circle', 3],
            ['telegram', 'Telegram', 'https://t.me/sizdahmarketing', 'telegram', 4],
            ['x', 'X', 'https://x.com/sizdahmarketing', 'twitter', 5],
        ];

        foreach ($links as [$platform, $label, $url, $icon, $sortOrder]) {
            DB::table('social_links')->updateOrInsert(
                ['platform' => $platform],
                [
                    'label' => $label,
                    'url' => $url,
                    'icon' => $icon,
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    public function down(): void
    {
        // The previous values are legacy CMS data and are intentionally not
        // restored by a rollback; editors can set them in Filament.
    }
};
