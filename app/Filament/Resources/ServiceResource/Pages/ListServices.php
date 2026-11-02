<?php

declare(strict_types=1);

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\PageResource;
use App\Filament\Resources\ServiceResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('editPageContent')
                ->label('Edit page heading and CTA')
                ->icon('heroicon-o-pencil-square')
                ->url(fn (): string => PageResource::getUrl('edit', [
                    'record' => Page::query()->key('services')->value('id'),
                ]))
                ->visible(fn (): bool => Page::query()->key('services')->exists()),

            Actions\CreateAction::make(),
        ];
    }
}
