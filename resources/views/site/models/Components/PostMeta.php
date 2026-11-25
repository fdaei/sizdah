<?php
// PHP presentation data migrated from resources/js/Components/PostMeta.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['calendarUrl'] = '/site/images/sizdah/shared/calendar-e284538365.svg';
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
\App\View\Site::prepare('Components/PostMeta', $v);
