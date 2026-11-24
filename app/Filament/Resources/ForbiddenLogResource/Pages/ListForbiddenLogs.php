<?php

declare(strict_types=1);

namespace App\Filament\Resources\ForbiddenLogResource\Pages;

use App\Filament\Resources\ForbiddenLogResource;
use App\Models\ForbiddenLog;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListForbiddenLogs extends ListRecords
{
    protected static string $resource = ForbiddenLogResource::class;

    public function getSubheading(): ?string
    {
        return 'خطاهای ۴۰۳ صفحات عمومی از زمان فعال‌شدن این گزارش ثبت می‌شوند. آدرس‌ها را انتخاب کنید و برای ساخت ریدایرکت فعال، مقصد را مشخص کنید.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clear')->label('پاک کردن گزارش')->color('danger')
                ->icon('heroicon-o-trash')->requiresConfirmation()
                ->action(fn () => ForbiddenLog::query()->delete()),
        ];
    }
}
