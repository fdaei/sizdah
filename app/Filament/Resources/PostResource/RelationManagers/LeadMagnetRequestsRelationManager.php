<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Filament\Resources\LeadMagnetRequestResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * "Who asked for this article's file" — the LeadMagnetRequestResource table,
 * scoped to one post, under the article's edit form.
 */
final class LeadMagnetRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'leadMagnetRequests';

    protected static ?string $icon = 'heroicon-o-document-arrow-down';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin.lead_magnet.requests_title');
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->leadMagnetRequests()->count();

        return $count > 0 ? (string) $count : null;
    }

    public function table(Table $table): Table
    {
        return LeadMagnetRequestResource::table($table);
    }
}
