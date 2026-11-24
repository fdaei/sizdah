<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resource;
use App\Filament\Resources\NotFoundLogResource\Pages;
use App\Models\NotFoundLog;
use App\Models\Redirect;
use App\Services\Redirects\RedirectResolver;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * 404 monitor (Rank Math parity): every missed URL with hits, referrer and
 * last-seen time, and a one-click "create redirect" that removes the row.
 */
class NotFoundLogResource extends Resource
{
    protected static ?string $model = NotFoundLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'SEO';

    protected static ?string $navigationLabel = 'گزارش ۴۰۴ (404 monitor)';

    protected static ?string $modelLabel = 'خطای ۴۰۴';

    protected static ?string $pluralModelLabel = 'گزارش ۴۰۴ (404 monitor)';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = NotFoundLog::query()->where('last_seen_at', '>=', now()->subDays(7))->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('hits', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->label('آدرس (URL)')
                    ->searchable()
                    ->limit(70)
                    ->tooltip(fn (NotFoundLog $r): string => (string) $r->path)
                    ->url(fn (NotFoundLog $r): string => url('/'.$r->path), shouldOpenInNewTab: true)
                    ->extraAttributes(['dir' => 'ltr'])
                    ->copyable(),
                Tables\Columns\TextColumn::make('hits')->label('تعداد (Hits)')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('referrer')->label('ارجاع‌دهنده (Referrer)')->limit(50)->placeholder('—')
                    ->tooltip(fn (NotFoundLog $r): ?string => $r->referrer)->extraAttributes(['dir' => 'ltr'])->toggleable(),
                Tables\Columns\IconColumn::make('is_bot')->label('ربات')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('user_agent')->label('User agent')->limit(40)->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_seen_at')->label('آخرین بار (Last seen)')->since()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('اولین بار')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('recent')->label('۷ روز اخیر')
                    ->query(fn ($q) => $q->where('last_seen_at', '>=', now()->subDays(7))),
                Tables\Filters\Filter::make('with_referrer')->label('دارای ارجاع‌دهنده (لینک شکسته)')
                    ->query(fn ($q) => $q->whereNotNull('referrer')),
                Tables\Filters\TernaryFilter::make('is_bot')->label('بازدیدکننده')
                    ->trueLabel('فقط ربات‌ها')->falseLabel('فقط انسان‌ها'),
            ])
            ->actions([
                Tables\Actions\Action::make('redirect')
                    ->label('ساخت ریدایرکت (Redirect)')
                    ->icon('heroicon-o-arrow-uturn-right')
                    ->color('primary')
                    ->form(fn (NotFoundLog $record): array => [
                        TextInput::make('source')->label('مبدأ')->default($record->path)->disabled()->extraInputAttributes(['dir' => 'ltr']),
                        TextInput::make('destination')->label('مقصد (Destination)')->required()
                            ->extraInputAttributes(['dir' => 'ltr'])->placeholder('fa/new-page یا https://…'),
                        Select::make('status')->label('نوع')->options(array_intersect_key(Redirect::STATUSES, array_flip([301, 302, 410])))
                            ->default(301)->native(false)->required(),
                    ])
                    ->action(function (NotFoundLog $record, array $data): void {
                        self::createRedirect(collect([$record]), (string) $data['destination'], (int) $data['status']);
                    }),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('redirect_all')
                    ->label('ریدایرکت همه به یک مقصد')
                    ->icon('heroicon-o-arrow-uturn-right')
                    ->form([
                        TextInput::make('destination')->label('مقصد')->required()->extraInputAttributes(['dir' => 'ltr']),
                        Select::make('status')->label('نوع')->options(array_intersect_key(Redirect::STATUSES, array_flip([301, 302, 410])))
                            ->default(301)->native(false)->required(),
                    ])
                    ->action(fn (Collection $records, array $data) => self::createRedirect($records, (string) $data['destination'], (int) $data['status'])),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /** @param Collection<int, NotFoundLog> $records */
    public static function createRedirect(Collection $records, string $destination, int $status): void
    {
        $made = 0;
        foreach ($records as $record) {
            $source = Redirect::normalize($record->path);
            if ($status !== 410 && app(RedirectResolver::class)->finalDestination($source, $destination) === null) {
                continue; // would loop
            }
            Redirect::query()->updateOrCreate(['source_path' => $source], [
                'destination_path' => $status === 410 ? null : $destination,
                'status_code' => $status,
                'match_type' => 'exact',
                'is_active' => true,
                'origin' => $record instanceof \App\Models\ForbiddenLog ? '403' : '404',
            ]);
            $record->delete();
            $made++;
        }

        Notification::make()->success()->title("{$made} ریدایرکت ساخته شد")->send();
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListNotFoundLogs::route('/')];
    }
}
