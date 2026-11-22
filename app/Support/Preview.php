<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

/**
 * Admin preview of a single record — published or not — on its real public URL.
 *
 * The admin panel builds a link with `?preview=<token>&signature=<hmac>`; the
 * ResolvePreview middleware verifies it (plus an authenticated session) and
 * calls allow(). From then on, for that one request only, the Publishable
 * `published()` scope also lets that exact row through — so route binding,
 * page-by-key lookups and the shared layout all behave exactly as they will
 * once the record is published. Nothing else is unlocked.
 */
final class Preview
{
    /** Token lifetime. Long enough for an editing session, short enough to not leak. */
    private const TTL_SECONDS = 7200;

    /** Short, stable type names used in the token (never class names). */
    private const TYPES = [
        'post' => Post::class,
        'project' => Project::class,
        'page' => Page::class,
        'service' => Service::class,
    ];

    /** Page `key` -> public route name. */
    private const PAGE_ROUTES = [
        'home' => 'home',
        'work' => 'work.index',
        'services' => 'services',
        'about' => 'about',
        'insights' => 'insights.index',
        'contact' => 'contact',
        'privacy-policy' => 'legal.privacy',
        'terms' => 'legal.terms',
    ];

    /** @var array<class-string, int|string> */
    private static array $allowed = [];

    public static function supports(Model $record): bool
    {
        return array_search($record::class, self::TYPES, true) !== false
            && self::publicUrl($record) !== null;
    }

    /** Public route for a page-builder key, without preview parameters. */
    public static function pageUrl(?string $key, ?string $locale = null): ?string
    {
        $locale ??= (string) config('locales.fallback');

        return $key !== null && isset(self::PAGE_ROUTES[$key])
            ? route(self::PAGE_ROUTES[$key], ['locale' => $locale])
            : null;
    }

    /**
     * Signed, expiring preview URL for the record, or null when it has no public page.
     */
    public static function url(Model $record, ?string $locale = null): ?string
    {
        $locale ??= (string) config('locales.fallback');
        $type = array_search($record::class, self::TYPES, true);
        $base = self::publicUrl($record, $locale);

        if ($type === false || $base === null) {
            return null;
        }

        $token = $type.'.'.$record->getKey().'.'.(time() + self::TTL_SECONDS);

        return $base.(str_contains($base, '?') ? '&' : '?').http_build_query([
            'preview' => $token,
            'signature' => self::sign($token),
        ]);
    }

    /**
     * Validates a token + signature and, when valid, unlocks that record.
     */
    public static function activate(string $token, string $signature): bool
    {
        if (! hash_equals(self::sign($token), $signature)) {
            return false;
        }

        $parts = explode('.', $token);

        if (count($parts) !== 3 || ! isset(self::TYPES[$parts[0]]) || (int) $parts[2] < time()) {
            return false;
        }

        self::$allowed[self::TYPES[$parts[0]]] = $parts[1];

        return true;
    }

    /** Primary key of the record unlocked for this model class, if any. */
    public static function allowedKey(string $class): int|string|null
    {
        return self::$allowed[$class] ?? null;
    }

    public static function active(): bool
    {
        return self::$allowed !== [];
    }

    /** Clears state between requests (Octane / tests). */
    public static function reset(): void
    {
        self::$allowed = [];
    }

    private static function sign(string $token): string
    {
        return hash_hmac('sha256', 'preview|'.$token, (string) config('app.key'));
    }

    private static function publicUrl(Model $record, ?string $locale = null): ?string
    {
        $locale ??= (string) config('locales.fallback');

        return match (true) {
            $record instanceof Post, $record instanceof Project => self::hasSlug($record, $locale)
                ? $record->url($locale)
                : null,
            $record instanceof Service => filled($record->anchor($locale))
                ? route('services.show', ['locale' => $locale, 'service' => $record->anchor($locale)])
                : null,
            $record instanceof Page => self::pageUrl($record->key, $locale),
            default => null,
        };
    }

    private static function hasSlug(Post|Project $record, string $locale): bool
    {
        return filled($record->slugForLocale($locale));
    }
}
