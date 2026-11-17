<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Service;
use App\Models\ServiceRating;
use Illuminate\Database\UniqueConstraintViolationException;

/**
 * Stores one pending rating per visitor per service. A repeat vote from the
 * same visitor is ignored (returns false) rather than overwriting, so a single
 * person cannot drift the average. Rows stay pending until an admin approves.
 */
final class RecordServiceRating
{
    public function __invoke(Service $service, int $rating, ?string $ip, ?string $userAgent): bool
    {
        try {
            ServiceRating::query()->create([
                'service_id' => $service->getKey(),
                'rating' => max(1, min(5, $rating)),
                'voter_hash' => ServiceRating::voterHash($ip, $userAgent),
            ]);

            return true;
        } catch (UniqueConstraintViolationException) {
            return false;
        }
    }
}
