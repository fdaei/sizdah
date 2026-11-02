<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Replaces the fixed contact-card fields with an ordered, editor-managed list.
 * The legacy values remain in place because they are still used by the footer
 * and structured-data output.
 */
return new class extends Migration
{
    public function up(): void
    {
        $setting = fn (string $key): mixed => DB::table('settings')->where('key', $key)->value('value');
        $value = fn (string $key, string $fallback = ''): string => (string) (data_get(json_decode((string) $setting($key), true), 'value') ?? $fallback);
        $translated = function (string $key, string $fallback) use ($setting): array {
            $stored = json_decode((string) $setting($key), true);

            return is_array($stored)
                ? array_replace(['en' => $fallback, 'fa' => $fallback, 'ar' => $fallback], $stored)
                : ['en' => $fallback, 'fa' => $fallback, 'ar' => $fallback];
        };

        $phone = $value('contact_phone');
        $whatsapp = $value('contact_whatsapp', $phone);
        $email = $value('contact_email');

        $items = [
            ['type' => 'whatsapp', 'icon' => 'whatsapp', 'label' => ['en' => 'WhatsApp', 'fa' => 'واتساپ', 'ar' => 'واتساب'], 'value' => ['en' => $whatsapp, 'fa' => $whatsapp, 'ar' => $whatsapp], 'url' => null],
            ['type' => 'phone', 'icon' => 'phone', 'label' => ['en' => 'Phone', 'fa' => 'تلفن', 'ar' => 'الهاتف'], 'value' => ['en' => $phone, 'fa' => $phone, 'ar' => $phone], 'url' => null],
            ['type' => 'location', 'icon' => 'location', 'label' => ['en' => 'Location', 'fa' => 'موقعیت', 'ar' => 'الموقع'], 'value' => $translated('contact_location', 'Kerman, Iran'), 'url' => null],
            ['type' => 'email', 'icon' => 'email', 'label' => ['en' => 'Email', 'fa' => 'ایمیل', 'ar' => 'البريد الإلكتروني'], 'value' => ['en' => $email, 'fa' => $email, 'ar' => $email], 'url' => null],
            ['type' => 'collaboration', 'icon' => 'collaboration', 'label' => ['en' => 'Work with us', 'fa' => 'همکاری با', 'ar' => 'العمل معنا'], 'value' => $translated('contact_working_with', 'Brands in Iran and Beyond'), 'url' => null],
        ];

        DB::table('settings')->updateOrInsert(
            ['key' => 'contact_details'],
            ['group' => 'general', 'value' => json_encode(['value' => $items]), 'is_translatable' => false, 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'contact_details')->delete();
    }
};
