<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\SiteSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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

    protected static ?string $navigationGroup = 'Website setup';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Site settings';

    protected static string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

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
        'seo_default_title' => true,
        'seo_default_description' => true,
        'seo_default_image' => false,
        'seo_organization_name' => true,
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
                Section::make('Brand')
                    ->schema([
                        $this->translatableInput('site_name', 'Site name'),
                        $this->translatableInput('tagline', 'Tagline'),
                        $this->translatableTextarea('footer_description', 'Footer description'),
                    ]),

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

                Section::make('SEO defaults')
                    ->description('Used when a page has no specific SEO values.')
                    ->schema([
                        $this->translatableInput('seo_default_title', 'Default title'),
                        $this->translatableTextarea('seo_default_description', 'Default description'),
                        $this->translatableInput('seo_organization_name', 'Organisation name'),

                        FileUpload::make('seo_default_image')
                            ->label('Default share image')
                            ->image()
                            ->directory('seo')
                            ->disk('public')
                            ->helperText('Shown when a page has no image. 1200×630 recommended.'),
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
            ->title('Settings saved')
            ->send();
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
