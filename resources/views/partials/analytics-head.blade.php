@php
    /**
     * Google Tag Manager + GA4 head snippets (config/services.php → google).
     * Inertia navigations are pushState changes, which GA4's enhanced
     * measurement and GTM's History Change trigger both pick up — no
     * per-visit JS hook needed.
     */
    $gtmId = config('services.google.tag_manager_id');
    $gaId  = config('services.google.analytics_id');
@endphp
@if (config('services.google.analytics_enabled'))
    @if ($gtmId)
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer',@json($gtmId));</script>
        <!-- End Google Tag Manager -->
    @endif
    @if ($gaId)
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @json($gaId));
        </script>
    @endif
@endif
