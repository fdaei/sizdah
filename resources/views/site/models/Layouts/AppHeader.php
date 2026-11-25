<?php
// PHP presentation data migrated from resources/js/Layouts/AppHeader.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['underlineUrl'] = '/site/images/sizdah/shared/nav-underline-4611dcc1ea.svg';
$v['doodleUrl'] = '/site/images/sizdah/shared/header-doodle-38464ba867.svg';
$v['isActive'] = function($l_item = null) use (&$v) {if ((!\App\View\Site::truth(\App\View\Site::get($l_item, 'url')))) {return \App\View\Site::method(\App\View\Site::get($l_item, 'children'), 'some', [($v['isActive'] ?? null)]);}
$l_path = \App\View\Site::method(\App\View\Site::get(\App\View\Site::method(($v['currentUrl'] ?? null), 'split', ['?']), 0), 'replace', ['~\\/$~', '']);
$l_target = \App\View\Site::method(\App\View\Site::get(['pathname' => parse_url(\App\View\Site::get($l_item, 'url'), PHP_URL_PATH)], 'pathname'), 'replace', ['~\\/$~', '']);
return ($l_path === $l_target); };
$v['page'] = $page;
$v['items'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'navigation'), 'header'); })();
$v['settings'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'settings'); })();
$v['cta'] = (function() use (&$v) { return (\App\View\Site::method(($v['items'] ?? null), 'find', [function($l_item = null) use (&$v) { return \App\View\Site::get($l_item, 'isCta'); }]) ?? null); })();
$v['links'] = (function() use (&$v) { return \App\View\Site::method(($v['items'] ?? null), 'filter', [function($l_item = null) use (&$v) { return (!\App\View\Site::truth(\App\View\Site::get($l_item, 'isCta'))); }]); })();
$v['currentUrl'] = (function() use (&$v) { return \App\View\Site::get(($v['page'] ?? null), 'url'); })();
\App\View\Site::prepare('Layouts/AppHeader', $v);
