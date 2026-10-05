@php
    /**
     * Server-rendered SEO head — the initial-HTML half of the SEO contract
     * built by App\Services\SeoBuilder (technical SEO audit 2026-10-04,
     * SEO-DEV-01/02/04).
     *
     * Rendered by Blade, not Vue, so crawlers get title, description,
     * canonical, robots, hreflang, Open Graph and JSON-LD even when the SSR
     * process is down or JavaScript never runs. Every tag carries an
     * `inertia="<key>"` attribute: on client-side navigations Inertia's head
     * manager replaces them with the identically keyed tags SeoHead.vue
     * renders, so the two never duplicate or drift apart.
     *
     * Pages without a `seo` prop are error pages: noindex, and a title only
     * when SSR did not already render one from Error.vue.
     */
    $seo        = $page['props']['seo'] ?? null;
    $alternates = $page['props']['alternates'] ?? null;
    $status     = $page['props']['status'] ?? null;
    $appName    = config('app.name');
    $locale     = app()->getLocale();
    $ssrHead    = isset($__inertiaSsrResponse) && $__inertiaSsrResponse;
    $schemaJson = isset($seo['schema'])
        ? json_encode($seo['schema'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
        : null;
@endphp
@if ($seo)
    <title inertia>{{ $seo['title'] ? "{$seo['title']} — {$appName}" : $appName }}</title>
    <meta name="description" content="{{ $seo['description'] }}" inertia="description">
    <link rel="canonical" href="{{ $seo['canonical'] }}" inertia="canonical">
    <meta name="robots" content="{{ ($seo['noindex'] ?? false) ? 'noindex, follow' : 'index, follow' }}" inertia="robots">

    @if ($alternates)
        @foreach ($alternates as $code => $url)
            <link rel="alternate" hreflang="{{ config("locales.supported.{$code}.html_lang") }}" href="{{ $url }}" inertia="hreflang-{{ $code }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ $alternates[config('locales.default')] ?? reset($alternates) }}" inertia="hreflang-x-default">
    @endif

    <meta property="og:type" content="{{ $seo['type'] }}" inertia="og:type">
    <meta property="og:title" content="{{ $seo['title'] }}" inertia="og:title">
    <meta property="og:description" content="{{ $seo['description'] }}" inertia="og:description">
    <meta property="og:url" content="{{ $seo['canonical'] }}" inertia="og:url">
    <meta property="og:site_name" content="{{ \App\Support\SiteSettings::get('seo_organization_name', $locale, 'Sizdah') }}" inertia="og:site_name">
    <meta property="og:locale" content="{{ str_replace('-', '_', config("locales.supported.{$locale}.html_lang")) }}" inertia="og:locale">
    @if ($seo['image'])
        <meta property="og:image" content="{{ $seo['image'] }}" inertia="og:image">
    @endif

    <meta name="twitter:card" content="{{ $seo['image'] ? 'summary_large_image' : 'summary' }}" inertia="twitter:card">
    <meta name="twitter:title" content="{{ $seo['title'] }}" inertia="twitter:title">
    <meta name="twitter:description" content="{{ $seo['description'] }}" inertia="twitter:description">
    @if ($seo['image'])
        <meta name="twitter:image" content="{{ $seo['image'] }}" inertia="twitter:image">
    @endif

    @if ($seo['type'] === 'article')
        @if ($seo['publishedAt'] ?? null)
            <meta property="article:published_time" content="{{ $seo['publishedAt'] }}" inertia="article:published_time">
        @endif
        @if ($seo['modifiedAt'] ?? null)
            <meta property="article:modified_time" content="{{ $seo['modifiedAt'] }}" inertia="article:modified_time">
        @endif
        @if ($seo['author'] ?? null)
            <meta property="article:author" content="{{ $seo['author'] }}" inertia="article:author">
        @endif
    @endif

    @if ($schemaJson)
        <script type="application/ld+json" inertia="schema">{!! $schemaJson !!}</script>
    @endif
@else
    @if (! $ssrHead)
        <title inertia>{{ $status ? __('errors.'.(in_array($status, [403, 404, 429, 500, 503], true) ? $status : 500).'.title').' — '.$appName : $appName }}</title>
    @endif
    <meta name="robots" content="noindex, follow" inertia="robots">
@endif
