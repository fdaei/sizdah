<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Models\Media;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

final class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    public function mount(): void
    {
        parent::mount();

        // Index files uploaded through content fields since the last visit.
        Media::sync();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('rescan')
                ->label('اسکن دوباره فایل‌ها')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function (): void {
                    Media::sync(force: true);
                    Notification::make()->success()->title('کتابخانه به‌روز شد')->send();
                }),
            Actions\CreateAction::make()->label('بارگذاری فایل')->icon('heroicon-o-arrow-up-tray'),
        ];
    }
}
