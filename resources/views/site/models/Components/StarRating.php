<?php
// PHP presentation data migrated from resources/js/Components/StarRating.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['form'] = array_merge(['rating' => 0], ['errors' => [], 'processing' => false, 'recentlySuccessful' => false, 'wasSuccessful' => false]);
$v['hover'] = 0;
\App\View\Site::prepare('Components/StarRating', $v);
