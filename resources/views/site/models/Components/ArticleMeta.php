<?php
// PHP presentation data migrated from resources/js/Components/ArticleMeta.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['subjectUrl'] = '/site/images/sizdah/blog/article-subject-1ebe9c99c1.svg';
$v['authorUrl'] = '/site/images/sizdah/blog/article-author-4f23023be3.svg';
$v['calendarUrl'] = '/site/images/sizdah/shared/calendar-e284538365.svg';
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['chips'] = (function() use (&$v) { return \App\View\Site::method([['key' => 'date', 'icon' => ($v['calendarUrl'] ?? null), 'label' => (($v['t'] ?? null))('blog.date'), 'value' => \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'post'), 'publishedAt')], (\App\View\Site::truth(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'post'), 'category')) ? ['key' => 'subject', 'icon' => ($v['subjectUrl'] ?? null), 'label' => (($v['t'] ?? null))('blog.subject'), 'value' => \App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'post'), 'category'), 'name')] : null), (\App\View\Site::truth(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'post'), 'author')) ? ['key' => 'author', 'icon' => ($v['authorUrl'] ?? null), 'label' => (($v['t'] ?? null))('blog.written_by'), 'value' => \App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'post'), 'author'), 'name')] : null)], 'filter', [function($l_chip = null) use (&$v) { return ($l_chip !== null); }]); })();
\App\View\Site::prepare('Components/ArticleMeta', $v);
