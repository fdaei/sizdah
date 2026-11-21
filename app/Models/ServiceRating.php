<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Performance\PageCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

/**
 * A visitor's 1–5 star rating of a service. Only admin-approved rows count
 * toward the public average and the AggregateRating JSON-LD.
 */
final class ServiceRating extends Model
{
    protected $fillable = ['service_id', 'rating', 'voter_hash', 'approved_at'];

    protected function casts(): array
    {
        return ['rating' => 'integer', 'approved_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        // Must return void: a listener returning false (Cache::forget on a
        // missing key) stops the model's remaining event listeners.
        $flush = function (self $r): void {
            Cache::forget(self::cacheKey((int) $r->service_id));
        };
        self::saved($flush);
        self::deleted($flush);

        // Public output (average + AggregateRating) only changes when an
        // approved rating appears, disappears or changes — not on new pending votes.
        self::saved(function (self $r): void {
            if ($r->wasChanged('approved_at') || ($r->approved_at !== null && $r->wasChanged('rating'))
                || ($r->wasRecentlyCreated && $r->approved_at !== null)) {
                PageCache::flush();
            }
        });
        self::deleted(function (self $r): void {
            if ($r->approved_at !== null) {
                PageCache::flush();
            }
        });
    }

    /** @return BelongsTo<Service, $this> */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /** @param Builder<self> $query */
    public function scopeApproved(Builder $query): void
    {
        $query->whereNotNull('approved_at');
    }

    /** @return array{average: float, count: int} */
    public static function summary(Service $service): array
    {
        return Cache::remember(self::cacheKey((int) $service->getKey()), 86400, function () use ($service): array {
            $row = self::query()->approved()->where('service_id', $service->getKey())
                ->selectRaw('COUNT(*) as c, AVG(rating) as a')->first();

            return ['average' => round((float) ($row->a ?? 0), 1), 'count' => (int) ($row->c ?? 0)];
        });
    }

    /** @return array<string, mixed>|null */
    public static function aggregateNode(Service $service): ?array
    {
        $summary = self::summary($service);

        if ($summary['count'] === 0) {
            return null;
        }

        return [
            '@type' => 'AggregateRating',
            'ratingValue' => $summary['average'],
            'ratingCount' => $summary['count'],
            'bestRating' => 5,
            'worstRating' => 1,
        ];
    }

    public static function voterHash(?string $ip, ?string $userAgent): string
    {
        return hash_hmac('sha256', ($ip ?? '').'|'.($userAgent ?? ''), (string) config('app.key'));
    }

    private static function cacheKey(int $serviceId): string
    {
        return "service_rating.{$serviceId}";
    }
}
