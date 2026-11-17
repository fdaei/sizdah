<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Preview;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Turns a signed `?preview=` link from the admin panel into a one-request
 * unlock of that record (see App\Support\Preview). Requires a logged-in
 * session as well as a valid signature; an invalid or expired link is
 * ignored, so the page renders exactly as the public sees it.
 *
 * Must run before SubstituteBindings (route binding filters by published()).
 */
final class ResolvePreview
{
    public function handle(Request $request, Closure $next): Response
    {
        Preview::reset();

        $token = $request->query('preview');
        $signature = $request->query('signature');

        if (! is_string($token) || ! is_string($signature) || $request->user() === null) {
            return $next($request);
        }

        if (! Preview::activate($token, $signature)) {
            return $next($request);
        }

        try {
            $response = $next($request);
        } finally {
            Preview::reset();
        }

        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        $response->headers->set('Cache-Control', 'no-store, private');

        return $response;
    }
}
