@php
    /**
     * Google Tag Manager + GA4 head snippets (config/services.php → google).
     * Inertia navigations are pushState changes, which GA4's enhanced
     * measurement and GTM's History Change trigger both pick up — no
     * per-visit JS hook needed.
     */
    $gtmId = config('services.google.tag_manager_id');
    $gaId  = config('services.google.analytics_id');
    $searchConsoleVerification = config('services.google.search_console_verification');
@endphp
@if ($searchConsoleVerification)
    <meta name="google-site-verification" content="{{ $searchConsoleVerification }}">
@endif
@if (config('services.google.analytics_enabled'))
    @if ($gtmId || $gaId)
        <script>
        (function () {
            if (window.sizdahAnalyticsScheduled) return;
            window.sizdahAnalyticsScheduled = true;
            window.dataLayer = window.dataLayer || [];
            var loaded = false, timer;
            var events = ['pointerdown', 'keydown', 'scroll'];
            @if (! $gtmId)
                window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
                window.gtag('js', new Date());
                window.gtag('config', @json($gaId));
            @endif
            function loadAnalytics() {
                if (loaded) return;
                loaded = true;
                clearTimeout(timer);
                events.forEach(function (event) { window.removeEventListener(event, loadAnalytics); });
                var script = document.createElement('script');
                script.async = true;
                @if ($gtmId)
                    window.dataLayer.push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
                    script.src = 'https://www.googletagmanager.com/gtm.js?id=' + @json($gtmId);
                @else
                    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + @json($gaId);
                @endif
                document.head.appendChild(script);
            }
            events.forEach(function (event) { window.addEventListener(event, loadAnalytics, {passive: true}); });
            function afterLoad() { if (!loaded) timer = setTimeout(loadAnalytics, 3000); }
            if (document.readyState === 'complete') afterLoad();
            else window.addEventListener('load', afterLoad, {once: true});
        })();
        </script>
    @endif
@endif
