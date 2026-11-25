<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

final class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale('fa');

        return $next($request);
    }
}
