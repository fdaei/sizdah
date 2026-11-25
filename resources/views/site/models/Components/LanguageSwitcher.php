<?php
// PHP presentation data migrated from resources/js/Components/LanguageSwitcher.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['urlFor'] = function($l_code = null) use (&$v) {return (\App\View\Site::get(($v['alternates'] ?? null), $l_code) ?? ('/' . \App\View\Site::text($l_code) . '')); };
$v['page'] = $page;
$v['current'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'locale'), 'current'); })();
$v['options'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'locale'), 'supported'); })();
$v['alternates'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'alternates'); })();
\App\View\Site::prepare('Components/LanguageSwitcher', $v);
