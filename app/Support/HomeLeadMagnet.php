<?php

declare(strict_types=1);

namespace App\Support;

/**
 * The home page lead magnet: the file a visitor receives after filling the
 * home checklist banner (source=home, no article attached).
 *
 * Edited in Filament (Pages\ManageHomeLeadMagnet), stored as plain settings
 * and read through the SiteSettings cache. The per-article equivalent lives
 * on the `posts` table (lead_magnet_path / _name / _send_email).
 */
final class HomeLeadMagnet
{
    public const PATH_KEY = 'home_lead_magnet_path';

    public const NAME_KEY = 'home_lead_magnet_name';

    public const SEND_EMAIL_KEY = 'home_lead_magnet_send_email';

    public static function path(): ?string
    {
        $path = SiteSettings::get(self::PATH_KEY);

        return is_string($path) && $path !== '' ? $path : null;
    }

    public static function name(): ?string
    {
        $name = SiteSettings::get(self::NAME_KEY);

        return is_string($name) && $name !== '' ? $name : null;
    }

    public static function emails(): bool
    {
        return (bool) SiteSettings::get(self::SEND_EMAIL_KEY, null, false)
            && self::path() !== null;
    }
}
