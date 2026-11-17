<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resource;
use App\Filament\Resources\ServiceRatingResource\Pages;
use App\Models\ServiceRating;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

/**
 * Moderation queue for visitor star ratings. Only approved ratings feed the
 * public average and the AggregateRating schema.
 */
final class ServiceRatingResource extends Resource
{
    protected static ?string $model = ServiceRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Messages';

    protected static ?string $navigationLabel = 'امتیاز خدمات';

    protected static ?string $modelLabel = 'امتیاز';

    protected static ?string $pluralModelLabel = 'امتیاز خدمات';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = ServiceRating::query()->whereNull('approved_at')->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with('service.translations'))
            ->columns([
                Tables\Columns\TextColumn::make('service.title')->label('خدمت')
                    ->state(fn (ServiceRating $r): string => (string) $r->service?->getTranslation('title')),
                Tables\Columns\TextColumn::make('rating')->label('امتیاز')->sortable()
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state).str_repeat('☆', 5 - $state)),
                Tables\Columns\IconColumn::make('approved_at')->label('تأییدشده')->boolean()
                    ->state(fn (ServiceRating $r): bool => $r->approved_at !== null),
                Tables\Columns\TextColumn::make('created_at')->label('تاریخ')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('approved_at')->label('وضعیت')->nullable()
                    ->trueLabel('تأییدشده')->falseLabel('در انتظار تأیید'),
                Tables\Filters\SelectFilter::make('service_id')->label('خدمت')->relationship('service', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => (string) $record->getTranslation('title')),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')->label('تأیید')->icon('heroicon-o-check')->color('success')
                    ->visible(fn (ServiceRating $r): bool => $r->approved_at === null)
                    ->action(fn (ServiceRating $r) => $r->update(['approved_at' => now()])),
                Tables\Actions\Action::make('unapprove')->label('لغو تأیید')->icon('heroicon-o-x-mark')->color('gray')
                    ->visible(fn (ServiceRating $r): bool => $r->approved_at !== null)
                    ->action(fn (ServiceRating $r) => $r->update(['approved_at' => null])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('approve_all')->label('تأیید انتخاب‌شده‌ها')->icon('heroicon-o-check')
                    ->action(fn (Collection $records) => $records->each->update(['approved_at' => now()]))
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListServiceRatings::route('/')];
    }
}
