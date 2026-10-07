<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PublicationStatus;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Support\PublicationFields;
use App\Filament\Support\TranslatableForm;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Blog articles. Figma: listing 1353:7935, detail 1352:7391.
 */
final class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Articles';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TranslatableForm::tabs(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.title")
                    ->label('Title')
                    ->required($locale === config('locales.fallback'))
                    ->maxLength(250)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, Set $set) use ($locale): void {
                        $set(
                            "translations.{$locale}.slug",
                            TranslatableForm::slugify($state, $locale),
                        );
                    }),

                TextInput::make("translations.{$locale}.slug")
                    ->label('Slug')
                    ->required($locale === config('locales.fallback'))
                    ->maxLength(250),

                TextInput::make("translations.{$locale}.subtitle")
                    ->label('Subtitle')
                    ->maxLength(300),

                Textarea::make("translations.{$locale}.excerpt")
                    ->label('Excerpt')
                    ->rows(3)
                    ->helperText('Shown on listing cards and used as the SEO description fallback.'),

                RichEditor::make("translations.{$locale}.content")
                    ->label('Article body')
                    ->helperText('Insert [[lead_magnet]] on its own line wherever the Content Direction Checklist should appear.')
                    ->toolbarButtons([
                        'bold', 'italic', 'link', 'bulletList', 'orderedList',
                        'h2', 'h3', 'blockquote', 'codeBlock', 'undo', 'redo',
                    ]),

                TextInput::make("translations.{$locale}.cover_alt")
                    ->label('Cover alt text')
                    ->maxLength(300),

                Section::make('SEO')
                    ->collapsed()
                    ->schema([
                        TextInput::make("translations.{$locale}.seo_title")
                            ->label('SEO title')
                            ->maxLength(200),
                        Textarea::make("translations.{$locale}.seo_description")
                            ->label('SEO description')
                            ->rows(2)
                            ->maxLength(300),
                    ]),
            ]),

            Grid::make(3)->schema([
                Section::make('Details')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        Select::make('post_category_id')
                            ->label('Category')
                            ->relationship('category')
                            ->getOptionLabelFromRecordUsing(
                                fn (PostCategory $record): string => (string) $record->getTranslation('name'),
                            )
                            ->searchable()
                            ->preload()
                            ->native(false),

                        Select::make('tags')
                            ->relationship('tags')
                            ->getOptionLabelFromRecordUsing(
                                fn (PostTag $record): string => (string) $record->getTranslation('name'),
                            )
                            ->multiple()
                            ->preload()
                            ->native(false),

                        Select::make('user_id')
                            ->label('Author')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id())
                            ->native(false),

                        TextInput::make('reading_minutes')
                            ->label('Reading time (minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->helperText('Recalculated automatically on save.'),

                        Toggle::make('is_featured')
                            ->label('Show as the main article')
                            ->helperText('Displays this article as the large card at the top of the Insights page.'),
                    ]),

                Grid::make(1)->columnSpan(1)->schema([
                    PublicationFields::section(),

                    Section::make('Cover')->schema([
                        FileUpload::make('cover_path')
                            ->label('Cover image')
                            ->image()
                            ->imageEditor()
                            ->directory('posts')
                            ->disk('public'),
                    ]),

                    // What the in-article checklist form (LeadMagnetModal)
                    // hands out. Who asked and whether their email went out
                    // is listed under the form (LeadMagnetRequestsRelationManager).
                    Section::make('Lead magnet')
                        ->description(fn (): string => __('admin.lead_magnet.section_description'))
                        ->schema([
                            FileUpload::make('lead_magnet_path')
                                ->label('Lead magnet file')
                                // Private disk: never publicly linkable, only
                                // ever leaves the server as a mail attachment.
                                ->disk('local')
                                ->directory('lead-magnets')
                                ->visibility('private')
                                ->storeFileNamesIn('lead_magnet_name')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/zip',
                                    'application/x-zip-compressed',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.ms-powerpoint',
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                    'image/png',
                                    'image/jpeg',
                                ])
                                // Mail servers commonly reject attachments past ~10–25 MB.
                                ->maxSize(10 * 1024)
                                ->downloadable()
                                ->required(fn (Get $get): bool => (bool) $get('lead_magnet_send_email'))
                                ->helperText(fn (): string => __('admin.lead_magnet.file_help')),

                            Toggle::make('lead_magnet_send_email')
                                ->label('Email the file to readers')
                                ->live()
                                ->helperText(fn (): string => __('admin.lead_magnet.send_email_help')),
                        ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_path')
                    ->label('')
                    ->disk('public')
                    ->height(40)
                    ->width(64),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn (Post $record): string => (string) $record->getTranslation('title'))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->whereHas(
                        'translations',
                        fn (Builder $q) => $q->where('title', 'like', "%{$search}%"),
                    ))
                    ->limit(50)
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->getStateUsing(fn (Post $record): string => (string) ($record->category?->getTranslation('name') ?? '—'))
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')->badge(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Main article')
                    ->boolean(),

                Tables\Columns\TextColumn::make('lead_magnet_requests_count')
                    ->label('Lead requests')
                    ->counts('leadMagnetRequests')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('translations_count')
                    ->label('Locales')
                    ->counts('translations')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 3 ? 'success' : 'warning')
                    ->formatStateUsing(fn (int $state): string => "{$state}/3"),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PublicationStatus::options()),

                Tables\Filters\SelectFilter::make('category')
                    ->relationship(
                        'category',
                        'name',
                        fn (Builder $query): Builder => $query->withTranslations()->ordered(),
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (PostCategory $record): string => (string) $record->getTranslation('name'),
                    ),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Main article'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LeadMagnetRequestsRelationManager::class,
        ];
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['translations', 'category.translations', 'author'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
