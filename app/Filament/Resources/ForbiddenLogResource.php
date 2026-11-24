<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ForbiddenLogResource\Pages\ListForbiddenLogs;
use App\Models\ForbiddenLog;
use Illuminate\Support\Facades\Schema;

final class ForbiddenLogResource extends NotFoundLogResource
{
    protected static ?string $model = ForbiddenLog::class;

    protected static ?string $navigationLabel = 'گزارش ۴۰۳ (403 monitor)';

    protected static ?string $modelLabel = 'خطای ۴۰۳';

    protected static ?string $pluralModelLabel = 'گزارش ۴۰۳ (403 monitor)';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return Schema::hasTable('forbidden_logs') && parent::shouldRegisterNavigation();
    }

    public static function getNavigationBadge(): ?string
    {
        if (! Schema::hasTable('forbidden_logs')) {
            return null;
        }

        $count = ForbiddenLog::query()->where('last_seen_at', '>=', now()->subDays(7))->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getPages(): array
    {
        return ['index' => ListForbiddenLogs::route('/')];
    }
}
