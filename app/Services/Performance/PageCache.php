<?php

declare(strict_types=1);

namespace App\Services\Performance;

use App\Support\TransactionalCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Full-response cache for anonymous GET requests (WP Rocket "page cache").
 *
 * Invalidation is generational: every key embeds a version number, and
 * {@see self::flush()} just bumps it. That works on every cache driver (no
 * tags needed) and old entries simply expire by TTL.
 *
 * The CSRF token is replaced with a placeholder before storing and the
 * visitor's own token is injected on the way out, so a cached page never hands
 * one visitor another visitor's token.
 */
final class PageCache
{
    /** Writes to these never change public output, so they don't bust the cache. */
    public const IGNORED_MODELS = [
        \App\Models\NotFoundLog::class,
        \App\Models\ContactSubmission::class,
        \App\Models\NewsletterSubscription::class,
        \App\Models\LeadMagnetRequest::class,
        \App\Models\ContentRevision::class,
        \App\Models\User::class,
        // Pending ratings are invisible; ServiceRating busts the cache itself on approval changes.
        \App\Models\ServiceRating::class,
        \Illuminate\Notifications\DatabaseNotification::class,
    ];

    private const VERSION_KEY = 'page_cache.version';

    private const CSRF_PLACEHOLDER = '__PAGE_CACHE_CSRF__';

    /** Headers worth replaying; everything else (cookies!) is regenerated per request. */
    private const KEEP_HEADERS = ['content-type', 'x-inertia', 'vary', 'link', 'content-language', 'x-robots-tag'];

    public static function isCacheable(Request $request): bool
    {
        return $request->isMethod('GET')
            && ! TransactionalCache::active()
            && $request->user() === null
            && ! $request->hasHeader('X-Inertia-Partial-Data')
            && ! $request->query->has('preview')
            && ! $request->session()->has('success')
            && ! $request->session()->has('error')
            && ! $request->session()->has('download')
            && ! $request->session()->has('errors');
    }

    public static function key(Request $request): string
    {
        $variant = implode('|', [
            $request->fullUrl(),
            $request->header('X-Inertia') ? 'inertia' : 'html',
            (string) $request->header('X-Inertia-Version'),
        ]);

        return 'page_cache.'.self::version().'.'.sha1($variant);
    }

    public static function get(Request $request): ?Response
    {
        $hit = Cache::get(self::key($request));

        if (! is_array($hit)) {
            return null;
        }

        $response = new \Illuminate\Http\Response(
            str_replace(self::CSRF_PLACEHOLDER, (string) csrf_token(), (string) $hit['content']),
            (int) $hit['status'],
            (array) $hit['headers'],
        );
        $response->headers->set('X-Page-Cache', 'HIT');

        return $response;
    }

    public static function put(Request $request, Response $response, int $ttl): void
    {
        if ($response->getStatusCode() !== 200 || $response->headers->has('Set-Cookie') && self::setsAppCookies($response)) {
            return;
        }

        $content = $response->getContent();
        $token = (string) csrf_token();

        if (! is_string($content) || $content === '' || $token === '') {
            return;
        }

        $headers = collect($response->headers->all())
            ->only(self::KEEP_HEADERS)
            ->all();

        Cache::put(self::key($request), [
            'content' => str_replace($token, self::CSRF_PLACEHOLDER, $content),
            'status' => 200,
            'headers' => $headers,
        ], $ttl);

        $response->headers->set('X-Page-Cache', 'MISS');
    }

    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
    }

    private static function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /** Responses that set cookies beyond session/XSRF are personalised — never cache. */
    private static function setsAppCookies(Response $response): bool
    {
        $session = (string) config('session.cookie');

        foreach ($response->headers->getCookies() as $cookie) {
            if (! in_array($cookie->getName(), [$session, 'XSRF-TOKEN'], true)) {
                return true;
            }
        }

        return false;
    }
}
