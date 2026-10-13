@php
    // Use only the controller's public, translated payload. No extra queries,
    // crawler detection, or separate cache of published content.
    $sections = $props['sections'] ?? [];
    $heroKey = $page['component'] === 'About' ? 'about_hero' : 'hero';
    $hero = $sections[$heroKey] ?? [];
    $post = $props['post'] ?? null;
    $project = $props['project'] ?? null;
    $service = $props['service'] ?? null;
    $status = $props['status'] ?? null;
    $title = $post['title'] ?? $project['title'] ?? $service['title'] ?? $props['title']
        ?? (($props['heading']['title'] ?? '') ?: null) ?? (($hero['title'] ?? '') ?: null) ?? $props['seo']['title'] ?? config('app.name');
    if ($status) {
        $title = __('errors.'.(in_array($status, [403, 404, 429, 500, 503], true) ? $status : 500).'.title');
    }
    $posts = $props['posts']['data'] ?? $props['posts'] ?? [];
@endphp

<div class="container-sizdah py-8">
    <header>
        <nav aria-label="{{ __('common.primary_navigation') }}" class="flex flex-wrap gap-6">
            @foreach (['home', 'about', 'services', 'work.index', 'insights.index', 'contact'] as $name)
                <a class="text-brand underline" href="{{ route($name) }}">{{ __('seo.breadcrumbs.'.$name) }}</a>
            @endforeach
        </nav>
    </header>
    <main id="main" class="py-12 text-body-lg">
        <h1 class="mb-8 text-display-md text-ink-50">{{ $title }}</h1>
        @if ($props['heading']['description'] ?? '')
            <p>{{ $props['heading']['description'] }}</p>
        @endif
        @if ($props['subtitle'] ?? '')
            <p>{{ $props['subtitle'] }}</p>
        @endif
        @if ($props['content'] ?? '')
            <div class="rich-prose">{!! $props['content'] !!}</div>
        @endif

        @foreach ($sections as $key => $section)
            @include('partials.content-section', ['section' => $section, 'isHero' => $key === $heroKey])
        @endforeach

        @if ($post)
            <article class="space-y-6">
                <p>{{ $post['subtitle'] }}</p>
                <p>{{ $post['excerpt'] }}</p>
                <time datetime="{{ $post['publishedAtIso'] }}">{{ $post['publishedAt'] }}</time>
                @if ($post['author'])
                    <p>{{ __('blog.written_by') }}: {{ $post['author']['name'] }}</p>
                @endif
                @include('partials.content-image', ['image' => $post['image']])
                <div class="rich-prose">{!! $post['content'] !!}</div>
                @foreach ($post['related'] as $card)
                    @include('partials.content-card', ['card' => $card])
                @endforeach
            </article>
        @endif

        @if ($project)
            <article class="space-y-6">
                <p>{{ $project['excerpt'] }}</p>
                <p>{{ $project['industry'] }} {{ $project['year'] }}</p>
                <p>{{ implode('، ', $project['services']) }}</p>
                @include('partials.content-image', ['image' => $project['banner'] ?? $project['image']])
                @if ($project['challenge'])
                    <h2 class="text-title-lg text-ink-50">{{ __('work.challenge') }}</h2>
                    <p>{{ $project['challenge'] }}</p>
                @endif
                <ul class="list-disc ps-6">
                    @foreach ($project['challengePoints'] as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
                @foreach (['goals', 'strategy', 'deliverables'] as $key)
                    @if ($project[$key])
                        <section class="space-y-4">
                            <h2 class="text-title-lg text-ink-50">{{ __('work.'.($key === 'strategy' ? 'strategy_title' : $key)) }}</h2>
                            @foreach ($project[$key] as $item)
                                <h3 class="text-title-sm text-ink-50">{{ $item['title'] }}</h3>
                                <p>{{ $item['description'] }}</p>
                            @endforeach
                        </section>
                    @endif
                @endforeach
                @foreach ($project['showcase'] as $image)
                    @include('partials.content-image', ['image' => $image])
                @endforeach
                @if ($project['results'] || $project['resultsSummary'])
                    <h2 class="text-title-lg text-ink-50">{{ __('work.results') }}</h2>
                    @foreach ($project['results'] as $result)
                        <p>{{ $result['label'] }}: {{ $result['value'] }}</p>
                    @endforeach
                    <p>{{ $project['resultsSummary'] }}</p>
                @endif
                @if ($project['next'])
                    <a class="underline" href="{{ $project['next']['url'] }}">{{ $project['next']['title'] }}</a>
                @endif
            </article>
        @endif

        @foreach ($service ? [$service] : ($props['services'] ?? []) as $item)
            <section class="my-8 space-y-4">
                @if (! $service)
                    <h2 class="text-title-lg text-ink-50">
                        @if ($item['slug'] ?? '')
                            <a href="{{ route('services.show', ['service' => $item['slug']]) }}">{{ $item['title'] }}</a>
                        @else
                            {{ $item['title'] }}
                        @endif
                    </h2>
                @endif
                <p>{{ $item['description'] ?? '' }}</p>
                @if ($item['features'] ?? [])
                    <ul class="list-disc ps-6">
                        @foreach ($item['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endforeach

        @foreach ($props['projects'] ?? [] as $card)
            @include('partials.content-card', ['card' => $card])
        @endforeach
        @if ($props['featured'] ?? null)
            @include('partials.content-card', ['card' => $props['featured']])
        @endif
        @foreach ($posts as $card)
            @include('partials.content-card', ['card' => $card])
        @endforeach
        @if (($props['posts']['prevPageUrl'] ?? null) || ($props['posts']['nextPageUrl'] ?? null))
            <nav aria-label="{{ __('common.pagination') }}" class="flex gap-6">
                @if ($props['posts']['prevPageUrl'])
                    <a rel="prev" href="{{ $props['posts']['prevPageUrl'] }}">{{ __('common.previous') }}</a>
                @endif
                @if ($props['posts']['nextPageUrl'])
                    <a rel="next" href="{{ $props['posts']['nextPageUrl'] }}">{{ __('common.next') }}</a>
                @endif
            </nav>
        @endif

        @foreach ($props['faqs'] ?? [] as $faq)
            <section class="my-8 space-y-3">
                <h2 class="text-title-lg text-ink-50">{{ $faq['question'] }}</h2>
                <p>{{ $faq['answer'] }}</p>
            </section>
        @endforeach
        @foreach ($props['team'] ?? [] as $member)
            <section class="my-8 space-y-3">
                <h2 class="text-title-lg text-ink-50">{{ $member['name'] }}</h2>
                <p>{{ $member['role'] }}</p>
                @include('partials.content-image', ['image' => $member['image']])
            </section>
        @endforeach
        @foreach ($props['testimonials'] ?? [] as $testimonial)
            <blockquote class="my-8">
                <p>{{ $testimonial['quote'] }}</p>
                <cite>{{ $testimonial['name'] }} — {{ $testimonial['role'] }}</cite>
            </blockquote>
        @endforeach
        @foreach (['leadMagnet', 'finalCta'] as $key)
            @if ($props[$key] ?? null)
                @include('partials.content-section', ['section' => $props[$key], 'isHero' => false])
            @endif
        @endforeach
        @if ($page['component'] === 'Contact')
            <section class="my-8 space-y-3">
                <h2 class="text-title-lg text-ink-50">{{ __('forms.details.title') }}</h2>
                @foreach (['phone', 'email', 'location', 'workingWith'] as $key)
                    <p>{{ $props['settings']['contact'][$key] }}</p>
                @endforeach
            </section>
        @endif
    </main>
    <footer class="flex flex-wrap gap-6">
        @foreach (['legal.privacy', 'legal.terms'] as $name)
            <a class="underline" href="{{ route($name) }}">{{ __('seo.breadcrumbs.'.$name) }}</a>
        @endforeach
    </footer>
</div>
