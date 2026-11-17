<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\ContentTools;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

/** Global switches for the article pipeline: table of contents + auto internal links. */
final class ManageContentTools extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?int $navigationSort = 90;

    protected static string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation.groups.Content');
    }

    public static function getNavigationLabel(): string
    {
        return 'فهرست مطالب و لینک‌سازی';
    }

    public function getTitle(): string
    {
        return 'فهرست مطالب خودکار و لینک‌سازی داخلی';
    }

    public function mount(): void
    {
        $this->form->fill(collect(ContentTools::DEFAULTS)->map(fn (mixed $d, string $k): mixed => ContentTools::get($k))->all());
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')->schema([
            Section::make('فهرست مطالب (Table of Contents)')->columns(2)->schema([
                Toggle::make('toc_enabled')->label('نمایش خودکار فهرست مطالب در مقالات')
                    ->helperText('در فرم هر مقاله می‌توانید این تنظیم را برای همان مقاله روشن/خاموش کنید.'),
                Toggle::make('toc_collapsed')->label('به‌صورت پیش‌فرض بسته باشد'),
                TextInput::make('toc_min_headings')->label('حداقل تعداد تیتر برای نمایش')->numeric()->minValue(1)->maxValue(20)->required(),
                CheckboxList::make('toc_levels')->label('سطوح تیتر')->options(['h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4'])->columns(3)->required(),
            ]),
            Section::make('لینک‌سازی داخلی خودکار')->columns(2)->schema([
                Toggle::make('autolink_enabled')->label('فعال')
                    ->helperText('کلمات کلیدی از بخش «سئو ← لینک‌سازی داخلی خودکار» مدیریت می‌شوند.'),
                TextInput::make('autolink_max_total')->label('حداکثر کل لینک‌های خودکار در هر مقاله')->numeric()->minValue(0)->maxValue(50)->required(),
            ]),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        DB::transaction(function () use ($state): void {
            foreach (ContentTools::DEFAULTS as $key => $default) {
                $value = $state[$key] ?? $default;
                if (in_array($key, ['toc_min_headings', 'autolink_max_total'], true)) {
                    $value = (int) $value;
                }
                Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['group' => 'content_tools', 'is_translatable' => false, 'value' => ['value' => $value]],
                );
            }
        });

        Notification::make()->title('ذخیره شد')->success()->send();
    }
}
