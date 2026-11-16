<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Page;

/**
 * In-panel SEO handbook for editors coming from WordPress + Yoast / Rank Math.
 * Read-only; linked from the header of every SEO box (SeoSection).
 */
final class SeoGuide extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Website setup';

    protected static ?int $navigationSort = 90;

    protected static ?string $slug = 'seo-guide';

    protected static string $view = 'filament.pages.seo-guide';

    public static function getNavigationGroup(): ?string
    {
        return __('admin.navigation.groups.Website setup');
    }

    public static function getNavigationLabel(): string
    {
        return 'راهنمای سئو (SEO guide)';
    }

    public function getTitle(): string
    {
        return 'راهنمای سئو (SEO guide)';
    }
}
