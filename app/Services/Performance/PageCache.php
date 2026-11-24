<?php

declare(strict_types=1);

namespace App\Services\Performance;

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\ContactSubmission;
use App\Models\ContentRevision;
use App\Models\LeadMagnetRequest;
use App\Models\NewsletterSubscription;
use App\Models\NotFoundLog;
use App\Models\ServiceRating;
use App\Models\User;
use App\Support\TransactionalCache;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
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
        NotFoundLog::class,
        ContactSubmission::class,
        NewsletterSubscription::class,
        LeadMagnetRequest::class,
        ContentRevision::class,
        User::class,
        // Pending ratings are invisible; ServiceRating busts the cache itself on approval changes.
        ServiceRating::class,
        DatabaseNotification::class,
    ];

    private const VERSION_KEY = 'page_cache.version';

    private const CSRF_PLACEHOLDER = '__PAGE_CACHE_CSRF__';

    /** Only public content pages; admin, downloads and future routes opt in explicitly. */
    private const PUBLIC_ROUTES = [
        'home', 'work.index', 'work.industry', 'work.show', 'services', 'services.show',
        'about', 'insights.index', 'insights.category', 'insights.tag', 'insights.show',
        'contact', 'legal.privacy', 'legal.terms',
    ];

    /** Headers worth replaying; everything else (cookies!) is regenerated per request. */
    private const KEEP_HEADERS = ['content-type', 'x-inertia', 'vary', 'link', 'content-language', 'x-robots-tag'];

    public static function isCacheable(Request $request): bool
    {
        return $request->isMethod('GET')
            && in_array($request->route()?->getName(), self::PUBLIC_ROUTES, true)
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
            (string) app(HandleInertiaRequests::class)->version($request),
        ]);

        return 'page_cache.'.self::version().'.'.sha1($variant);
    }

    public static function get(Request $request, ?string $key = null): ?Response
    {
        $hit = Cache::get($key ?? self::key($request));

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

    public static function put(Request $request, Response $response, int $ttl, ?string $key = null): void
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

        Cache::put($key ?? self::key($request), [
            'content' => str_replace($token, self::CSRF_PLACEHOLDER, $content),
            'status' => 200,
            'headers' => $headers,
        ], $ttl);

        $response->headers->set('X-Page-Cache', 'MISS');
    }

    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, (string) Str::uuid());
    }

    private static function version(): string
    {
        return (string) Cache::get(self::VERSION_KEY, '1');
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
