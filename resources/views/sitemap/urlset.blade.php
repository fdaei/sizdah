<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
@if (\App\Services\Sitemap\SitemapBuilder::stylesheetEnabled())
<?php echo '<?xml-stylesheet type="text/xsl" href="'.e(route('sitemap.xsl')).'"?>'."\n"; ?>
@endif
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        @if (! empty($url['lastmod']))
            <lastmod>{{ $url['lastmod'] }}</lastmod>
        @endif
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ number_format((float) $url['priority'], 1) }}</priority>
        @foreach ($url['alternates'] as $alternate)
            <xhtml:link rel="alternate" hreflang="{{ $alternate['hreflang'] }}" href="{{ $alternate['href'] }}"/>
        @endforeach
        @foreach ($url['images'] as $image)
            <image:image><image:loc>{{ $image }}</image:loc></image:image>
        @endforeach
    </url>
@endforeach
</urlset>
