<?php

declare(strict_types=1);

namespace App\Services\Redirects;

use App\Models\Redirect;
use App\Support\TransactionalCache;
use Illuminate\Support\Facades\Cache;

/**
 * Looks a request path up in the cached redirect map.
 *
 * Exact rules are a hash lookup; regex rules are tried in id order after
 * that. Regex sources are matched against the decoded path (no leading
 * slash) and may use $1…$9 in the destination.
 */
final class RedirectResolver
{
    /**
     * @return array{0: Redirect, 1: array<int, string>}|null
     */
    public function match(string $path): ?array
    {
        $path = Redirect::normalize($path);
        $map = $this->map();

        if (isset($map['exact'][$path])) {
            return [$map['exact'][$path], []];
        }

        foreach ($map['regex'] as $redirect) {
            $pattern = self::delimit($redirect->source_path);
            if ($pattern !== null && @preg_match($pattern, $path, $m) === 1) {
                return [$redirect, $m];
            }
        }

        return null;
    }

    /**
     * Follows a destination through other exact rules. Returns the final
     * destination path, or null when the chain loops back on itself.
     */
    public function finalDestination(string $source, string $destination, int $max = 10): ?string
    {
        $seen = [Redirect::normalize($source) => true];
        $current = $destination;
        $query = null;

        for ($i = 0; $i < $max; $i++) {
            if (preg_match('#^https?://#i', $current)) {
                $host = parse_url($current, PHP_URL_HOST);
                $siteHost = parse_url(url('/'), PHP_URL_HOST);
                if (! is_string($host) || ! is_string($siteHost) || strcasecmp($host, $siteHost) !== 0) {
                    // Preserve the incoming query if this redirect has no query of its own.
                    if ($query !== null && parse_url($current, PHP_URL_QUERY) === null) {
                        [$base, $fragment] = array_pad(explode('#', $current, 2), 2, null);

                        return $base.'?'.$query.($fragment !== null ? '#'.$fragment : '');
                    }

                    return $current;
                }
            }
            $currentQuery = parse_url($current, PHP_URL_QUERY);
            $query = is_string($currentQuery) ? $currentQuery : $query;
            $key = Redirect::normalize($current);
            if (isset($seen[$key])) {
                return null; // loop
            }
            $seen[$key] = true;

            $next = Redirect::query()->active()->where('match_type', 'exact')->where('source_path', $key)->first();
            if ($next === null || in_array($next->status_code, Redirect::GONE, true)) {
                return $key.($query !== null ? '?'.$query : '');
            }
            $current = (string) $next->destination_path;
        }

        return null;
    }

    /** "#^old/(\d+)$#" or a bare "old/(\d+)" -> a delimited, anchored, UTF-8 pattern. */
    public static function delimit(string $source): ?string
    {
        $source = trim($source);
        if ($source === '') {
            return null;
        }
        $pattern = preg_match('/^([#~\/]).+\1[imsxuU]*$/s', $source)
            ? $source
            : '#^'.str_replace('#', '\#', ltrim($source, '^/')).'$#u';

        return @preg_match($pattern, '') === false ? null : $pattern;
    }

    /**
     * @return array{exact: array<string, Redirect>, regex: list<Redirect>}
     */
    private function map(): array
    {
        $load = function (): array {
            $rows = Redirect::query()->active()->orderBy('id')->get();

            return [
                'exact' => $rows->where('match_type', '!=', 'regex')->keyBy('source_path')->all(),
                'regex' => $rows->where('match_type', 'regex')->values()->all(),
            ];
        };

        return TransactionalCache::active() ? $load() : Cache::rememberForever(Redirect::CACHE_KEY, $load);
    }
}
