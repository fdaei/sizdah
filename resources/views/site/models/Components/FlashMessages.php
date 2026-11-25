<?php
// PHP presentation data migrated from resources/js/Components/FlashMessages.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['page'] = $page;
$v['dismissed'] = false;
$v['flash'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'flash'); })();
$v['message'] = (function() use (&$v) { return ((\App\View\Site::get(($v['flash'] ?? null), 'success') ?? \App\View\Site::get(($v['flash'] ?? null), 'error')) ?? null); })();
$v['isError'] = (function() use (&$v) { return (\App\View\Site::truth((\App\View\Site::get(($v['flash'] ?? null), 'error') !== null)) ? (\App\View\Site::get(($v['flash'] ?? null), 'error') !== null) : null); })();
\App\View\Site::prepare('Components/FlashMessages', $v);
