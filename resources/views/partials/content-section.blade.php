<section class="my-10 space-y-4">
    @if ($section['eyebrow'] ?? '')
        <p class="text-brand">{{ $section['eyebrow'] }}</p>
    @endif
    @if (($section['title'] ?? '') && ! ($isHero ?? false))
        <h2 class="text-title-lg text-ink-50">{{ $section['title'] }}</h2>
    @endif
    @foreach (['subtitle', 'description'] as $field)
        @if ($section[$field] ?? '')
            <p>{{ $section[$field] }}</p>
        @endif
    @endforeach
    @if ($section['content'] ?? '')
        {{-- Same admin-authored rich text rendered by the Vue pages. --}}
        <div class="rich-prose">{!! $section['content'] !!}</div>
    @endif
    @include('partials.content-image', ['image' => $section['image'] ?? null])
    @foreach ($section['items'] ?? [] as $item)
        <article class="space-y-3">
            @if ($item['title'] ?? '')
                <h3 class="text-title-sm text-ink-50">{{ $item['title'] }}</h3>
            @endif
            @foreach (['value', 'suffix', 'label', 'badge', 'description', 'footer'] as $field)
                @if ($item[$field] ?? '')
                    <p>{{ $item[$field] }}</p>
                @endif
            @endforeach
            @if ($item['features'] ?? [])
                <ul class="list-disc ps-6">
                    @foreach ($item['features'] as $feature)
                        <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            @endif
            @include('partials.content-image', ['image' => $item['image'] ?? null])
        </article>
    @endforeach
    @foreach (['primaryCta', 'secondaryCta'] as $field)
        @if (($section[$field]['url'] ?? '') && $section[$field]['url'] !== '#')
            <a class="inline-block underline" href="{{ $section[$field]['url'] }}">{{ $section[$field]['label'] }}</a>
        @endif
    @endforeach
</section>
