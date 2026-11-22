<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\Seo\IndexNow;
use App\Services\SeoBuilder;
use App\Services\Sitemap\SitemapBuilder;
use App\Support\SiteSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

/**
 * XML sitemap control panel (Yoast "XML sitemaps" / Rank Math "Sitemap
 * settings"): which content types are listed, image entries, the browser
 * stylesheet, live URL counts and cache / IndexNow actions. Per-item
 * exclusion, priority and changefreq live in each record's SEO box.
 */
final class ManageSitemap extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'SEO';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'sitemap';

    protected static string $view = 'filament.pages.manage-sitemap';

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation.groups.SEO');
    }

    public static function getNavigationLabel(): string
    {
        return 'نقشه سایت (Sitemap)';
    }

    public function getTitle(): string
    {
        return 'نقشه سایت (XML Sitemap)';
    }

    public function mount(): void
    {
        $this->form->fill([
            'sitemap_types' => SitemapBuilder::enabledTypes(),
            'sitemap_images' => SitemapBuilder::imagesEnabled(),
            'sitemap_stylesheet' => SitemapBuilder::stylesheetEnabled(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')->schema([
            Section::make('تنظیمات نقشه سایت')->schema([
                CheckboxList::make('sitemap_types')
                    ->label('محتوای موجود در نقشه سایت (Included content types)')
                    ->options(SitemapBuilder::LABELS)
                    ->columns(4)
                    ->required()
                    ->helperText('برای حذف یک مورد خاص، در فرم همان مطلب ← جعبه سئو ← «حذف از نقشه سایت» را بزنید. موارد پیش‌نویس، noindex و دارای canonical خارجی خودکار حذف می‌شوند.'),
                Toggle::make('sitemap_images')
                    ->label('افزودن تصاویر (Image sitemap)')
                    ->helperText('تصویر شاخص و تصاویر داخل متن به هر آدرس اضافه می‌شود (برای Google Images).'),
                Toggle::make('sitemap_stylesheet')
                    ->label('نمایش خوانا در مرورگر (XSL stylesheet)')
                    ->helperText('فقط ظاهر نقشه را در مرورگر زیبا می‌کند؛ روی موتورهای جستجو اثری ندارد.'),
            ]),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        DB::transaction(function () use ($state): void {
            foreach (['sitemap_types', 'sitemap_images', 'sitemap_stylesheet'] as $key) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['group' => 'seo', 'value' => ['value' => $key === 'sitemap_types' ? array_values($state[$key]) : (bool) $state[$key]], 'is_translatable' => false],
                );
            }
        });

        SiteSettings::flush();
        SitemapBuilder::bust();

        Notification::make()->success()->title('تنظیمات نقشه سایت ذخیره شد')->send();
    }

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        return [
            'indexUrl' => route('sitemap'),
            'robotsUrl' => route('robots'),
            'stats' => app(SitemapBuilder::class)->stats(),
            'labels' => SitemapBuilder::LABELS,
            'siteNoindex' => SeoBuilder::siteNoindex(),
        ];
    }

    /** @return array<int, Action> */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')->label('مشاهده نقشه سایت')->icon('heroicon-o-arrow-top-right-on-square')
                ->url(route('sitemap'), shouldOpenInNewTab: true),
            Action::make('rebuild')->label('بازسازی کش')->icon('heroicon-o-arrow-path')->color('gray')
                ->action(function (): void {
                    SitemapBuilder::bust();
                    Notification::make()->success()->title('نقشه سایت از نو ساخته می‌شود')->send();
                }),
            Action::make('indexnow')->label('ارسال همه آدرس‌ها به IndexNow')->icon('heroicon-o-paper-airplane')->color('gray')
                ->visible(fn (): bool => IndexNow::enabled())
                ->requiresConfirmation()
                ->action(function (): void {
                    if (! app()->isProduction()) {
                        Notification::make()->warning()->title('ارسال IndexNow فقط در محیط production انجام می‌شود')->send();

                        return;
                    }
                    $urls = app(SitemapBuilder::class)->allUrls();
                    if ($urls === []) {
                        Notification::make()->warning()->title('آدرسی برای ارسال در نقشه سایت وجود ندارد')->send();

                        return;
                    }
                    foreach (array_chunk($urls, 10000) as $chunk) {
                        IndexNow::submit($chunk);
                    }
                    Notification::make()->success()->title(count($urls).' آدرس برای ارسال به IndexNow آماده شد')->send();
                }),
        ];
    }
}
