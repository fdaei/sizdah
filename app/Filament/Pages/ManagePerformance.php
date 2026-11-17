<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\Performance\PageCache;
use App\Support\Performance;
use App\Support\SiteSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

/**
 * WP Rocket-style performance controls: HTML minify, page cache (+ purge),
 * lazy media / video facades, and resource hints.
 */
final class ManagePerformance extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation.groups.Performance');
    }

    public static function getNavigationLabel(): string
    {
        return 'تنظیمات عملکرد';
    }

    public function getTitle(): string
    {
        return 'عملکرد و سرعت (Core Web Vitals)';
    }

    public function mount(): void
    {
        $this->form->fill(collect(Performance::DEFAULTS)
            ->map(fn (mixed $default, string $key): mixed => Performance::get($key))
            ->all());
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('purge')
                ->label('پاک کردن کش')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('کش صفحات، تنظیمات و متن‌ها پاک می‌شود. اولین بازدید بعدی کمی کندتر خواهد بود.')
                ->action(function (): void {
                    PageCache::flush();
                    SiteSettings::flush();
                    Notification::make()->title('کش پاک شد')->success()->send();
                }),
        ];
    }

    public function form(Form $form): Form
    {
        $urlList = fn (string $key, string $label, string $help): Repeater => Repeater::make($key)
            ->label($label)
            ->helperText($help)
            ->simple(TextInput::make('url')->required()->maxLength(500)->extraInputAttributes(['dir' => 'ltr'])
                ->regex('#^(/|https?://)#i')->placeholder('https://example.com'))
            ->defaultItems(0)
            ->reorderable(false);

        return $form->statePath('data')->schema([
            Section::make('کش و فشرده‌سازی')->columns(2)->schema([
                Toggle::make('perf_page_cache')->label('کش صفحات (Page cache)')->live()
                    ->helperText('صفحات عمومی برای بازدیدکنندگان مهمان از کش سرو می‌شوند. با ذخیره هر محتوا یا تنظیم، خودکار پاک می‌شود.'),
                TextInput::make('perf_page_cache_ttl')->label('مدت نگهداری (ثانیه)')->numeric()->minValue(60)->maxValue(604800)
                    ->visible(fn (Get $get): bool => (bool) $get('perf_page_cache')),
                Toggle::make('perf_minify_html')->label('فشرده‌سازی HTML (Minify)')
                    ->helperText('کامنت‌ها و فاصله‌های اضافه حذف می‌شود؛ pre، textarea، script و style دست‌نخورده می‌مانند. CSS/JS توسط Vite فشرده می‌شوند.'),
            ]),
            Section::make('بارگذاری تنبل (Lazy load)')->columns(2)->schema([
                Toggle::make('perf_lazy_media')->label('Lazy load تصاویر و iframeهای محتوا')
                    ->helperText('loading="lazy" و decoding="async" به تصاویر و iframeهای متن مقاله اضافه می‌شود.'),
                Toggle::make('perf_video_facade')->label('نمای جایگزین ویدیو (YouTube facade)')
                    ->helperText('تا قبل از کلیک فقط تصویر بندانگشتی بارگذاری می‌شود.'),
            ]),
            Section::make('Preload / Preconnect / Prefetch')->schema([
                $urlList('perf_preconnect', 'Preconnect', 'دامنه‌های خارجی که زود به آن‌ها وصل می‌شویم (مثلاً CDN یا Google Tag Manager).'),
                $urlList('perf_dns_prefetch', 'DNS prefetch', 'دامنه‌هایی که فقط DNS آن‌ها از قبل resolve شود.'),
                Repeater::make('perf_preload')->label('Preload')->defaultItems(0)->columns(3)
                    ->helperText('فونت‌های اصلی سایت از قبل preload می‌شوند؛ اینجا موارد اضافه را وارد کنید.')
                    ->schema([
                        TextInput::make('url')->label('آدرس')->required()->maxLength(500)->regex('#^(/|https?://)#i')->extraInputAttributes(['dir' => 'ltr']),
                        Select::make('as')->label('نوع')->required()->native(false)
                            ->options(['font' => 'font', 'image' => 'image', 'style' => 'style', 'script' => 'script', 'fetch' => 'fetch']),
                        TextInput::make('type')->label('MIME')->placeholder('font/woff2')->maxLength(50)->extraInputAttributes(['dir' => 'ltr']),
                    ]),
                $urlList('perf_prefetch', 'Prefetch', 'صفحات یا فایل‌هایی که احتمالاً بازدیدکننده بعداً به آن‌ها می‌رود.'),
            ]),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        DB::transaction(function () use ($state): void {
            foreach (array_keys(Performance::DEFAULTS) as $key) {
                $value = $state[$key] ?? Performance::DEFAULTS[$key];
                Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['group' => 'performance', 'is_translatable' => false, 'value' => ['value' => $value]],
                );
            }
        });

        Notification::make()->title('تنظیمات عملکرد ذخیره شد')->success()->send();
    }
}
