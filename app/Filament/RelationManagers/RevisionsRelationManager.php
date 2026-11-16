<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Models\ContentRevision;
use App\Services\Seo\SeoAnalyzer;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * "Revisions" tab (WordPress parity): every saved change to title / excerpt
 * / body keeps the previous version; view it and restore with one click.
 * Shared by Post and Page.
 */
final class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    protected static ?string $title = 'نسخه‌ها (Revisions)';

    protected static ?string $icon = 'heroicon-o-clock';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->revisions()->count();

        return $count > 0 ? (string) $count : null;
    }

    public function isReadOnly(): bool
    {
        return false; // restore is an action, but no create/edit form
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('زمان (Date)')->dateTime('Y-m-d H:i')->description(fn (ContentRevision $r): string => $r->created_at->diffForHumans()),
                Tables\Columns\TextColumn::make('user.name')->label('ویرایشگر (Author)')->placeholder('—'),
                Tables\Columns\TextColumn::make('locale')->label('زبان')->badge(),
                Tables\Columns\TextColumn::make('data.title')->label('عنوان در آن نسخه')->limit(60),
                Tables\Columns\TextColumn::make('words')->label('کلمات')
                    ->state(fn (ContentRevision $r): int => count(SeoAnalyzer::words(SeoAnalyzer::plain((string) ($r->data['content'] ?? ''))))),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('مشاهده')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalContent(fn (ContentRevision $record) => view('filament.revisions.preview', ['revision' => $record])),
                Tables\Actions\Action::make('restore')
                    ->label('بازگردانی (Restore)')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('بازگردانی این نسخه؟')
                    ->modalDescription('متن فعلی هم به‌عنوان یک نسخه ذخیره می‌شود، پس چیزی از دست نمی‌رود.')
                    ->action(function (ContentRevision $record): void {
                        $record->restore();
                        Notification::make()->success()->title('نسخه بازگردانی شد — صفحه را رفرش کنید')->send();
                    }),
            ])
            ->bulkActions([]);
    }
}
