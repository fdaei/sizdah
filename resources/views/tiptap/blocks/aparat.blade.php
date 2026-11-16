@php($hash = \App\Filament\Support\Editor\Blocks\AparatBlock::hash((string) ($url ?? '')))
@if ($hash)
    <figure class="rich-embed" data-embed="aparat">
        <iframe src="https://www.aparat.com/video/video/embed/videohash/{{ $hash }}/vt/frame"
                title="{{ $title ?? 'ویدیو آپارات' }}" loading="lazy" allowfullscreen
                style="aspect-ratio:16/9; width:100%; border:0"></iframe>
        @if (! empty($title))<figcaption>{{ $title }}</figcaption>@endif
    </figure>
@endif
