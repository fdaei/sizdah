<?php
// PHP presentation data migrated from resources/js/Layouts/MobileMenu.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['emit'] = null;
$v['page'] = $page;
$v['items'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'navigation'), 'header'); })();
$v['cta'] = (function() use (&$v) { return (\App\View\Site::method(($v['items'] ?? null), 'find', [function($l_item = null) use (&$v) { return \App\View\Site::get($l_item, 'isCta'); }]) ?? null); })();
$v['links'] = (function() use (&$v) { return \App\View\Site::method(($v['items'] ?? null), 'filter', [function($l_item = null) use (&$v) { return (!\App\View\Site::truth(\App\View\Site::get($l_item, 'isCta'))); }]); })();
$v['panel'] = null;
\App\View\Site::prepare('Layouts/MobileMenu', $v);
