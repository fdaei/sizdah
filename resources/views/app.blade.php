@php
    /**
     * Inertia root view.
     *
     * Sets lang/dir from the resolved locale so the very first paint is already
     * correct — no flash of LTR before Vue hydrates. The font class picks the
     * Poppins (EN) or Vazirmatn (FA/AR) stack defined in tailwind.config.js.
     */
    $locale    = app()->getLocale();
    $config    = config("locales.supported.{$locale}");
    $direction = $config['direction'];
    $htmlLang  = $config['html_lang'];
    $fontClass = $config['font'] === 'arabic' ? 'font-arabic' : 'font-sans';
@endphp
<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $direction }}" class="{{ $fontClass }} antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    {{-- Figma Black/1000 — the page ground for every frame in the file --}}
    <meta name="theme-color" content="#141414">

    {{-- Self-hosted fonts: resources/css/fonts.css --}}
    <link rel="preconnect" href="{{ config('app.url') }}">
    @if ($config['font'] === 'arabic')
        <link rel="preload" href="/fonts/vazirmatn/Vazirmatn-Regular.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="/fonts/vazirmatn/Vazirmatn-Medium.woff2" as="font" type="font/woff2" crossorigin>
    @else
        <link rel="preload" href="/fonts/poppins/Poppins-Regular.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="/fonts/poppins/Poppins-SemiBold.woff2" as="font" type="font/woff2" crossorigin>
    @endif

    {{-- hreflang: every page in every language + x-default --}}
    @isset($page['props']['alternates'])
        @foreach ($page['props']['alternates'] as $code => $url)
            <link rel="alternate" hreflang="{{ config("locales.supported.{$code}.html_lang") }}" href="{{ $url }}">
        @endforeach
        <link rel="alternate" hreflang="x-default"
              href="{{ $page['props']['alternates'][config('locales.default')] ?? url('/') }}">
    @endisset

    @routes
    @vite(['resources/js/app.ts'])
    @inertiaHead
</head>
<body class="bg-ink-1000 text-ink-300">
    {{-- Skip link — first focusable element, revealed on focus --}}
    <a href="#main"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:start-4 focus:z-menu
              focus:rounded-sm focus:bg-brand focus:px-4 focus:py-3 focus:text-ink-1000">
        {{ __('common.skip_to_content') }}
    </a>

    @inertia
</body>
</html>
