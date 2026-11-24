<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resource;
use App\Filament\Resources\MediaResource\Pages;
use App\Filament\Support\TranslatableForm;
use App\Models\Media;
use App\Services\Media\MediaUsage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Media library — every image, video, audio file and document in one place,
 * each with per-locale SEO metadata (title, alt, caption, description) and a
 * schema.org type. Files are always uploaded, never linked by URL.
 *
 * The library alt is the site-wide fallback for any image field left without
 * one, and content image fields can reuse a file via "Choose from library"
 * (App\Filament\Support\ImageUpload).
 */
final class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website content';

    protected static ?string $navigationLabel = 'کتابخانه رسانه (Media)';

    protected static ?int $navigationSort = 99;

    /** Largest upload, in KB (256 MB — production's PHP limit). */
    public const MAX_KB = 262144;

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Section::make('File')
                ->columnSpan(fn (string $operation): int => $operation === 'create' ? 3 : 1)
                ->schema([
                    FileUpload::make('path')
                        ->label('فایل (File)')
                        ->helperText('عکس، ویدیو، صوت، PDF یا فایل آفیس — تا ۲۵۶ مگابایت. خود فایل را بارگذاری کنید؛ لینک فایل پذیرفته نمی‌شود.')
                        ->disk(Media::DISK)
                        ->directory(fn (): string => Media::LIBRARY_DIR.'/'.now()->format('Y/m'))
                        ->acceptedFileTypes(Media::allMimes())
                        ->maxSize(self::MAX_KB)
                        // Readable, unique, URL-safe names help image SEO.
                        ->getUploadedFileNameForStorageUsing(fn (TemporaryUploadedFile $file): string => (Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '-', 'en') ?: 'file')
                            .'-'.Str::lower(Str::random(6)).'.'.strtolower($file->getClientOriginalExtension()))
                        ->required()
                        ->visibleOn('create'),

                    Placeholder::make('preview')
                        ->hiddenLabel()
                        ->content(fn (?Media $record): HtmlString => self::preview($record))
                        ->visibleOn('edit'),

                    Placeholder::make('info')
                        ->label('مشخصات (Details)')
                        ->content(fn (?Media $record): HtmlString => new HtmlString($record === null ? '' : collect([
                            'نوع' => Media::TYPES[$record->type] ?? $record->type,
                            'فرمت' => $record->mime,
                            'حجم' => Number::fileSize($record->size),
                            'ابعاد' => $record->width ? "{$record->width}×{$record->height}" : null,
                            'مسیر' => $record->path,
                        ])->filter()->map(fn (string $v, string $k): string => '<div><strong>'.e($k).':</strong> <span style="word-break:break-all">'.e($v).'</span></div>')->implode('')))
                        ->visibleOn('edit'),

                    TextInput::make('url')
                        ->label('آدرس عمومی (Public URL)')
                        ->formatStateUsing(fn (?Media $record): ?string => $record?->url())
                        ->readOnly()
                        ->dehydrated(false)
                        ->suffixAction(\Filament\Forms\Components\Actions\Action::make('open')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->url(fn (?Media $record): ?string => $record?->url())
                            ->openUrlInNewTab())
                        ->visibleOn('edit'),
                ]),

            Section::make('سئو (SEO)')
                ->description('این اطلاعات را موتورهای جستجو می‌خوانند. alt برای دسترس‌پذیری تصویر لازم است؛ عنوان و توضیحات در اسکیما استفاده می‌شوند.')
                ->columnSpan(fn (string $operation): int => $operation === 'create' ? 3 : 2)
                ->schema([
                    TranslatableForm::tabs(fn (string $locale): array => [
                        TextInput::make("translations.{$locale}.title")
                            ->label('عنوان سئو (SEO title)')
                            ->helperText('نام فایل در نتایج جستجو و اسکیما.')
                            ->maxLength(300),
                        TextInput::make("translations.{$locale}.alt")
                            ->label('متن جایگزین (Alt text)')
                            ->helperText('آنچه تصویر نشان می‌دهد، در یک جمله. هرجا این فایل بدون alt خودش استفاده شود، همین نمایش داده می‌شود.')
                            ->maxLength(300),
                        TextInput::make("translations.{$locale}.caption")
                            ->label('زیرنویس (Caption)')
                            ->maxLength(500),
                        Textarea::make("translations.{$locale}.description")
                            ->label('توضیحات (Description)')
                            ->rows(3)
                            ->maxLength(2000),
                    ], 'اطلاعات سئو (SEO metadata)'),

                    Section::make('اسکیما (Schema)')
                        ->columns(2)
                        ->schema([
                            Toggle::make('schema_enabled')
                                ->label('خروجی اسکیما schema.org')
                                ->default(true)
                                ->live()
                                ->columnSpanFull(),
                            Select::make('schema_type')
                                ->label('نوع اسکیما (Schema type)')
                                ->options(Media::SCHEMA_TYPES)
                                ->placeholder('خودکار بر اساس نوع فایل (Automatic)')
                                ->native(false)
                                ->visible(fn (Get $get): bool => (bool) $get('schema_enabled')),
                            TextInput::make('credit')
                                ->label('منبع / صاحب اثر (Credit)')
                                ->maxLength(255)
                                ->visible(fn (Get $get): bool => (bool) $get('schema_enabled')),
                            Placeholder::make('schema_preview')
                                ->label('JSON-LD تولیدشده')
                                ->content(fn (?Media $record): HtmlString => new HtmlString($record?->schema() === null ? '—' :
                                    '<pre style="font-size:12px;white-space:pre-wrap;direction:ltr;text-align:left">'.e(json_encode($record->schema(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)).'</pre>'))
                                ->helperText('بعد از ذخیره به‌روز می‌شود.')
                                ->visibleOn('edit')
                                ->columnSpanFull(),
                        ]),

                    Section::make('ویدیو (Video)')
                        ->columns(2)
                        ->visible(fn (?Media $record): bool => $record?->isVideo() ?? false)
                        ->schema([
                            FileUpload::make('poster_path')
                                ->label('پوستر / تصویر بندانگشتی (Poster)')
                                ->image()
                                ->disk(Media::DISK)
                                ->directory(Media::LIBRARY_DIR.'/posters')
                                ->maxSize(5120)
                                ->helperText('پیش از پخش نمایش داده می‌شود؛ گوگل برای نتایج ویدیویی لازم دارد.'),
                            TextInput::make('duration')
                                ->label('مدت (ثانیه)')
                                ->numeric()
                                ->minValue(0),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->contentGrid(['md' => 3, 'xl' => 5])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('thumb')
                        ->getStateUsing(fn (Media $record): ?string => match (true) {
                            $record->isImage() => $record->url(),
                            $record->poster_path !== null => Storage::disk(Media::DISK)->url($record->poster_path),
                            default => null,
                        })
                        ->defaultImageUrl(fn (Media $record): string => self::typeIcon($record->type))
                        ->height(160)
                        ->width('100%')
                        ->extraImgAttributes(['class' => 'object-cover w-full rounded-lg', 'loading' => 'lazy']),
                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->weight('medium')
                        ->limit(32)
                        ->tooltip(fn (Media $record): string => $record->path),
                    Tables\Columns\TextColumn::make('type')
                        ->badge()
                        ->formatStateUsing(fn (string $state, Media $record): string => (Media::TYPES[$state] ?? $state)
                            .' · '.Number::fileSize($record->size)
                            .($record->width ? " · {$record->width}×{$record->height}" : '')),
                    Tables\Columns\TextColumn::make('alt_status')
                        ->getStateUsing(fn (Media $record): ?string => $record->isImage() && blank($record->getTranslation('alt')) ? 'بدون alt' : null)
                        ->color('danger')
                        ->size('xs'),
                ])->space(2),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options(Media::TYPES),
                Tables\Filters\Filter::make('missing_alt')
                    ->label('تصاویر بدون alt')
                    ->query(fn (Builder $q): Builder => $q->where('type', 'image')
                        ->whereDoesntHave('translations', fn (Builder $t) => $t->where('locale', app()->getLocale())->whereNotNull('alt')->where('alt', '!=', ''))),
                Tables\Filters\SelectFilter::make('folder')
                    ->options(fn (): array => collect(Storage::disk(Media::DISK)->directories())
                        ->reject(fn (string $d): bool => in_array($d, ['livewire-tmp', 'icons'], true))
                        ->mapWithKeys(fn (string $d): array => [$d => $d])
                        ->all())
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->where('path', 'like', $data['value'].'/%')
                        : $query),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconButton()
                    ->url(fn (Media $record): string => $record->url())
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->before(function (Media $record, Tables\Actions\DeleteAction $action): void {
                        if (! self::canRemoveFile($record)) {
                            $action->halt();
                        }
                    })
                    ->after(fn (Media $record) => self::deleteFiles($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('deleteUnused')
                    ->label('حذف فایل‌های انتخاب‌شده')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->action(function ($records): void {
                        $blocked = [];
                        foreach ($records as $media) {
                            if (! (auth()->user()?->can('delete', $media) ?? false)) {
                                $blocked[] = $media->name.' — مجوز حذف ندارید';
                                continue;
                            }
                            $uses = MediaUsage::find($media);
                            if ($uses !== []) {
                                $blocked[] = $media->name.': '.implode(' ؛ ', $uses);
                                continue;
                            }
                            $media->delete();
                            self::deleteFiles($media);
                        }
                        if ($blocked !== []) {
                            Notification::make()->danger()->persistent()->title('برخی فایل‌ها حذف نشدند')
                                ->body(implode(' | ', $blocked))->send();
                        } else {
                            Notification::make()->success()->title('فایل‌های انتخاب‌شده حذف شدند')->send();
                        }
                    }),
            ]);
    }

    public static function canRemoveFile(Media $media): bool
    {
        $uses = MediaUsage::find($media);
        if ($uses === []) {
            return true;
        }

        Notification::make()->danger()->persistent()->title('این فایل در حال استفاده است و حذف نشد')
            ->body(implode(' ؛ ', $uses))->send();

        return false;
    }

    public static function deleteFiles(Media $media): void
    {
        $disk = Storage::disk(Media::DISK);
        foreach (array_filter([$media->path]) as $file) {
            \App\Services\ImageOptimizer::purge($file); // responsive variants
        }
        $disk->delete(array_filter([$media->path]));
    }

    private static function preview(?Media $record): HtmlString
    {
        if ($record === null) {
            return new HtmlString('');
        }

        $url = e($record->url());
        $style = 'max-width:100%;border-radius:8px';

        return new HtmlString(match ($record->type) {
            'image' => "<img src=\"{$url}\" alt=\"\" style=\"{$style}\">",
            'video' => "<video src=\"{$url}\" controls preload=\"metadata\" style=\"{$style}\"".($record->poster_path ? ' poster="'.e(Storage::disk(Media::DISK)->url($record->poster_path)).'"' : '').'></video>',
            'audio' => "<audio src=\"{$url}\" controls style=\"width:100%\"></audio>",
            default => "<a href=\"{$url}\" target=\"_blank\" rel=\"noopener\" style=\"text-decoration:underline\">".e($record->name).'</a>',
        });
    }

    private static function typeIcon(string $type): string
    {
        $label = strtoupper(Media::TYPES[$type] ?? $type);

        return 'data:image/svg+xml;utf8,'.rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="320" height="160"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" fill="#6b7280" font-family="sans-serif" font-size="20" text-anchor="middle" dominant-baseline="middle">'.$label.'</text></svg>');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('translations');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
