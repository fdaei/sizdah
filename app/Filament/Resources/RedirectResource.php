<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resource;
use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use App\Services\Redirects\RedirectResolver;
use Closure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Redirections (Rank Math parity): exact/regex sources, 301/302/307/308,
 * 410/451 "gone", hit counter, CSV import/export, chain/loop protection.
 * Applied by App\Http\Middleware\ApplyRedirects.
 */
final class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationGroup = 'SEO';

    protected static ?string $navigationLabel = 'ریدایرکت‌ها (Redirections)';

    protected static ?string $modelLabel = 'ریدایرکت';

    protected static ?string $pluralModelLabel = 'ریدایرکت‌ها (Redirections)';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $gone = fn (Get $get): bool => in_array((int) $get('status_code'), Redirect::GONE, true);
        $host = rtrim((string) config('app.url'), '/').'/';

        return $form->schema([
            Section::make()
                ->columns(2)
                ->schema([
                    Select::make('match_type')
                        ->label('نوع تطبیق (Match type)')
                        ->options(['exact' => 'دقیق (Exact)', 'regex' => 'عبارت باقاعده (Regex)'])
                        ->default('exact')
                        ->live()
                        ->native(false)
                        ->required(),

                    Select::make('status_code')
                        ->label('نوع ریدایرکت (Redirection type)')
                        ->options(Redirect::STATUSES)
                        ->default(301)
                        ->live()
                        ->native(false)
                        ->required(),

                    TextInput::make('source_path')
                        ->label('آدرس مبدأ (Source URL)')
                        ->required()
                        ->maxLength(500)
                        ->columnSpanFull()
                        ->extraInputAttributes(['dir' => 'ltr'])
                        ->prefix(fn (Get $get): ?string => $get('match_type') === 'regex' ? null : $host)
                        ->dehydrateStateUsing(fn (?string $state, Get $get): string => $get('match_type') === 'regex'
                            ? trim((string) $state)
                            : Redirect::normalize((string) $state))
                        ->rule(fn (Get $get, ?Model $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get, $record): void {
                            $normalized = $get('match_type') === 'regex' ? trim((string) $value) : Redirect::normalize((string) $value);
                            if (Redirect::query()->where('source_path', $normalized)->when($record?->exists, fn ($q) => $q->whereKeyNot($record->getKey()))->exists()) {
                                $fail('برای این آدرس مبدأ قبلاً ریدایرکت ساخته شده است.');
                            }
                            if ($get('match_type') === 'regex' && RedirectResolver::delimit((string) $value) === null) {
                                $fail('عبارت باقاعده (regex) معتبر نیست.');
                            }
                            if ($get('match_type') !== 'regex' && str_starts_with(Redirect::normalize((string) $value), 'admin')) {
                                $fail('ریدایرکت پنل مدیریت مجاز نیست.');
                            }
                        })
                        ->helperText(fn (Get $get): string => $get('match_type') === 'regex'
                            ? 'مثال: ^blog/(\d+)$ — در مقصد با $1 به گروه اول اشاره کنید. با مسیر بدون / ابتدایی تطبیق داده می‌شود.'
                            : 'آدرس قدیمی؛ کامل (https://…) یا فقط مسیر (fa/old-page). حروف فارسی و آدرس کدشده هر دو قبول است.'),

                    TextInput::make('destination_path')
                        ->label('آدرس مقصد (Destination URL)')
                        ->maxLength(500)
                        ->columnSpanFull()
                        ->extraInputAttributes(['dir' => 'ltr'])
                        ->required(fn (Get $get): bool => ! $gone($get))
                        ->hidden($gone)
                        ->rule(fn (Get $get, ?Model $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                            if ($get('match_type') === 'regex' || blank($value)) {
                                return;
                            }
                            $source = Redirect::normalize((string) $get('source_path'));
                            if (Redirect::normalize((string) $value) === $source && ! preg_match('#^https?://#i', (string) $value)) {
                                $fail('مقصد نمی‌تواند همان مبدأ باشد.');

                                return;
                            }
                            if (app(RedirectResolver::class)->finalDestination($source, (string) $value) === null) {
                                $fail('این ریدایرکت یک حلقه (loop) می‌سازد: مقصد دوباره به همین آدرس برمی‌گردد.');
                            }
                        })
                        ->helperText('مسیر داخلی (fa/new-page) یا آدرس کامل خارجی. اگر مقصد خودش ریدایرکت شده باشد، زنجیره خودکار به مقصد نهایی کوتاه می‌شود.'),

                    TextInput::make('notes')
                        ->label('یادداشت (Notes)')
                        ->maxLength(500)
                        ->columnSpanFull(),

                    Toggle::make('is_active')->label('فعال (Active)')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('source_path')
                    ->label('مبدأ (From)')
                    ->searchable()
                    ->limit(60)
                    ->tooltip(fn (Redirect $r): string => $r->source_path)
                    ->extraAttributes(['dir' => 'ltr'])
                    ->copyable(),
                Tables\Columns\TextColumn::make('destination_path')
                    ->label('مقصد (To)')
                    ->searchable()
                    ->limit(60)
                    ->placeholder('—')
                    ->extraAttributes(['dir' => 'ltr']),
                Tables\Columns\TextColumn::make('status_code')->label('کد')->badge()
                    ->color(fn (int $state): string => in_array($state, Redirect::GONE, true) ? 'danger' : ($state === 301 || $state === 308 ? 'success' : 'warning')),
                Tables\Columns\TextColumn::make('match_type')->label('تطبیق')->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'regex' ? 'Regex' : 'Exact'),
                Tables\Columns\TextColumn::make('origin')->label('منبع')->badge()->toggleable()
                    ->formatStateUsing(fn (string $state): string => ['manual' => 'دستی', 'auto' => 'خودکار (تغییر نامک)', 'import' => 'درون‌ریزی', '404' => 'از ۴۰۴'][$state] ?? $state),
                Tables\Columns\TextColumn::make('hits')->label('بازدید (Hits)')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('last_hit_at')->label('آخرین بازدید')->since()->sortable()->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->label('فعال')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('فعال'),
                Tables\Filters\SelectFilter::make('status_code')->label('کد')->options(Redirect::STATUSES),
                Tables\Filters\SelectFilter::make('origin')->label('منبع')
                    ->options(['manual' => 'دستی', 'auto' => 'خودکار', 'import' => 'درون‌ریزی', '404' => 'از ۴۰۴']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('deactivate')->label('غیرفعال کردن')->icon('heroicon-o-pause')
                    ->action(fn ($records) => $records->each->update(['is_active' => false])),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}
