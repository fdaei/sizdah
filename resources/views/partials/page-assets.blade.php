{{-- Discover the current page and its hero before the app entry executes. --}}
@php
    $entry = 'resources/js/Pages/'.$page['component'].'.vue';
    $vite = app(\Illuminate\Foundation\Vite::class);
    $entries = ['resources/js/app.ts'];
    if (! $vite->isRunningHot()) {
        $entries[] = $entry;
    }
@endphp
@if (($page['component'] ?? '') === 'Home')
    @php
        // Keep the same precedence and breakpoints as Home.vue.
        $images = $page['props']['settings']['images'] ?? [];
        $heroImage = $page['props']['sections']['hero']['image']['src'] ?? null;
        $desktop = $images['top_home_desktop'] ?? $heroImage ?? $images['home_desktop'] ?? $vite->asset('resources/images/sizdah/home/hero-bg.webp');
        $tablet = $images['top_home_tablet'] ?? $desktop;
        $mobile = $images['top_home_mobile'] ?? $images['home_mobile'] ?? $vite->asset('resources/images/sizdah/home/hero-bg-mobile.webp');
    @endphp
    <link rel="preload" as="image" href="{{ $mobile }}" media="(max-width: 767px)" fetchpriority="high">
    <link rel="preload" as="image" href="{{ $tablet }}" media="(min-width: 768px) and (max-width: 1023px)" fetchpriority="high">
    <link rel="preload" as="image" href="{{ $desktop }}" media="(min-width: 1024px)" fetchpriority="high">
@endif
{{-- Vite resolves hashed files, emits the page's CSS and modulepreloads its
     imports. Never hardcode a Home-<hash>.js from a previous deployment. --}}
@vite($entries)
