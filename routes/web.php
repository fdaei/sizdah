<?php

declare(strict_types=1);

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminLocaleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
|
| Every public route is locale-prefixed. Route names are locale-agnostic
| ("home", "work.show") — the {locale} parameter is supplied automatically by
| URL::defaults() in App\Http\Middleware\SetLocale, so route('work.show', $p)
| stays inside the visitor's current language without extra arguments.
|
| Figma node IDs below are the LIVE ones, re-read 2026-08-21. The IDs that
| used to sit here (1419:*, 1362:*, 908:1576, …) belong to a two-page version
| of the file that no longer exists — docs/TRACEABILITY.md and
| docs/FIGMA-AUDIT.md still cite that generation and are stale. The current
| map is .figma-sync/MANIFEST.md.
|
*/

$localePattern = implode('|', array_keys(config('locales.supported')));

Route::get('admin/language/{locale}', AdminLocaleController::class)
    ->middleware('auth')
    ->whereIn('locale', config('locales.admin'))
    ->name('admin.locale');

// SEO endpoints — not locale-prefixed.
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('sitemap-{locale}.xml', [SitemapController::class, 'locale'])
    ->where('locale', $localePattern)
    ->name('sitemap.locale');

Route::prefix('{locale}')
    ->where(['locale' => $localePattern])
    ->middleware('locale')
    ->group(function (): void {

        // 1 — Home                       Figma 268:2962 (canonical dark)
        Route::get('/', HomeController::class)->name('home');

        // 2 — Projects listing           Figma 222:1989
        Route::get('work', [ProjectController::class, 'index'])->name('work.index');

        // 3 — Single project             Figma 336:5374 ("case study")
        Route::get('work/{project}', [ProjectController::class, 'show'])->name('work.show');

        // 4 — Services                   Figma 308:4492
        // Single page, four sections. No detail routes — see FIGMA-AUDIT §4.
        Route::get('services', ServiceController::class)->name('services');

        // 5 — About                      Figma 336:5623
        Route::get('about', AboutController::class)->name('about');

        // 6 — Blog listing               Figma 268:4158
        Route::get('insights', [PostController::class, 'index'])->name('insights.index');

        // 7 — Single blog                Figma 285:4590
        Route::get('insights/{post}', [PostController::class, 'show'])->name('insights.show');

        // 8 — Contact                    Figma 279:6325
        Route::get('contact', [ContactController::class, 'create'])->name('contact');
        Route::post('contact', [ContactController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('contact.store');

        // Lead magnet / newsletter       Figma 391:4795 (on Home 268:2962).
        // The frame's wording differs from the seeded copy — see GAPS G16.
        Route::post('newsletter', NewsletterController::class)
            ->middleware('throttle:6,1')
            ->name('newsletter.store');

        // 9/10 — Legal                   Figma 279:5924, 281:6773
        Route::get('privacy-policy', [LegalController::class, 'privacy'])->name('legal.privacy');
        Route::get('terms', [LegalController::class, 'terms'])->name('legal.terms');
    });

/*
| Bare-path fallback: /about -> /fa/about, / -> /fa (config locales.default)
| Registered last so it never shadows a real localised route.
*/
Route::middleware('locale.redirect')->group(function (): void {
    Route::get('/', fn () => null);
    Route::get('/{any}', fn () => null)->where('any', '.*');
});
