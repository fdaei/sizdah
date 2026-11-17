<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\SetAdminLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Admin panel at /admin.
 *
 * Deliberately NOT locale-prefixed. The panel language is stored separately
 * in the session, while the content it edits remains trilingual.
 *
 * Access is gated by User::canAccessPanel() (admin | editor) and then
 * per-resource by the policies in app/Policies.
 */
final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->passwordReset()
            ->profile(isSimple: false)
            ->brandName('Sizdah')
            ->favicon(asset('favicon.svg').'?v=3')
            ->colors([
                // Figma "primary gold" #BD933B
                'primary' => Color::hex('#BD933B'),
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            // Filament's image editor saves crops via canvas.toBlob() with no
            // quality argument (browser default ≈0.92 → visible JPEG/WebP loss).
            // Default it to maximum quality inside the admin panel only.
            ->renderHook(
                'panels::head.end',
                fn (): string => '<script>(function(){var t=HTMLCanvasElement.prototype.toBlob;'
                    .'HTMLCanvasElement.prototype.toBlob=function(cb,type,q){'
                    .'return t.call(this,cb,type,q===undefined?1:q);};})();</script>',
            )
            ->maxContentWidth('full')
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages',
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets',
            )
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label(__('admin.language.english'))
                    ->icon('heroicon-o-language')
                    ->url(fn (): string => route('admin.locale', ['locale' => 'en']))
                    ->visible(fn (): bool => app()->getLocale() !== 'en')
                    ->sort(-2),
                MenuItem::make()
                    ->label(__('admin.language.persian'))
                    ->icon('heroicon-o-language')
                    ->url(fn (): string => route('admin.locale', ['locale' => 'fa']))
                    ->visible(fn (): bool => app()->getLocale() !== 'fa')
                    ->sort(-2),
            ])
            ->navigationGroups([
                NavigationGroup::make(fn (): string => __('admin.navigation.groups.Content'))
                    ->icon('heroicon-o-document-text'),
                NavigationGroup::make(fn (): string => __('admin.navigation.groups.SEO'))
                    ->icon('heroicon-o-magnifying-glass'),
                NavigationGroup::make(fn (): string => __('admin.navigation.groups.Appearance'))
                    ->icon('heroicon-o-paint-brush'),
                NavigationGroup::make(fn (): string => __('admin.navigation.groups.Performance'))
                    ->icon('heroicon-o-bolt'),
                NavigationGroup::make(fn (): string => __('admin.navigation.groups.Settings'))
                    ->icon('heroicon-o-cog-6-tooth'),
                NavigationGroup::make(fn (): string => __('admin.navigation.groups.Messages'))
                    ->icon('heroicon-o-inbox'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetAdminLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop();
    }
}
