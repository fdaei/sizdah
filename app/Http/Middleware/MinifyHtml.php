<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Performance\HtmlMinifier;
use App\Support\Performance;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Minifies full HTML responses when enabled in the panel (Inertia JSON is left alone). */
final class MinifyHtml
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! Performance::minifyHtml()
            || $request->header('X-Inertia')
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')
            || $response->getStatusCode() >= 300
            || ! is_string($response->getContent())) {
            return $response;
        }

        $response->setContent(HtmlMinifier::minify($response->getContent()));

        return $response;
    }
}
