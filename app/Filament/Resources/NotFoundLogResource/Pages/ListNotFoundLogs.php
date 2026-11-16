<?php

declare(strict_types=1);

namespace App\Filament\Resources\NotFoundLogResource\Pages;

use App\Filament\Resources\NotFoundLogResource;
use App\Models\NotFoundLog;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListNotFoundLogs extends ListRecords
{
    protected static string $resource = NotFoundLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clear')
                ->label('پاک کردن همه (Clear log)')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(fn () => NotFoundLog::query()->delete()),
        ];
    }
}
