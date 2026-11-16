<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resource;
use App\Filament\Resources\PostTagResource\Pages;
use App\Filament\Support\Seo\SeoContext;
use App\Filament\Support\Seo\SeoSection;
use App\Filament\Support\Seo\SeoTable;
use App\Filament\Support\TranslatableForm;
use App\Models\PostTag;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Blog tags. Locale-scoped slugs — the same slug string may exist in
 * different locales, but never twice within one.
 */
final class PostTagResource extends Resource
{
    protected static ?string $model = PostTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Articles';

    protected static ?string $navigationLabel = 'Article tags';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TranslatableForm::tabs(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.name")
                    ->label('Name')
                    ->required($locale === config('locales.fallback'))
                    ->maxLength(150)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, Set $set) use ($locale): void {
                        $set(
                            "translations.{$locale}.slug",
                            TranslatableForm::slugify($state, $locale),
                        );
                    }),
            ]),

            SeoSection::make(self::seoContext()),
        ]);
    }

    public static function seoContext(): SeoContext
    {
        return new SeoContext(titleField: 'name', slugField: 'slug', pathPrefix: 'insights/tag', slugTable: 'post_tag_translations', slugForeignKey: 'post_tag_id', defaultPageType: 'CollectionPage');
    }

    public static function table(Table $table): Table
    {
        return SeoTable::apply($table

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->getStateUsing(fn (PostTag $record): string => (string) $record->getTranslation('name'))
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('slug')
                    ->getStateUsing(fn (PostTag $record): string => (string) $record->getTranslation('slug'))
                    ->color('gray'),

                Tables\Columns\TextColumn::make('translations_count')
                    ->label('Locales')
                    ->counts('translations')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 3 ? 'success' : 'warning')
                    ->formatStateUsing(fn (int $state): string => "{$state}/3"),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostTags::route('/'),
            'create' => Pages\CreatePostTag::route('/create'),
            'edit' => Pages\EditPostTag::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('seoMeta')->with('translations');
    }
}
