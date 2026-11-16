{{--
    Site-wide SEO head bits from "SEO settings" (ManageSeoSettings):
    webmaster verification metas and the admin-only custom header code.
    Rendered on every page, including error pages.
--}}
@php
    $verify = [
        'google-site-verification' => \App\Support\SiteSettings::get('seo_verify_google'),
        'msvalidate.01' => \App\Support\SiteSettings::get('seo_verify_bing'),
        'yandex-verification' => \App\Support\SiteSettings::get('seo_verify_yandex'),
        'baidu-site-verification' => \App\Support\SiteSettings::get('seo_verify_baidu'),
    ];
@endphp
@foreach ($verify as $name => $code)
    @if (filled($code))
        <meta name="{{ $name }}" content="{{ $code }}">
    @endif
@endforeach
{{-- Admin-authored (admin role only); intentionally unescaped, like WordPress header scripts. --}}
{!! \App\Support\SiteSettings::get('seo_head_code') ?: '' !!}
