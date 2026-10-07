<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\LeadMagnetEmailStatus;
use App\Filament\Resource;
use App\Filament\Resources\LeadMagnetRequestResource\Pages;
use App\Models\LeadMagnetRequest;
use App\Models\Post;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Who filled the lead-magnet form, from which article, which file they were
 * sent and whether the email went out. Rows arrive from the public form
 * (SubmissionHandler) and are never hand-edited — only resent or deleted.
 *
 * Also rendered per article as PostResource's LeadMagnetRequestsRelationManager,
 * which reuses table() below minus the article column/filter.
 */
final class LeadMagnetRequestResource extends Resource
{
    protected static ?string $model = LeadMagnetRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationGroup = 'Messages';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        $perArticle = $table->getLivewire() instanceof RelationManager;

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns(array_values(array_filter([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->weight('medium'),

                $perArticle ? null : Tables\Columns\TextColumn::make('article')
                    ->label('Article')
                    ->getStateUsing(fn (LeadMagnetRequest $record): string => $record->post === null
                        ? __('admin.lead_magnet.home_page')
                        : (string) $record->post->getTranslation('title'))
                    ->url(fn (LeadMagnetRequest $record): ?string => $record->post === null || $record->post->trashed()
                        ? null
                        : PostResource::getUrl('edit', ['record' => $record->post]))
                    ->limit(40),

                Tables\Columns\TextColumn::make('file_name')
                    ->label('File')
                    ->placeholder('—')
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email_status')
                    ->label('Email status')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('emailed_at')
                    ->label('Sent at')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email_attempts')
                    ->label('Attempts')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email_error')
                    ->label('Error')
                    ->color('danger')
                    ->limit(60)
                    ->tooltip(fn (LeadMagnetRequest $record): ?string => $record->email_error)
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])))
            ->filters(array_values(array_filter([
                Tables\Filters\SelectFilter::make('email_status')
                    ->label('Email status')
                    ->options(LeadMagnetEmailStatus::options()),

                $perArticle ? null : Tables\Filters\SelectFilter::make('post_id')
                    ->label('Article')
                    ->options(fn (): array => Post::query()
                        ->withTrashed()
                        ->whereHas('leadMagnetRequests')
                        ->withTranslations()
                        ->get()
                        ->mapWithKeys(fn (Post $post): array => [
                            $post->getKey() => (string) $post->getTranslation('title'),
                        ])
                        ->all())
                    ->searchable(),
            ])))
            ->headerActions([
                Action::make('export')
                    ->label(__('admin.lead_magnet.export'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function ($livewire): StreamedResponse {
                        $rows = $livewire->getFilteredTableQuery()
                            ->with('post.translations')
                            ->orderBy('created_at')
                            ->get();

                        return response()->streamDownload(function () use ($rows): void {
                            $handle = fopen('php://output', 'wb');
                            fputcsv($handle, ['Requested at', 'Name', 'Email', 'Article', 'File', 'Email status', 'Sent at', 'Error']);

                            foreach ($rows as $row) {
                                fputcsv($handle, [
                                    $row->created_at?->toDateTimeString(),
                                    $row->name,
                                    $row->email,
                                    $row->post?->getTranslation('title'),
                                    $row->file_name,
                                    $row->email_status?->value,
                                    $row->emailed_at?->toDateTimeString(),
                                    $row->email_error,
                                ]);
                            }

                            fclose($handle);
                        }, 'lead-magnet-requests-'.now()->toDateString().'.csv');
                    }),
            ])
            ->actions([
                Action::make('resend')
                    ->label(__('admin.lead_magnet.resend'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription(__('admin.lead_magnet.resend_confirm'))
                    ->visible(fn (LeadMagnetRequest $record): bool => auth()->user()?->can('update', $record) ?? false)
                    ->action(function (LeadMagnetRequest $record): void {
                        $queued = $record->resend();

                        Notification::make()
                            ->title(__($queued ? 'admin.lead_magnet.resend_queued' : 'admin.lead_magnet.resend_no_file'))
                            ->status($queued ? 'success' : 'danger')
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('resend')
                        ->label(__('admin.lead_magnet.resend'))
                        ->icon('heroicon-o-arrow-path')
                        ->requiresConfirmation()
                        ->modalDescription(__('admin.lead_magnet.resend_confirm'))
                        ->visible(fn (): bool => auth()->user()?->can('update_any_lead_magnet_request') ?? false)
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records): void {
                            $queued = $records->filter(fn (LeadMagnetRequest $record): bool => $record->resend())->count();

                            Notification::make()
                                ->title(__('admin.lead_magnet.resend_bulk_queued', ['count' => $queued]))
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('post.translations');
    }

    public static function getNavigationBadge(): ?string
    {
        $failed = LeadMagnetRequest::query()
            ->where('email_status', LeadMagnetEmailStatus::Failed)
            ->count();

        return $failed > 0 ? (string) $failed : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadMagnetRequests::route('/'),
        ];
    }
}
