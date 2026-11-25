<?php
// PHP presentation data migrated from resources/js/Components/InsightsShowcase.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['calendarUrl'] = '/site/images/sizdah/shared/calendar-e284538365.svg';
$v['dividerUrl'] = '/site/images/sizdah/home/insight-divider-2b365e87c5.svg';
$v['lead'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'posts'), 0); })();
$v['rest'] = (function() use (&$v) { return \App\View\Site::method(\App\View\Site::get(($v['props'] ?? null), 'posts'), 'slice', [1, 3]); })();
\App\View\Site::prepare('Components/InsightsShowcase', $v);
