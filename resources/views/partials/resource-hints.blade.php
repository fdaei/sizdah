{{-- Admin-managed resource hints (Filament: عملکرد → تنظیمات عملکرد). --}}
@php($hints = \App\Support\Performance::hints())
@foreach ($hints['preconnect'] as $url)
    <link rel="preconnect" href="{{ $url }}" crossorigin>
@endforeach
@foreach ($hints['dnsPrefetch'] as $url)
    <link rel="dns-prefetch" href="{{ $url }}">
@endforeach
@foreach ($hints['preload'] as $p)
    <link rel="preload" href="{{ $p['href'] }}" as="{{ $p['as'] }}"@if ($p['type']) type="{{ $p['type'] }}"@endif @if ($p['crossorigin']) crossorigin @endif>
@endforeach
@foreach ($hints['prefetch'] as $url)
    <link rel="prefetch" href="{{ $url }}">
@endforeach
