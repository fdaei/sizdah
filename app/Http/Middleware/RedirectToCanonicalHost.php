<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * One canonical origin (technical SEO audit 2026-10-04, SEO-DEV-03 / CHK-07).
 *
 * Any GET/HEAD whose scheme or host differs from APP_URL — www., plain
 * http — or whose path ends in a slash is sent to the canonical URL with a
 * single 301, so http://www.example.com/fa/about/ lands on
 * https://example.com/fa/about without a redirect chain. Canonical tags,
 * hreflang, sitemap and Open Graph URLs are all generated from that same
 * origin, so crawl signals are never split across hosts.
 *
 * Should ideally also exist at the web-server/CDN layer; this is the
 * application-level guarantee. Toggled by config('app.canonical_redirect').
 */
final class RedirectToCanonicalHost
{
    /**
     * Paths probed by load balancers on internal hosts/IPs.
     */
    private const EXCLUDED = ['up'];

    public function handle(Request $request, Closure $next): Response
    {
        if (
            ! config('app.canonical_redirect')
            || ! $request->isMethodSafe()
            || in_array($request->path(), self::EXCLUDED, true)
        ) {
            return $next($request);
        }

        $canonical = parse_url((string) config('app.url'));
        $scheme = $canonical['scheme'] ?? 'https';
        $host = $canonical['host'] ?? $request->getHost();
        $port = isset($canonical['port']) ? ':'.$canonical['port'] : '';

        $path = $request->getPathInfo();
        $trimmed = $path === '/' ? '/' : rtrim($path, '/');

        if (
            $this->requestScheme($request) === $scheme
            && strtolower($request->getHost()) === strtolower($host)
            && $trimmed === $path
        ) {
            return $next($request);
        }

        $query = $request->getQueryString();

        return redirect()->away(
            "{$scheme}://{$host}{$port}{$trimmed}".($query ? "?{$query}" : ''),
            301,
        );
    }

    /**
     * Behind a TLS-terminating proxy/CDN the app sees plain http; trust the
     * forwarded protocol for this decision so https requests don't loop. (A
     * spoofed header can only make *this* request skip the redirect.)
     */
    private function requestScheme(Request $request): string
    {
        $forwarded = $request->headers->get('X-Forwarded-Proto');

        if (is_string($forwarded) && $forwarded !== '') {
            return strtolower(trim(explode(',', $forwarded)[0]));
        }

        return $request->getScheme();
    }
}
