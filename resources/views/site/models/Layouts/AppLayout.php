<?php
// PHP presentation data migrated from resources/js/Layouts/AppLayout.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['siteBackgroundUrl'] = '/site/images/sizdah/shared/site-background-5fd7afa988.webp';
$v['page'] = $page;
$v['gridPages'] = ['Work/Index', 'Work/Show', 'Services', 'Insights/Index', 'Insights/Show', 'Contact', 'About', 'Legal', 'Error'];
$v['washed'] = (function() use (&$v) { return (\App\View\Site::truth((\App\View\Site::get(($v['page'] ?? null), 'component') !== 'Home')) ? (!\App\View\Site::truth(\App\View\Site::method(($v['gridPages'] ?? null), 'includes', [\App\View\Site::get(($v['page'] ?? null), 'component')]))) : null); })();
$v['gridBackground'] = (function() use (&$v) { return \App\View\Site::method(($v['gridPages'] ?? null), 'includes', [\App\View\Site::get(($v['page'] ?? null), 'component')]); })();
$v['nonHomeBackground'] = (function() use (&$v) { return (\App\View\Site::get(($v['page'] ?? null), 'component') !== 'Home'); })();
$v['workTopBackground'] = (function() use (&$v) { return (\App\View\Site::get(($v['page'] ?? null), 'component') === 'Work/Index'); })();
$v['topBackgroundStyles'] = [];
$v['menuOpen'] = false;
$v['breadcrumbs'] = (function() use (&$v) { return (\App\View\Site::truth((\App\View\Site::get(($v['page'] ?? null), 'component') === 'Error')) ? [] : (\App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'seo'), 'breadcrumbs') ?? [])); })();
\App\View\Site::prepare('Layouts/AppLayout', $v);
