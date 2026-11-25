<?php
// PHP presentation data migrated from resources/js/Pages/Services/Show.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
\App\View\Site::prepare('Pages/Services/Show', $v);
