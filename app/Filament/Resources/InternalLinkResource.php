<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resource;
use App\Filament\Resources\InternalLinkResource\Pages;
use App\Models\InternalLink;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

/**
 * "Keyword → URL" auto internal-linking rules (Rank Math / Link Whisper style).
 * Applied when articles render; stored article content is never modified.
 */
final class InternalLinkResource extends Resource
{
    protected static ?string $model = InternalLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'SEO';

    protected static ?string $navigationLabel = 'لینک‌سازی داخلی خودکار';

    protected static ?string $modelLabel = 'قانون لینک';

    protected static ?string $pluralModelLabel = 'لینک‌سازی داخلی خودکار';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        $locales = collect(config('locales.supported'))->map(fn (array $c): string => $c['native'])->all();

        return $form->schema([
            Section::make()->columns(2)->schema([
                TextInput::make('keyword')->label('کلمه کلیدی')->required()->maxLength(190)
                    ->helperText('تطبیق بدون حساسیت به حروف بزرگ/کوچک، ی/ک عربی، نیم‌فاصله و اعراب.')
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('locale', $get('locale'))),
                TextInput::make('url')->label('آدرس مقصد')->required()->maxLength(500)
                    ->regex('#^(/|https?://)#i')->extraInputAttributes(['dir' => 'ltr'])
                    ->placeholder('/fa/services/seo یا https://…'),
                Select::make('locale')->label('زبان')->options($locales)->default(config('locales.default'))->required()->native(false),
                TextInput::make('max_per_page')->label('حداکثر لینک در هر صفحه')->numeric()->minValue(1)->maxValue(10)->default(1)->required(),
                Toggle::make('is_active')->label('فعال')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('keyword')
            ->columns([
                Tables\Columns\TextColumn::make('keyword')->label('کلمه کلیدی')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('url')->label('مقصد')->limit(50)->extraAttributes(['dir' => 'ltr']),
                Tables\Columns\TextColumn::make('locale')->label('زبان')->badge(),
                Tables\Columns\TextColumn::make('max_per_page')->label('حداکثر'),
                Tables\Columns\ToggleColumn::make('is_active')->label('فعال'),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('وضعیت')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternalLinks::route('/'),
            'create' => Pages\CreateInternalLink::route('/create'),
            'edit' => Pages\EditInternalLink::route('/{record}/edit'),
        ];
    }
}
