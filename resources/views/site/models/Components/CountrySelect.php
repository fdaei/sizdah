<?php
// PHP presentation data migrated from resources/js/Components/CountrySelect.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['caretUrl'] = '/site/images/sizdah/contact/field-caret-1ac2d92769.svg';
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['open'] = false;
$v['query'] = '';
$v['filtered'] = $v['countries'];
\App\View\Site::prepare('Components/CountrySelect', $v);
