<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\Media;
use App\Services\Media\MediaUsage;
use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use App\Support\SiteSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

/**
 * Site-wide settings: brand details, contact info and SEO defaults.
 *
 * Backed by the key/value `settings` table. Translatable values are stored as
 * {"en":…,"fa":…,"ar":…} — the one documented JSON exception (see the
 * settings migration header).
 *
 * Figma sources: footer 1419:9317, contact details card 1363:8934.
 */
final class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Site settings';

    protected static string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    /** @var list<int> Image IDs captured when the confirmation opens. */
    #[Locked]
    public array $unusedImageIds = [];

    /**
     * key => translatable?
     *
     * @var array<string, bool>
     */
    private const KEYS = [
        'site_name' => true,
        'tagline' => true,
        'footer_description' => true,
        'contact_whatsapp' => false,
        'contact_phone' => false,
        'contact_email' => false,
        'contact_location' => true,
        'contact_working_with' => true,
        'contact_details' => false,
        'image_top_home_desktop' => false,
        'image_top_home_tablet' => false,
        'image_top_home_mobile' => false,
        'image_top_work_desktop' => false,
        'image_top_work_tablet' => false,
        'image_top_work_mobile' => false,
        'image_top_services_desktop' => false,
        'image_top_services_tablet' => false,
        'image_top_services_mobile' => false,
        'image_top_insights_desktop' => false,
        'image_top_insights_tablet' => false,
        'image_top_insights_mobile' => false,
        'image_top_about_desktop' => false,
        'image_top_about_tablet' => false,
        'image_top_about_mobile' => false,
        'image_home_desktop' => false,
        'image_home_mobile' => false,
        'image_about_hero' => false,
        'image_site_background' => false,
        'image_project_fallback' => false,
        'image_error_404' => false,
    ];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation.groups.Settings');
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fa' ? 'تنظیمات سایت' : 'Site settings';
    }

    public function getTitle(): string
    {
        return app()->getLocale() === 'fa' ? 'تنظیمات سایت' : 'Site settings';
    }

    public function mount(): void
    {
        $stored = Setting::query()->get()->keyBy('key');

        $data = [];

        foreach (self::KEYS as $key => $translatable) {
            $value = $stored->get($key)?->value;

            if ($translatable) {
                foreach (array_keys(config('locales.supported')) as $locale) {
                    $data[$key][$locale] = is_array($value) ? ($value[$locale] ?? '') : '';
                }
            } else {
                $data[$key] = is_array($value) ? ($value['value'] ?? null) : $value;
            }
        }

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('settings')->columnSpanFull()->persistTabInQueryString('settings-tab')->tabs([
                Tabs\Tab::make($this->label('اطلاعات سایت', 'Site information'))->icon('heroicon-o-building-storefront')->schema([
                Section::make($this->label('معرفی سایت', 'Site identity'))
                    ->schema([
                        $this->translatableInput('site_name', 'Site name'),
                        $this->translatableInput('tagline', 'Tagline'),
                        $this->translatableTextarea('footer_description', 'Footer description'),
                    ]),

                ]),
                Tabs\Tab::make($this->label('اطلاعات تماس', 'Contact details'))->icon('heroicon-o-phone')->schema([
                Section::make($this->label('اطلاعات تماس', 'Contact details'))
                    ->description($this->label('موارد کارت تماس را اضافه، حذف و با کشیدن جابه‌جا کنید. برای اینستاگرام یا شبکه‌های دیگر، نوع و آدرس لینک را انتخاب کنید.', 'Add, remove, and drag to reorder the contact-card items. Choose a type and link for Instagram or other networks.'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_whatsapp')
                            ->label($this->label('شماره واتس‌اپ', 'WhatsApp number'))
                            ->maxLength(50),

                        TextInput::make('contact_phone')
                            ->label($this->label('شماره تماس', 'Phone number'))
                            ->helperText($this->label('مثلاً +98 913 616 2313', 'e.g. +98 913 616 2313'))
                            ->maxLength(50),

                        TextInput::make('contact_email')
                            ->label($this->label('ایمیل', 'Email'))
                            ->email()
                            ->maxLength(200),

                        $this->translatableInput('contact_location', $this->label('آدرس', 'Address'))
                            ->columnSpanFull(),

                        $this->translatableInput('contact_working_with', $this->label('همکاری با', 'Working with'))
                            ->columnSpanFull(),

                        Repeater::make('contact_details')
                            ->label($this->label('موارد کارت اطلاعات تماس', 'Contact card items'))
                            ->helperText($this->label('ترتیب نمایش دقیقاً مطابق ترتیب این فهرست است.', 'The display order follows this list exactly.'))
                            ->addActionLabel($this->label('افزودن مورد', 'Add item'))
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'][app()->getLocale()]
                                ?? $state['label'][config('locales.fallback')]
                                ?? null)
                            ->schema([
                                Select::make('type')
                                    ->label($this->label('نوع', 'Type'))
                                    ->options([
                                        'phone' => $this->label('تلفن', 'Phone'),
                                        'whatsapp' => 'WhatsApp',
                                        'email' => $this->label('ایمیل', 'Email'),
                                        'location' => $this->label('موقعیت', 'Location'),
                                        'collaboration' => $this->label('همکاری', 'Collaboration'),
                                        'instagram' => 'Instagram',
                                        'telegram' => 'Telegram',
                                        'linkedin' => 'LinkedIn',
                                        'custom' => $this->label('سفارشی', 'Custom'),
                                    ])
                                    ->required()
                                    ->default('custom')
                                    ->live(),

                                Select::make('icon')
                                    ->label($this->label('آیکن', 'Icon'))
                                    ->options([
                                        'phone' => $this->label('تلفن', 'Phone'),
                                        'whatsapp' => 'WhatsApp',
                                        'email' => $this->label('ایمیل', 'Email'),
                                        'location' => $this->label('موقعیت', 'Location'),
                                        'collaboration' => $this->label('همکاری', 'Collaboration'),
                                        'instagram' => 'Instagram',
                                        'telegram' => 'Telegram',
                                        'linkedin' => 'LinkedIn',
                                        'link' => $this->label('لینک', 'Link'),
                                    ])
                                    ->required()
                                    ->default('link'),

                                TextInput::make('url')
                                    ->label($this->label('لینک (اختیاری)', 'Link (optional)'))
                                    ->helperText($this->label('برای تلفن، ایمیل و واتس‌اپ خودکار ساخته می‌شود؛ برای شبکه‌های اجتماعی آدرس کامل را وارد کنید.', 'Generated automatically for phone, email, and WhatsApp. Enter the full URL for social profiles.'))
                                    ->url()
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                Tabs::make('translations')
                                    ->columnSpanFull()
                                    ->tabs(
                                        collect(config('locales.supported'))
                                            ->map(fn (array $config, string $locale): Tabs\Tab => Tabs\Tab::make($config['native'])
                                                ->schema([
                                                    TextInput::make("label.{$locale}")
                                                        ->label($this->label('عنوان', 'Label'))
                                                        ->required($locale === config('locales.fallback'))
                                                        ->maxLength(100)
                                                        ->extraAttributes(['dir' => $config['direction'], 'lang' => $config['html_lang']]),
                                                    TextInput::make("value.{$locale}")
                                                        ->label($this->label('مقدار', 'Value'))
                                                        ->required($locale === config('locales.fallback'))
                                                        ->maxLength(300)
                                                        ->extraAttributes(['dir' => $config['direction'], 'lang' => $config['html_lang']]),
                                                ]))
                                            ->values()
                                            ->all(),
                                    ),
                            ])
                            ->columnSpanFull(),
                    ]),

                ]),
                Tabs\Tab::make($this->label('تصاویر عمومی', 'General images'))->icon('heroicon-o-photo')->schema([
                Section::make($this->label('تصاویر عمومی سایت', 'General site images'))
                    ->description('تصاویر پیش‌فرض را اینجا جایگزین کنید. تصاویر محتوایی در بخش مربوط به هر صفحه یا پروژه قابل ویرایش‌اند.')
                    ->columns(2)
                    ->schema(array_map(
                        fn (string $key, string $label): FileUpload => FileUpload::make($key)->label($label)->image()->disk('public')->directory('site-images'),
                        ['image_home_desktop', 'image_home_mobile', 'image_about_hero', 'image_site_background', 'image_project_fallback', 'image_error_404'],
                        ['پس‌زمینه صفحه اصلی دسکتاپ', 'پس‌زمینه صفحه اصلی موبایل', 'تصویر بالای درباره ما', 'پس‌زمینه صفحات', 'تصویر پیش‌فرض پروژه', 'تصویر صفحه ۴۰۴'],
                    )),

                ]),
                Tabs\Tab::make($this->label('پس‌زمینه صفحات', 'Page backgrounds'))->id('page-backgrounds')->icon('heroicon-o-device-phone-mobile')->schema([
                Section::make($this->label('پس‌زمینه بالای صفحات', 'Page top backgrounds'))
                    ->description($this->label('برای هر صفحه تصویر جداگانه در اندازه‌های دسکتاپ، تبلت و موبایل انتخاب کنید. در صورت خالی بودن، تصویر موجود استفاده می‌شود.', 'Choose a separate image for each page and screen size. Existing backgrounds are used when no image is selected.'))
                    ->schema([Tabs::make('background_pages')->columnSpanFull()->tabs(array_map(
                        fn (string $page, string $label): Tabs\Tab => Tabs\Tab::make($label)->columns(3)->schema(array_map(
                            fn (string $size, string $sizeLabel): FileUpload => FileUpload::make("image_top_{$page}_{$size}")->label($sizeLabel)->image()->disk('public')->directory('site-images'),
                            ['desktop', 'tablet', 'mobile'],
                            [$this->label('دسکتاپ', 'Desktop'), $this->label('تبلت', 'Tablet'), $this->label('موبایل', 'Mobile')],
                        )),
                        ['home', 'work', 'services', 'insights', 'about'],
                        [$this->label('صفحه اصلی', 'Home'), $this->label('پروژه‌ها', 'Projects'), $this->label('خدمات', 'Services'), $this->label('مقالات', 'Articles'), $this->label('درباره ما', 'About')],
                    ))]),
                ]),

                Tabs\Tab::make($this->label('سئو', 'SEO'))->icon('heroicon-o-magnifying-glass')->schema([
                Section::make('SEO')
                    ->description(new HtmlString('عنوان، توضیحات و تصویر پیش‌فرض، نمودار دانش و سایر تنظیمات سئو به «<a href="'.e(ManageSeoSettings::getUrl()).'" style="text-decoration:underline">تنظیمات سئو (SEO settings)</a>» منتقل شده‌اند.')),
                ]),
                ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        DB::transaction(function () use ($state): void {
            foreach (self::KEYS as $key => $translatable) {
                $value = $translatable
                    ? ($state[$key] ?? [])
                    : ['value' => $state[$key] ?? null];

                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'group' => str_contains($key, 'seo_') ? 'seo' : 'general',
                        'value' => $value,
                        'is_translatable' => $translatable,
                    ],
                );
            }
        });

        SiteSettings::flush();

        Notification::make()
            ->success()
            ->title($this->label('تنظیمات ذخیره شد', 'Settings saved'))
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cleanUnusedImages')
                ->label($this->label('پاک‌سازی تصاویر بدون استفاده', 'Clean up unused images'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading($this->label('حذف تصاویر بدون استفاده', 'Delete unused images'))
                ->modalSubmitActionLabel($this->label('حذف تصاویر فهرست‌شده', 'Delete listed images'))
                ->mountUsing(function (): void {
                    $this->unusedImageIds = Media::query()->where('type', 'image')->get()
                        ->filter(fn (Media $media): bool => (auth()->user()?->can('delete', $media) ?? false)
                            && MediaUsage::find($media) === [])
                        ->modelKeys();
                })
                ->modalDescription(function (): HtmlString {
                    $files = Media::query()->whereIn('id', $this->unusedImageIds)->orderBy('id')->get(['name', 'path']);
                    $list = $files->map(fn (Media $media): string => '<li dir="ltr">'.e($media->path).'</li>')->implode('');
                    $description = 'تصاویر ثبت‌شده در کتابخانه که در محتوای سایت، تنظیمات یا سئو استفاده نشده‌اند: '.$files->count().' فایل. این فایل‌ها و نسخه‌های بهینه‌شده‌شان از فضای هاست حذف می‌شوند. پیش از تأیید فهرست را بررسی کنید.';

                    return new HtmlString('<p>'.e($description).'</p><ul style="max-height:16rem;overflow:auto;list-style:disc;padding-inline-start:1.5rem">'.$list.'</ul>');
                })
                ->action(function (): void {
                    $deleted = 0;
                    $skipped = 0;
                    $disk = Storage::disk(Media::DISK);
                    foreach (Media::query()->whereIn('id', $this->unusedImageIds)->where('type', 'image')->cursor() as $media) {
                        if (! (auth()->user()?->can('delete', $media) ?? false) || MediaUsage::find($media) !== []) {
                            $skipped++;
                            continue;
                        }
                        if ($disk->exists($media->path) && ! $disk->delete($media->path)) {
                            $skipped++;
                            continue;
                        }
                        ImageOptimizer::purge($media->path);
                        $media->delete();
                        $deleted++;
                    }
                    $this->unusedImageIds = [];
                    Notification::make()->success()->title("{$deleted} تصویر حذف شد")
                        ->body($skipped ? "{$skipped} تصویر به دلیل استفاده یا خطای حذف حفظ شد." : null)->send();
                }),
        ];
    }

    /**
     * @return array<int, Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save changes')
                ->submit('save'),
        ];
    }

    private function label(string $fa, string $en): string
    {
        return app()->getLocale() === 'fa' ? $fa : $en;
    }

    private function translatableInput(string $key, string $label): Tabs
    {
        return Tabs::make($label)
            ->columnSpanFull()
            ->tabs(
                collect(config('locales.supported'))
                    ->map(fn (array $config, string $locale): Tabs\Tab => Tabs\Tab::make($config['native'])
                        ->schema([
                            TextInput::make("{$key}.{$locale}")
                                ->label($label)
                                ->maxLength(300)
                                ->extraAttributes([
                                    'dir' => $config['direction'],
                                    'lang' => $config['html_lang'],
                                ]),
                        ]))
                    ->values()
                    ->all(),
            );
    }

    private function translatableTextarea(string $key, string $label): Tabs
    {
        return Tabs::make($label)
            ->columnSpanFull()
            ->tabs(
                collect(config('locales.supported'))
                    ->map(fn (array $config, string $locale): Tabs\Tab => Tabs\Tab::make($config['native'])
                        ->schema([
                            Textarea::make("{$key}.{$locale}")
                                ->label($label)
                                ->rows(3)
                                ->maxLength(500)
                                ->extraAttributes([
                                    'dir' => $config['direction'],
                                    'lang' => $config['html_lang'],
                                ]),
                        ]))
                    ->values()
                    ->all(),
            );
    }
}
