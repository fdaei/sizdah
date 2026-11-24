<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Performance\PageCache;
use App\Support\Performance;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Serves/stores anonymous public pages from {@see PageCache} when enabled in the panel. */
final class CachePublicPages
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Performance::pageCache() || ! PageCache::isCacheable($request)) {
            return $next($request);
        }

        // Keep the generation captured before rendering: a concurrent edit can
        // invalidate it while the response is being built.
        $key = PageCache::key($request);

        if ($hit = PageCache::get($request, $key)) {
            return $hit;
        }

        $response = $next($request);
        PageCache::put($request, $response, Performance::pageCacheTtl(), $key);

        return $response;
    }
}
