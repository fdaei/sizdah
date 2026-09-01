<?php

declare(strict_types=1);

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RedirectToLocalisedRoute;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'locale'          => SetLocale::class,
            'locale.redirect' => RedirectToLocalisedRoute::class,
        ]);

        /*
         | SetLocale MUST run before SubstituteBindings.
         |
         | Route-model binding for the localised slug routes resolves through
         | Model::resolveRouteBinding() -> scopeWhereSlug(), which matches the
         | slug against app()->getLocale(). As a route alias, SetLocale would
         | otherwise run *after* SubstituteBindings (a `web` group middleware),
         | so a Persian or Arabic slug was looked up among the English
         | translations and every /fa|ar/work/{slug} and /fa|ar/insights/{slug}
         | URL 404'd while the /en ones resolved.
         */
        $middleware->prependToPriorityList(
            before: SubstituteBindings::class,
            prepend: SetLocale::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /*
         | Render 404/403/419/500 through Inertia so error pages keep the site
         | chrome, the active locale, and the RTL/LTR direction.
         |
         | Figma: 404 = 266:2825 (dark, Persian numerals — canonical). 82:2059
         | is its Latin-numeral light twin and 88:2490 (named "405" in the file,
         | but drawn as ۴۰۴) is its Persian light twin; both are superseded.
         | The file carries no 405 design, so 405 is not in the list below.
         */
        $exceptions->respond(function ($response, Throwable $e, Request $request) {
            if ($request->is('admin*') || $request->expectsJson()) {
                return $response;
            }

            $status = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : $response->getStatusCode();

            if (! in_array($status, [403, 404, 419, 429, 500, 503], true)) {
                return $response;
            }

            if ($status === 419) {
                return back()->with('error', __('errors.session_expired'));
            }

            /*
             | A genuine 404 matches no route at all, so SetLocale — a route
             | middleware — never ran and never called URL::defaults(). The
             | Blade root view still renders the right lang/dir because it
             | falls back independently, but Ziggy's client-side route()
             | ships an empty `defaults` object, and CtaButton's
             | route('home') then throws client-side ("'locale' parameter is
             | required"), blanking the whole page. Resolve and set it here
             | too — idempotent when SetLocale already ran on a matched route.
             */
            $locale = (new SetLocale())->resolve($request);
            AppFacade::setLocale($locale);
            URL::defaults(['locale' => $locale]);

            return inertia('Error', ['status' => $status])
                ->toResponse($request)
                ->setStatusCode($status);
        });
    })
    ->create();
