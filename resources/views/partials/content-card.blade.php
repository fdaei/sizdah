<article class="my-8 space-y-3">
    <h2 class="text-title-lg text-ink-50"><a href="{{ $card['url'] }}">{{ $card['title'] }}</a></h2>
    @include('partials.content-image', ['image' => $card['image'] ?? null])
    @if ($card['excerpt'] ?? '')
        <p>{{ $card['excerpt'] }}</p>
    @endif
    @if ($card['publishedAtIso'] ?? '')
        <time datetime="{{ $card['publishedAtIso'] }}">{{ $card['publishedAt'] }}</time>
    @endif
</article>
