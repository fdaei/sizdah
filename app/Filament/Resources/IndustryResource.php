<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resource;
use App\Filament\Resources\IndustryResource\Pages;
use App\Filament\Support\Seo\SeoContext;
use App\Filament\Support\Seo\SeoSection;
use App\Filament\Support\Seo\SeoTable;
use App\Filament\Support\TranslatableForm;
use App\Models\Industry;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Project industries. Locale-scoped slugs — the same slug string may exist in
 * different locales, but never twice within one.
 */
final class IndustryResource extends Resource
{
    protected static ?string $model = Industry::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Project categories';

    protected static ?int $navigationSort = 2;

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

            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('sort_order')->numeric()->default(0),
                ]),

            SeoSection::make(self::seoContext()),
        ]);
    }

    public static function seoContext(): SeoContext
    {
        return new SeoContext(titleField: 'name', slugField: 'slug', pathPrefix: 'work/industry', slugTable: 'industry_translations', slugForeignKey: 'industry_id', defaultPageType: 'CollectionPage');
    }

    public static function table(Table $table): Table
    {
        return SeoTable::apply($table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->getStateUsing(fn (Industry $record): string => (string) $record->getTranslation('name'))
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('slug')
                    ->getStateUsing(fn (Industry $record): string => (string) $record->getTranslation('slug'))
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
            'index' => Pages\ListIndustries::route('/'),
            'create' => Pages\CreateIndustry::route('/create'),
            'edit' => Pages\EditIndustry::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('seoMeta')->with('translations');
    }
}
