@if ($image ?? null)
    <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}"
         width="{{ $image['width'] }}" height="{{ $image['height'] }}"
         class="my-6 h-auto max-w-full rounded-sm" loading="lazy">
@endif
