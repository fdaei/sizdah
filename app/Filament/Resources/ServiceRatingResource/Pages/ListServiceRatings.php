<?php

declare(strict_types=1);

namespace App\Filament\Resources\ServiceRatingResource\Pages;

use App\Filament\Resources\ServiceRatingResource;
use Filament\Resources\Pages\ListRecords;

final class ListServiceRatings extends ListRecords
{
    protected static string $resource = ServiceRatingResource::class;
}
