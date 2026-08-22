<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Locale configuration
|--------------------------------------------------------------------------
|
| Single source of truth for supported locales. Consumed by:
|   - App\Http\Middleware\SetLocale         (request handling)
|   - App\Http\Middleware\HandleInertiaRequests (shared props)
|   - routes/web.php                        (route prefix constraint)
|   - App\Traits\HasTranslations             (translatable columns)
|   - Filament translatable field tabs
|
| Font families map to the Figma type systems:
|   en -> "EN-Desktop/*" tokens  -> Poppins
|   fa -> "AR-Desktop/*" tokens  -> Doran FaNum (fallback Vazirmatn)
|   ar -> "AR-Desktop/*" tokens  -> Doran FaNum (fallback Vazirmatn)
|
| FA-ONLY, 2026-08-21 (user decision). The site ships Persian only, and the
| Figma file backs that up — it contains no English or Arabic frame anywhere
| (.figma-sync/GAPS.md G6). Only `fa` is listed under `supported`, so the
| language switcher collapses, `/en/*` and `/ar/*` stop resolving, and Filament
| renders a single tab per translatable field.
|
| The multilingual machinery itself is deliberately untouched — the
| `{entity}_translations` tables, HasTranslations, the `{locale}` route prefix
| and lang/{en,ar} all stay. Restoring a locale is a one-line move: cut its
| block out of `inactive` below and paste it back into `supported`.
*/

return [

    'default' => env('APP_LOCALE', 'fa'),

    'fallback' => env('APP_FALLBACK_LOCALE', 'fa'),

    /*
     | Languages the Filament panel chrome can be shown in. Separate from
     | `supported` on purpose: that list is the public CONTENT locales, this one
     | is the admin UI, and the two move independently — the site is fa-only
     | while the panel still offers English. Read by routes/web.php and
     | AdminLocaleController, which each used to hardcode their own copy.
     */
    'admin' => ['en', 'fa'],

    /*
     | Ordered map. Key = URL segment + DB translation key.
     | Order controls the language-switcher rendering order.
     */
    'supported' => [

        'fa' => [
            'name'        => 'Persian',
            'native'      => 'فارسی',
            'direction'   => 'rtl',
            'font'        => 'arabic',
            'html_lang'   => 'fa-IR',
            'locale'      => 'fa_IR',
            'flag'        => 'IR',
            'date_format' => 'Y/m/d',
            /*
             | Extended Arabic-Indic (U+06F0–U+06F9) — the Persian set. Note
             | this is a DIFFERENT Unicode block from Arabic's (U+0660–U+0669);
             | the two look similar for some digits but must not be swapped.
             */
            'digits'      => '۰۱۲۳۴۵۶۷۸۹',
        ],

    ],

    /*
     | Parked, not deleted. Same shape as `supported`; nothing reads this key.
     | Move a block back up to bring the locale online again — every consumer
     | listed at the top of this file picks it up with no further change.
     */
    'inactive' => [

        'en' => [
            'name'        => 'English',
            'native'      => 'English',
            'direction'   => 'ltr',
            'font'        => 'sans',
            'html_lang'   => 'en',
            'locale'      => 'en_US',
            'flag'        => 'GB',
            'date_format' => 'M d, Y',
            // Western Arabic (ASCII) digits — no transliteration needed.
            'digits'      => null,
        ],

        'ar' => [
            'name'        => 'Arabic',
            'native'      => 'العربية',
            'direction'   => 'rtl',
            'font'        => 'arabic',
            'html_lang'   => 'ar',
            'locale'      => 'ar_OM',
            'flag'        => 'OM',
            'date_format' => 'Y/m/d',
            // Arabic-Indic (U+0660–U+0669) — not the Persian set above.
            'digits'      => '٠١٢٣٤٥٦٧٨٩',
        ],

    ],

];
