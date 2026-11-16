<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\Seo\IndexNow;
use App\Services\Seo\SeoTitle;
use App\Support\SiteSettings;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

/**
 * Global SEO settings — Yoast "Settings" / Rank Math "Titles & Meta" +
 * "Local SEO" + "Webmaster tools" in one page. Stored in the key/value
 * `settings` table (same shape as ManageSettings), read via SiteSettings.
 */
final class ManageSeoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'Website setup';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'seo-settings';

    protected static string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    /**
     * key => translatable?
     */
    public const KEYS = [
        // General / titles
        'seo_title_separator' => false,
        'seo_title_template' => true,
        'seo_title_template_website' => true,
        'seo_title_template_article' => true,
        'seo_default_title' => true,
        'seo_default_description' => true,
        'seo_default_image' => false,
        'seo_default_twitter_card' => false,
        // Knowledge Graph / Local SEO
        'seo_kg_type' => false,
        'seo_organization_name' => true,
        'seo_kg_alternate_name' => true,
        'seo_kg_logo' => false,
        'seo_kg_business_type' => false,
        'seo_kg_street' => true,
        'seo_kg_locality' => true,
        'seo_kg_region' => true,
        'seo_kg_postal_code' => false,
        'seo_kg_country' => false,
        'seo_kg_lat' => false,
        'seo_kg_lng' => false,
        'seo_kg_phone' => false,
        'seo_kg_email' => false,
        'seo_kg_price_range' => false,
        'seo_kg_area_served' => true,
        'seo_kg_hours' => false,
        'seo_schema_reviews' => false,
        // Webmaster tools
        'seo_verify_google' => false,
        'seo_verify_bing' => false,
        'seo_verify_yandex' => false,
        'seo_verify_baidu' => false,
        'seo_indexnow_enabled' => false,
        // Search appearance / indexing
        'seo_noindex_site' => false,
        'seo_noindex_paginated' => false,
        'seo_noindex_search' => false,
        'seo_noindex_post_category' => false,
        'seo_noindex_post_tag' => false,
        // Breadcrumbs
        'seo_breadcrumbs_enabled' => false,
        'seo_breadcrumb_home' => true,
        // robots.txt + code
        'seo_robots_txt' => false,
        'seo_head_code' => false,
        'seo_body_code' => false,
    ];

    /** Defaults applied when a key has never been saved. */
    private const DEFAULTS = [
        'seo_title_separator' => '—',
        'seo_default_twitter_card' => 'summary_large_image',
        'seo_kg_type' => 'Organization',
        'seo_kg_country' => 'IR',
        'seo_noindex_search' => true,
        'seo_breadcrumbs_enabled' => true,
    ];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation.groups.Website setup');
    }

    public static function getNavigationLabel(): string
    {
        return 'تنظیمات سئو (SEO settings)';
    }

    public function getTitle(): string
    {
        return 'تنظیمات سئو (SEO settings)';
    }

    public function mount(): void
    {
        $stored = Setting::query()->whereIn('key', array_keys(self::KEYS))->get()->keyBy('key');
        $data = [];

        foreach (self::KEYS as $key => $translatable) {
            $value = $stored->get($key)?->value;

            if ($translatable) {
                foreach (array_keys(config('locales.supported')) as $locale) {
                    $data[$key][$locale] = is_array($value) ? ($value[$locale] ?? '') : '';
                }
            } else {
                $data[$key] = is_array($value) && array_key_exists('value', $value)
                    ? $value['value']
                    : (self::DEFAULTS[$key] ?? null);
            }
        }

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('seo-settings')
                    ->persistTabInQueryString()
                    ->tabs([
                        $this->generalTab(),
                        $this->knowledgeGraphTab(),
                        $this->searchAppearanceTab(),
                        $this->webmasterTab(),
                        $this->robotsTab(),
                        $this->codeTab(),
                    ]),
            ]);
    }

    private function generalTab(): Tabs\Tab
    {
        return Tabs\Tab::make('عمومی (General)')
            ->icon('heroicon-o-adjustments-horizontal')
            ->schema([
                Select::make('seo_title_separator')
                    ->label('جداکننده عنوان (Title separator)')
                    ->native(false)
                    ->options(array_combine(SeoTitle::SEPARATORS, SeoTitle::SEPARATORS))
                    ->helperText('مقدار متغیر %sep% در قالب‌های عنوان.'),

                ...$this->perLocale('seo_title_template', fn (string $p) => TextInput::make($p)
                    ->label('قالب عنوان پیش‌فرض (Default title template)')
                    ->placeholder(SeoTitle::DEFAULT_TEMPLATE)
                    ->helperText('متغیرها: %title% · %sep% · %sitename% · %tagline% · %page%')),

                ...$this->perLocale('seo_title_template_website', fn (string $p) => TextInput::make($p)
                    ->label('قالب عنوان صفحات و خدمات و پروژه‌ها (Pages template)')
                    ->placeholder('خالی = قالب پیش‌فرض')),

                ...$this->perLocale('seo_title_template_article', fn (string $p) => TextInput::make($p)
                    ->label('قالب عنوان مقالات (Posts template)')
                    ->placeholder('خالی = قالب پیش‌فرض')),

                ...$this->perLocale('seo_default_title', fn (string $p) => TextInput::make($p)
                    ->label('عنوان پیش‌فرض (Default title)')
                    ->helperText('وقتی محتوا عنوانی ندارد.')
                    ->maxLength(300)),

                ...$this->perLocale('seo_default_description', fn (string $p) => Textarea::make($p)
                    ->label('توضیحات متای پیش‌فرض (Default meta description)')
                    ->rows(2)
                    ->maxLength(500)),

                Grid::make(2)->schema([
                    FileUpload::make('seo_default_image')
                        ->label('تصویر اشتراک‌گذاری پیش‌فرض (Default OG image)')
                        ->image()
                        ->directory('seo')
                        ->disk('public')
                        ->helperText('۱۲۰۰×۶۳۰ پیکسل. وقتی محتوا تصویری ندارد.'),

                    Select::make('seo_default_twitter_card')
                        ->label('نوع کارت توییتر پیش‌فرض (Default X card)')
                        ->native(false)
                        ->options([
                            'summary_large_image' => 'تصویر بزرگ (summary_large_image)',
                            'summary' => 'خلاصه (summary)',
                        ]),
                ]),
            ]);
    }

    private function knowledgeGraphTab(): Tabs\Tab
    {
        $isOrg = fn (Get $get): bool => $get('seo_kg_type') !== 'Person';
        $isLocal = fn (Get $get): bool => $get('seo_kg_type') !== 'Person' && filled($get('seo_kg_business_type'));

        return Tabs\Tab::make('نمودار دانش و سئوی محلی (Knowledge Graph & Local SEO)')
            ->icon('heroicon-o-building-storefront')
            ->schema([
                Select::make('seo_kg_type')
                    ->label('سایت نماینده‌ی کیست؟ (Organization or Person)')
                    ->native(false)
                    ->live()
                    ->options(['Organization' => 'سازمان / شرکت (Organization)', 'Person' => 'شخص (Person)']),

                ...$this->perLocale('seo_organization_name', fn (string $p) => TextInput::make($p)
                    ->label('نام (Name)')
                    ->maxLength(300)),

                ...$this->perLocale('seo_kg_alternate_name', fn (string $p) => TextInput::make($p)
                    ->label('نام جایگزین (Alternate name)')
                    ->helperText('مثلاً نام انگلیسی یا مخفف برند.')
                    ->maxLength(300)),

                FileUpload::make('seo_kg_logo')
                    ->label('لوگو (Logo)')
                    ->image()
                    ->directory('seo')
                    ->disk('public')
                    ->helperText('مربع، حداقل ۱۱۲×۱۱۲ پیکسل. خالی = آیکون ۵۱۲ سایت.')
                    ->visible($isOrg),

                Select::make('seo_kg_business_type')
                    ->label('نوع کسب‌وکار محلی (Local business type)')
                    ->native(false)
                    ->placeholder('بدون سئوی محلی')
                    ->live()
                    ->visible($isOrg)
                    ->options([
                        'ProfessionalService' => 'خدمات حرفه‌ای (ProfessionalService)',
                        'LocalBusiness' => 'کسب‌وکار محلی (LocalBusiness)',
                        'MarketingAgency' => 'آژانس بازاریابی (MarketingAgency — schema.org pending)',
                    ]),

                Grid::make(2)->visible($isLocal)->schema([
                    ...$this->perLocale('seo_kg_street', fn (string $p) => TextInput::make($p)->label('نشانی (Street address)')),
                    ...$this->perLocale('seo_kg_locality', fn (string $p) => TextInput::make($p)->label('شهر (City)')->placeholder('کرمان')),
                    ...$this->perLocale('seo_kg_region', fn (string $p) => TextInput::make($p)->label('استان (Region)')->placeholder('کرمان')),
                    TextInput::make('seo_kg_postal_code')->label('کد پستی (Postal code)')->extraInputAttributes(['dir' => 'ltr']),
                    TextInput::make('seo_kg_country')->label('کشور (Country code)')->maxLength(2)->extraInputAttributes(['dir' => 'ltr']),
                    TextInput::make('seo_kg_lat')->label('عرض جغرافیایی (Latitude)')->numeric()->extraInputAttributes(['dir' => 'ltr'])
                        ->rule('between:-90,90'),
                    TextInput::make('seo_kg_lng')->label('طول جغرافیایی (Longitude)')->numeric()->extraInputAttributes(['dir' => 'ltr'])
                        ->rule('between:-180,180'),
                    TextInput::make('seo_kg_phone')->label('تلفن (Phone)')->tel()->extraInputAttributes(['dir' => 'ltr'])
                        ->placeholder('+98 34 ...'),
                    TextInput::make('seo_kg_email')->label('ایمیل (Email)')->email()->extraInputAttributes(['dir' => 'ltr']),
                    TextInput::make('seo_kg_price_range')->label('محدوده قیمت (Price range)')->placeholder('$$')->maxLength(10),
                    ...$this->perLocale('seo_kg_area_served', fn (string $p) => TextInput::make($p)->label('محدوده خدمت‌رسانی (Area served)')->placeholder('ایران')),
                ]),

                Repeater::make('seo_kg_hours')
                    ->label('ساعات کاری (Opening hours)')
                    ->visible($isLocal)
                    ->addActionLabel('افزودن بازه')
                    ->columns(3)
                    ->defaultItems(0)
                    ->schema([
                        CheckboxList::make('days')
                            ->label('روزها')
                            ->columns(4)
                            ->columnSpanFull()
                            ->required()
                            ->options([
                                'Saturday' => 'شنبه', 'Sunday' => 'یکشنبه', 'Monday' => 'دوشنبه', 'Tuesday' => 'سه‌شنبه',
                                'Wednesday' => 'چهارشنبه', 'Thursday' => 'پنجشنبه', 'Friday' => 'جمعه',
                            ]),
                        TimePicker::make('opens')->label('از')->seconds(false)->required(),
                        TimePicker::make('closes')->label('تا')->seconds(false)->required(),
                    ]),

                Toggle::make('seo_schema_reviews')
                    ->label('نظرات مشتریان در اسکیما (Testimonials as Review schema)')
                    ->helperText('پیش‌فرض خاموش. گوگل برای نظرات «سازمان درباره‌ی خودش» ستاره نشان نمی‌دهد و نظرات امتیاز ندارند، پس AggregateRating ساخته نمی‌شود.'),

                Placeholder::make('kg_same_as')
                    ->label('پروفایل‌های اجتماعی (sameAs)')
                    ->content(new HtmlString('به‌طور خودکار از «Social media links» خوانده می‌شود.')),
            ]);
    }

    private function searchAppearanceTab(): Tabs\Tab
    {
        return Tabs\Tab::make('نمایش در جستجو (Search appearance)')
            ->icon('heroicon-o-eye')
            ->schema([
                Toggle::make('seo_noindex_site')
                    ->label('جلوگیری از ایندکس کل سایت (Discourage search engines)')
                    ->helperText(new HtmlString('<b style="color:#dc2626">خطرناک:</b> همه‌ی صفحات noindex می‌شوند — فقط برای سایت آزمایشی (staging). روی سرور با APP_ENV=staging خودکار فعال است.')),

                Toggle::make('seo_noindex_search')->label('noindex برای نتایج جستجوی داخلی (Search results)'),
                Toggle::make('seo_noindex_paginated')->label('noindex برای صفحه ۲ به بعد آرشیوها (Paginated pages)')
                    ->helperText('توصیه‌ی فعلی گوگل: خاموش بماند (صفحات بعدی self-canonical و قابل ایندکس).'),
                Toggle::make('seo_noindex_post_category')->label('noindex برای دسته‌بندی مقالات (Categories)'),
                Toggle::make('seo_noindex_post_tag')->label('noindex برای برچسب‌های مقالات (Tags)'),

                Toggle::make('seo_breadcrumbs_enabled')
                    ->label('بردکرامب در اسکیما (Breadcrumbs schema)')
                    ->live(),
                ...$this->perLocale('seo_breadcrumb_home', fn (string $p) => TextInput::make($p)
                    ->label('عنوان «خانه» در بردکرامب (Breadcrumb home label)')
                    ->placeholder(__('seo.breadcrumbs.home'))),
            ]);
    }

    private function webmasterTab(): Tabs\Tab
    {
        $code = fn (string $key, string $label, string $meta): TextInput => TextInput::make($key)
            ->label($label)
            ->extraInputAttributes(['dir' => 'ltr'])
            ->helperText("فقط مقدار content از تگ {$meta}؛ اگر کل تگ را بچسبانید، خودکار استخراج می‌شود.")
            ->dehydrateStateUsing(fn (?string $state): ?string => self::extractContent($state));

        return Tabs\Tab::make('ابزارهای وبمستر (Webmaster tools)')
            ->icon('heroicon-o-check-badge')
            ->schema([
                $code('seo_verify_google', 'Google Search Console', 'google-site-verification'),
                $code('seo_verify_bing', 'Bing Webmaster Tools', 'msvalidate.01'),
                $code('seo_verify_yandex', 'Yandex Webmaster', 'yandex-verification'),
                $code('seo_verify_baidu', 'Baidu Webmaster', 'baidu-site-verification'),

                Toggle::make('seo_indexnow_enabled')
                    ->label('IndexNow — اطلاع فوری به بینگ و یاندکس هنگام انتشار')
                    ->live()
                    ->helperText(fn (): HtmlString => new HtmlString(
                        'کلید به‌طور خودکار ساخته می‌شود و در <span dir="ltr"><a href="'.e(url('/'.IndexNow::ensureKey().'.txt')).'" target="_blank" style="text-decoration:underline">'.e(IndexNow::ensureKey()).'.txt</a></span> قرار دارد. گوگل از IndexNow پشتیبانی نمی‌کند؛ برای گوگل نقشه سایت کافی است.'
                    )),
            ]);
    }

    private function robotsTab(): Tabs\Tab
    {
        return Tabs\Tab::make('ویرایش robots.txt')
            ->icon('heroicon-o-document-text')
            ->schema([
                Textarea::make('seo_robots_txt')
                    ->label('محتوای robots.txt')
                    ->rows(12)
                    ->placeholder("User-agent: *\nAllow: /\n\nDisallow: /admin\nDisallow: /admin/*")
                    ->extraInputAttributes(['dir' => 'ltr', 'style' => 'font-family: ui-monospace, monospace'])
                    ->helperText(new HtmlString('خالی = پیش‌فرض بالا. خط <code>Sitemap:</code> را ننویسید؛ آدرس کامل نقشه سایت همیشه خودکار اضافه می‌شود. <a href="/robots.txt" target="_blank" style="text-decoration:underline">مشاهده robots.txt فعلی</a>')),
            ]);
    }

    private function codeTab(): Tabs\Tab
    {
        return Tabs\Tab::make('کدهای سفارشی (Custom code)')
            ->icon('heroicon-o-code-bracket-square')
            ->schema([
                Placeholder::make('code_warning')->hiddenLabel()->content(new HtmlString(
                    '<span style="color:#dc2626">کد اینجا بدون تغییر در همه‌ی صفحات اجرا می‌شود. فقط کد ابزارهای معتبر (آنالیتیکس، پیکسل، چت) را بچسبانید. گوگل آنالیتیکس/تگ‌منیجر از قبل در فایل .env تنظیم شده؛ دوباره اضافه نکنید.</span>'
                )),
                Textarea::make('seo_head_code')
                    ->label('کد داخل <head> (Header code)')
                    ->rows(6)
                    ->extraInputAttributes(['dir' => 'ltr', 'style' => 'font-family: ui-monospace, monospace']),
                Textarea::make('seo_body_code')
                    ->label('کد انتهای <body> (Footer code)')
                    ->rows(6)
                    ->extraInputAttributes(['dir' => 'ltr', 'style' => 'font-family: ui-monospace, monospace']),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        DB::transaction(function () use ($state): void {
            foreach (self::KEYS as $key => $translatable) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'group' => 'seo',
                        'value' => $translatable ? ($state[$key] ?? []) : ['value' => $state[$key] ?? null],
                        'is_translatable' => $translatable,
                    ],
                );
            }
        });

        SiteSettings::flush();

        Notification::make()->success()->title('تنظیمات سئو ذخیره شد')->send();
    }

    /** @return array<int, Action> */
    protected function getFormActions(): array
    {
        return [Action::make('save')->label('ذخیره (Save changes)')->submit('save')];
    }

    /**
     * One field per supported locale (no tab chrome while only one locale is live).
     *
     * @param  Closure(string): Component  $make
     * @return list<Component>
     */
    private function perLocale(string $key, Closure $make): array
    {
        $locales = config('locales.supported');

        return collect($locales)->map(function (array $cfg, string $locale) use ($key, $make, $locales): Component {
            $field = $make("{$key}.{$locale}")->extraAttributes(['dir' => $cfg['direction'], 'lang' => $cfg['html_lang']]);

            return count($locales) > 1 ? $field->label($field->getLabel().' — '.$cfg['native']) : $field;
        })->values()->all();
    }

    /** Accept a full <meta ... content="X"> tag or a bare code. */
    public static function extractContent(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (preg_match('/content\s*=\s*["\']([^"\']+)["\']/i', $value, $m)) {
            return trim($m[1]);
        }

        return preg_replace('/[^A-Za-z0-9_\-=.]/', '', $value) ?: null;
    }
}
