<?php
// PHP presentation data migrated from resources/js/Pages/Error.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['lostMapUrl'] = '/site/images/sizdah/errors/lost-map-064ebf6a0b.webp';
$v['page'] = $page;
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['known'] = [403, 404, 429, 500, 503];
$v['key'] = (function() use (&$v) { return (\App\View\Site::truth(\App\View\Site::method(($v['known'] ?? null), 'includes', [\App\View\Site::get(($v['props'] ?? null), 'status')])) ? \App\View\Site::text(\App\View\Site::get(($v['props'] ?? null), 'status')) : '500'); })();
$v['title'] = (function() use (&$v) { return (($v['t'] ?? null))(('errors.' . \App\View\Site::text(($v['key'] ?? null)) . '.title')); })();
$v['appName'] = config('app.name');
$v['message'] = (function() use (&$v) { return (($v['t'] ?? null))(('errors.' . \App\View\Site::text(($v['key'] ?? null)) . '.message')); })();
\App\View\Site::prepare('Pages/Error', $v);
