@if ($image ?? null)
    <picture>
        @if ($image['avif'] ?? null)
            <source srcset="{{ $image['avif'] }}" sizes="{{ $image['sizes'] ?? '100vw' }}" type="image/avif">
        @endif
        @if ($image['webp'] ?? null)
            <source srcset="{{ $image['webp'] }}" sizes="{{ $image['sizes'] ?? '100vw' }}" type="image/webp">
        @endif
        <img src="{{ $image['src'] }}" @if ($image['srcset'] ?? null) srcset="{{ $image['srcset'] }}" sizes="{{ $image['sizes'] ?? '100vw' }}" @endif
             alt="{{ $image['alt'] }}" width="{{ $image['width'] }}" height="{{ $image['height'] }}"
             class="my-6 h-auto max-w-full rounded-sm" loading="lazy" decoding="async">
    </picture>
@endif
