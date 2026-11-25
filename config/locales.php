<?php

declare(strict_types=1);

// Sizdah is Persian-only. Keep /fa URLs and existing content tables intact.
return [
    'default' => 'fa',
    'fallback' => 'fa',
    'admin' => ['fa'],
    'supported' => [
        'fa' => [
            'name' => 'Persian',
            'native' => 'فارسی',
            'direction' => 'rtl',
            'font' => 'arabic',
            'html_lang' => 'fa-IR',
            'locale' => 'fa_IR',
            'flag' => 'IR',
            'date_format' => 'Y/m/d',
            /*
             | Extended Arabic-Indic (U+06F0–U+06F9) — the Persian set. Note
             | this is a DIFFERENT Unicode block from Arabic's (U+0660–U+0669);
             | the two look similar for some digits but must not be swapped.
             */
            'digits' => '۰۱۲۳۴۵۶۷۸۹',
        ],
    ],
];
