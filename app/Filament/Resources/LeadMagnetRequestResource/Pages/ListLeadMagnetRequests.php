<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadMagnetRequestResource\Pages;

use App\Filament\Resources\LeadMagnetRequestResource;
use Filament\Resources\Pages\ListRecords;

final class ListLeadMagnetRequests extends ListRecords
{
    protected static string $resource = LeadMagnetRequestResource::class;
}
