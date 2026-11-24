<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Actions\PreviewAction;
use App\Filament\Concerns\HandlesTranslations;
use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditPage extends EditRecord
{
    use HandlesTranslations;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(),
            Actions\Action::make('pageBackgrounds')
                ->label(app()->getLocale() === 'fa' ? 'پس‌زمینه موبایل، تبلت و دسکتاپ' : 'Mobile, tablet and desktop backgrounds')
                ->icon('heroicon-o-photo')
                ->url(fn (): string => ManageSettings::getUrl(['settings-tab' => '-page-backgrounds-tab']))
                ->visible(fn (): bool => ManageSettings::canAccess() && in_array($this->record->key, ['home', 'work', 'services', 'insights', 'about'], true)),
            Actions\DeleteAction::make(),
        ];
    }
}
