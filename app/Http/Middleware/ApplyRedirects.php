<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Redirect;
use App\Services\Redirects\RedirectResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies admin-managed redirects before routing (global middleware), so a
 * rule works for any old URL — including ones that no longer match a route.
 * GET/HEAD only; the admin panel, Livewire and built assets are never touched.
 * The original query string is carried over unless the target has its own.
 */
final class ApplyRedirects
{
    public function __construct(private readonly RedirectResolver $resolver) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodSafe() || $request->is('admin', 'admin/*', 'livewire/*', 'build/*', 'storage/*', 'filament/*')) {
            return $next($request);
        }

        $match = $this->resolver->match($request->decodedPath());
        if ($match === null) {
            return $next($request);
        }

        [$redirect, $captures] = $match;
        $redirect->recordHit();

        if (in_array($redirect->status_code, Redirect::GONE, true)) {
            abort($redirect->status_code);
        }

        $target = (string) $redirect->targetUrl($captures);
        $query = $request->getQueryString();
        if ($query !== null && parse_url($target, PHP_URL_QUERY) === null) {
            [$base, $fragment] = array_pad(explode('#', $target, 2), 2, null);
            $target = $base.'?'.$query.($fragment !== null ? '#'.$fragment : '');
        }

        // Never redirect a URL onto itself (misconfigured rule).
        if (rtrim($target, '/') === rtrim($request->fullUrl(), '/')) {
            return $next($request);
        }

        return redirect()->away($target, $redirect->status_code);
    }
}
