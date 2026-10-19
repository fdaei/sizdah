<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\HomeLeadMagnet;
use App\Support\SiteSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

/**
 * The home page lead magnet file and whether it is emailed — the home
 * banner's counterpart of the per-article fields on PostResource.
 *
 * Stored as three plain `settings` rows (see Support\HomeLeadMagnet); the
 * banner copy itself is still edited on the home Page's `lead_magnet` section.
 *
 * Figma source: home lead magnet strip 391:4795.
 */
final class ManageHomeLeadMagnet extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationGroup = 'Website setup';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'home-lead-magnet';

    protected static string $view = 'filament.pages.manage-settings';

    /** @var array<string, mixed> */
    public array $data = [];

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
        return __('admin.lead_magnet.home.title');
    }

    public function getTitle(): string
    {
        return __('admin.lead_magnet.home.title');
    }

    public function mount(): void
    {
        $this->form->fill([
            'path' => HomeLeadMagnet::path(),
            'name' => HomeLeadMagnet::name(),
            'send_email' => (bool) SiteSettings::get(HomeLeadMagnet::SEND_EMAIL_KEY, null, false),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make(fn (): string => __('admin.lead_magnet.home.title'))
                    ->description(fn (): string => __('admin.lead_magnet.home.section_description'))
                    ->schema([
                        FileUpload::make('path')
                            ->label(fn (): string => __('admin.lead_magnet.home.file'))
                            // Private disk: never publicly linkable, only
                            // ever leaves the server as a mail attachment.
                            ->disk('local')
                            ->directory('lead-magnets')
                            ->visibility('private')
                            ->storeFileNamesIn('name')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/zip',
                                'application/x-zip-compressed',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'image/png',
                                'image/jpeg',
                            ])
                            // Mail servers commonly reject attachments past ~10–25 MB.
                            ->maxSize(10 * 1024)
                            ->downloadable()
                            ->required(fn (Get $get): bool => (bool) $get('send_email'))
                            ->helperText(fn (): string => __('admin.lead_magnet.file_help')),

                        Toggle::make('send_email')
                            ->label(fn (): string => __('admin.lead_magnet.home.send_email'))
                            ->live()
                            ->helperText(fn (): string => __('admin.lead_magnet.send_email_help')),
                    ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $values = [
            HomeLeadMagnet::PATH_KEY => $state['path'] ?? null,
            HomeLeadMagnet::NAME_KEY => filled($state['path'] ?? null) ? ($state['name'] ?? null) : null,
            HomeLeadMagnet::SEND_EMAIL_KEY => (bool) ($state['send_email'] ?? false),
        ];

        DB::transaction(function () use ($values): void {
            foreach ($values as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'group' => 'lead_magnet',
                        'value' => ['value' => $value],
                        'is_translatable' => false,
                    ],
                );
            }
        });

        SiteSettings::flush();

        Notification::make()
            ->success()
            ->title(__('admin.lead_magnet.home.saved'))
            ->send();
    }
}
