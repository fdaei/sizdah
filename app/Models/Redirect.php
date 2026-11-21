<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Redirects\RedirectResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Editor-managed redirect (Rank Math "Redirections"). Applied before routing
 * by App\Http\Middleware\ApplyRedirects from a cached map that is flushed on
 * every save/delete.
 *
 * Paths are stored without a leading slash and URL-decoded, so a Persian
 * path matches whether it was typed raw or copied percent-encoded.
 */
final class Redirect extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'redirects.map';

    public const STATUSES = [
        301 => '301 — دائمی (Permanent)',
        302 => '302 — موقت (Temporary)',
        303 => '303 — See Other',
        307 => '307 — موقت، حفظ متد (Temporary Redirect)',
        308 => '308 — دائمی، حفظ متد (Permanent Redirect)',
        410 => '410 — حذف‌شده (Content deleted)',
        451 => '451 — حذف به دلیل قانونی (Unavailable for legal reasons)',
    ];

    /** Codes that answer without a destination. */
    public const GONE = [410, 451];

    protected $fillable = [
        'source_path',
        'destination_path',
        'match_type',
        'origin',
        'notes',
        'status_code',
        'is_active',
        'hits',
        'last_hit_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'status_code' => 'integer',
            'hits' => 'integer',
            'last_hit_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        self::saving(function (self $r): void {
            if ($r->match_type !== 'regex') {
                $r->source_path = self::normalize((string) $r->source_path);
            }
            if (filled($r->destination_path) && ! preg_match('#^https?://#i', (string) $r->destination_path)) {
                // Keep a destination query string (e.g. "fa/x?id=$1"); normalize only the path.
                [$destPath, $destQuery] = array_pad(explode('?', (string) $r->destination_path, 2), 2, null);
                $r->destination_path = self::normalize($destPath).($destQuery !== null ? '?'.$destQuery : '');
            }
            if (in_array((int) $r->status_code, self::GONE, true)) {
                $r->destination_path = null;
            } elseif ($r->match_type !== 'regex' && filled($r->destination_path)) {
                // Shorten chains: A -> B where B -> C becomes A -> C.
                $final = app(RedirectResolver::class)->finalDestination((string) $r->source_path, (string) $r->destination_path);
                if ($final !== null) {
                    $r->destination_path = $final;
                }
            }
        });

        self::saved(function (self $r): void {
            // Rules that pointed at this source now point straight at its target.
            if ($r->is_active && $r->match_type !== 'regex' && filled($r->destination_path)) {
                self::query()->whereKeyNot($r->getKey())
                    ->where('destination_path', $r->source_path)
                    ->where('source_path', '!=', $r->destination_path)
                    ->update(['destination_path' => $r->destination_path]);
            }
        });

        // Void on purpose: a listener returning false (Cache::forget on a cold
        // key) stops the remaining listeners, incl. the page-cache bust.
        $flush = function (): void {
            Cache::forget(self::CACHE_KEY);
        };
        self::saved($flush);
        self::deleted($flush);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function recordHit(): void
    {
        self::query()->whereKey($this->getKey())->update([
            'hits' => DB::raw('hits + 1'),
            'last_hit_at' => now(),
        ]);
    }

    /** "/fa/%D8%A2.../" or "https://site/fa/x?y" -> "fa/آ..." (no query, no slashes at ends). */
    public static function normalize(string $path): string
    {
        $path = trim($path);
        if (preg_match('#^https?://#i', $path)) {
            $path = (string) parse_url($path, PHP_URL_PATH);
        }
        $path = strtok($path, '?#') ?: '';

        return trim(rawurldecode($path), '/');
    }

    /** Destination as an absolute URL ("/" + path, or the external URL untouched). */
    public function targetUrl(?array $captures = null): ?string
    {
        $dest = (string) $this->destination_path;
        if ($captures) {
            foreach ($captures as $i => $value) {
                $dest = str_replace('$'.$i, (string) $value, $dest);
            }
        }

        if (preg_match('#^https?://#i', $dest)) {
            return $dest;
        }

        [$path, $query] = array_pad(explode('?', $dest, 2), 2, null);

        return url('/'.implode('/', array_map('rawurlencode', explode('/', $path)))).($query !== null ? '?'.$query : '');
    }
}
