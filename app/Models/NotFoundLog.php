<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 404 monitor row (Rank Math "404 Monitor"): one row per path, hit-counted.
 * Pruned after 90 days without a hit (`model:prune`, scheduled daily).
 */
final class NotFoundLog extends Model
{
    use MassPrunable;

    protected $fillable = ['path_hash', 'path', 'referrer', 'user_agent', 'is_bot', 'hits', 'last_seen_at'];

    protected function casts(): array
    {
        return ['is_bot' => 'boolean', 'hits' => 'integer', 'last_seen_at' => 'datetime'];
    }

    /** Static-asset and scanner noise that would only flood the log. */
    private const IGNORE = '#(\.(js|css|map|png|jpe?g|gif|webp|avif|svg|ico|woff2?|ttf|txt|xml|php|asp|aspx|env|ini|sql|bak)$)|(^|/)(wp-|xmlrpc|\.env|\.git|vendor/|phpmyadmin|cgi-bin)#i';

    /** Crawlers, link unfurlers, monitors and HTTP libraries. */
    private const BOT_UA = '/bot|crawl|spider|slurp|facebookexternalhit|embedly|preview|monitor|curl|wget|python-requests|httpclient|go-http|headless|lighthouse/i';

    public static function isBot(?string $userAgent): bool
    {
        return $userAgent === null || trim($userAgent) === '' || preg_match(self::BOT_UA, $userAgent) === 1;
    }

    public static function record(Request $request): void
    {
        $path = Redirect::normalize($request->getRequestUri());

        if ($path === '' || preg_match(self::IGNORE, $path)) {
            return;
        }

        $hash = sha1($path);
        $now = now();

        $updated = self::query()->where('path_hash', $hash)->update([
            'hits' => DB::raw('hits + 1'),
            'last_seen_at' => $now,
            'referrer' => Str::limit((string) $request->headers->get('referer'), 990, '') ?: DB::raw('referrer'),
            'updated_at' => $now,
        ]);

        if ($updated === 0) {
            self::query()->insertOrIgnore([
                'path_hash' => $hash,
                'path' => Str::limit($path, 990, ''),
                'referrer' => Str::limit((string) $request->headers->get('referer'), 990, '') ?: null,
                'user_agent' => Str::limit((string) $request->userAgent(), 490, '') ?: null,
                'is_bot' => self::isBot($request->userAgent()),
                'hits' => 1,
                'last_seen_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function prunable(): Builder
    {
        return self::query()->where('last_seen_at', '<', now()->subDays(90));
    }
}
