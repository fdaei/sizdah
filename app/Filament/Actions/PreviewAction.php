<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Support\Preview;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;

/**
 * Header action: opens the record's public page (drafts included, via a
 * signed admin-only link) in a modal with Mobile / Tablet / Desktop frames.
 * Shows the last *saved* state — save first to preview edits.
 */
final class PreviewAction
{
    public static function make(): Action
    {
        return Action::make('preview')
            ->label('پیش‌نمایش')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->modalHeading('پیش‌نمایش (آخرین نسخه‌ی ذخیره‌شده)')
            ->modalWidth(MaxWidth::Screen)
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('بستن')
            ->modalContent(fn (Model $record) => view('filament.preview.device-frame', [
                'url' => Preview::url($record),
            ]))
            ->visible(fn (Model $record): bool => Preview::supports($record));
    }
}
