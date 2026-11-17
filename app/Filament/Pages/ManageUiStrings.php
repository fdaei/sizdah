<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Support\UiStrings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * Every UI string on the public site (buttons, labels, footer legal line,
 * error-page copy, empty states…) editable without touching code.
 *
 * Fields are generated from the `lang/{locale}/*.php` files, so a string a
 * developer adds to a lang file shows up here automatically. Leaving a field
 * empty keeps the file default (shown as the placeholder).
 */
final class ManageUiStrings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    /** Readable Persian names for the lang groups. */
    private const GROUP_LABELS = [
        'blog' => 'وبلاگ و مقالات',
        'common' => 'عمومی، دکمه‌ها و CTAها',
        'errors' => 'صفحات خطا',
        'footer' => 'فوتر',
        'forms' => 'فرم‌ها',
        'home' => 'صفحه اصلی',
        'seo' => 'سئو',
        'work' => 'نمونه‌کارها',
    ];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation.groups.Appearance');
    }

    public static function getNavigationLabel(): string
    {
        return 'متن‌های سایت';
    }

    public function getTitle(): string
    {
        return 'متن‌ها، دکمه‌ها و برچسب‌های سایت';
    }

    public function mount(): void
    {
        $data = [];

        foreach (array_keys(config('locales.supported')) as $locale) {
            $overrides = UiStrings::overrides($locale);

            foreach (UiStrings::editable($locale) as $lines) {
                foreach (array_keys($lines) as $key) {
                    $data[$locale][self::encode($key)] = $overrides[$key] ?? null;
                }
            }
        }

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        $locales = config('locales.supported');

        $perLocale = fn (string $locale): array => collect(UiStrings::editable($locale))
            ->map(fn (array $lines, string $group): Section => Section::make(self::GROUP_LABELS[$group] ?? $group)
                ->collapsible()
                ->collapsed()
                ->columns(2)
                ->schema(collect($lines)->map(function (string $default, string $key) use ($locale) {
                    $field = mb_strlen($default) > 80
                        ? Textarea::make("{$locale}.".self::encode($key))->rows(2)
                        : TextInput::make("{$locale}.".self::encode($key));

                    return $field
                        ->label($key)
                        ->placeholder($default)
                        ->helperText(str_contains($default, ':') ? 'متغیرهایی مثل :name یا :year را نگه دارید.' : null);
                })->values()->all()))
            ->values()
            ->all();

        $schema = count($locales) === 1
            ? $perLocale((string) array_key_first($locales))
            : [Tabs::make('locales')->tabs(collect($locales)
                ->map(fn (array $c, string $locale): Tabs\Tab => Tabs\Tab::make($c['native'])->schema($perLocale($locale)))
                ->values()->all())];

        return $form->schema([
            Section::make()
                ->description('خالی گذاشتن هر فیلد یعنی استفاده از متن پیش‌فرض (نمایش داده‌شده به رنگ کم‌رنگ).')
                ->schema($schema),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (array_keys(config('locales.supported')) as $locale) {
            $values = collect($state[$locale] ?? [])
                ->mapWithKeys(fn (mixed $v, string $k): array => [self::decode($k) => $v])
                ->all();

            UiStrings::save($locale, $values);
        }

        Notification::make()->title('متن‌ها ذخیره شد')->success()->send();
    }

    /** Livewire state paths treat dots as nesting, so dot-keys are encoded. */
    private static function encode(string $key): string
    {
        return str_replace('.', '__', $key);
    }

    private static function decode(string $key): string
    {
        return str_replace('__', '.', $key);
    }
}
