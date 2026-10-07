<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Delivery state of the lead-magnet email for one LeadMagnetRequest.
 *
 * `Sent` means the mail server accepted the message — it is not proof the
 * reader opened it, and a later bounce is not reported back here.
 */
enum LeadMagnetEmailStatus: string implements HasColor, HasIcon, HasLabel
{
    /** Email disabled for the article, or no file attached to it. */
    case Skipped = 'skipped';
    /** Queued; waiting for a queue worker. */
    case Pending = 'pending';
    case Sent = 'sent';
    case Failed = 'failed';

    public function getLabel(): string
    {
        return __("admin.lead_magnet.status.{$this->value}");
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Skipped => 'gray',
            self::Pending => 'warning',
            self::Sent => 'success',
            self::Failed => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Skipped => 'heroicon-m-minus-circle',
            self::Pending => 'heroicon-m-clock',
            self::Sent => 'heroicon-m-check-circle',
            self::Failed => 'heroicon-m-x-circle',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $carry, self $case): array => $carry + [$case->value => $case->getLabel()],
            [],
        );
    }
}
