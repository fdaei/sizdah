@php
    $locale = app()->getLocale();
    $config = config('locales.supported.'.$locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $config['html_lang'] }}" dir="{{ $config['direction'] }}" class="{{ $config['font'] === 'arabic' ? 'font-arabic' : 'font-sans' }} antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#141414">
    <link rel="icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico?v=3" sizes="any">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=3">
    @if ($config['font'] === 'arabic')
        <link rel="preload" href="/fonts/peyda/Peyda-Regular-Core.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="/fonts/peyda/Peyda-SemiBold-Core.woff2" as="font" type="font/woff2" crossorigin>
    @endif
    @if ($page['component'] === 'Home')
        @php
            $images = $page['props']['settings']['images'] ?? [];
            $art = json_decode(file_get_contents(resource_path('views/site/assets.json')), true);
            $desktop = $images['top_home_desktop'] ?? $page['props']['sections']['hero']['image']['src'] ?? $images['home_desktop'] ?? $art['sizdah/home/hero-bg.webp'];
            $tablet = $images['top_home_tablet'] ?? $desktop;
            $mobile = $images['top_home_mobile'] ?? $images['home_mobile'] ?? $art['sizdah/home/hero-bg-mobile.webp'];
        @endphp
        <link rel="preload" as="image" href="{{ $mobile }}" media="(max-width:767px)" fetchpriority="high">
        <link rel="preload" as="image" href="{{ $tablet }}" media="(min-width:768px) and (max-width:1023px)" fetchpriority="high">
        <link rel="preload" as="image" href="{{ $desktop }}" media="(min-width:1024px)" fetchpriority="high">
    @endif
    @include('partials.resource-hints')
    @vite('resources/js/site.ts')
    @include('partials.seo')
    @include('partials.seo-site')
    @include('partials.analytics-head')
</head>
<body class="bg-ink-1000 text-ink-300">
    @include('partials.analytics-body')
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:start-4 focus:z-menu focus:rounded-sm focus:bg-brand focus:px-4 focus:py-3 focus:text-ink-1000">{{ __('common.skip_to_content') }}</a>
    {!! \App\View\Site::render('Layouts/AppLayout', [], $page, fn () => \App\View\Site::render('Pages/'.$page['component'], $page['props'], $page)) !!}
    {!! \App\Support\SiteSettings::get('seo_body_code') ?: '' !!}
</body>
</html>
