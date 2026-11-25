<?php
// PHP presentation data migrated from resources/js/Pages/Services.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['ringUrl'] = '/site/images/sizdah/services/ring-36e6556007.svg';
$v['sparkUrl'] = '/site/images/sizdah/shared/spark-0b0c7de551.svg';
$v['underlineUrl'] = '/site/images/sizdah/shared/nav-underline-4611dcc1ea.svg';
$v['finalCta'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'final_cta'); })();
\App\View\Site::prepare('Pages/Services', $v);
