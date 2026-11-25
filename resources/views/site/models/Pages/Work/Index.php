<?php
// PHP presentation data migrated from resources/js/Pages/Work/Index.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['filterOptions'] = (function() use (&$v) { return array_merge([['value' => null, 'label' => (($v['t'] ?? null))('common.all'), 'href' => route('work.index')]], (\App\View\Site::method(\App\View\Site::get(($v['props'] ?? null), 'filters'), 'map', [function($l_filter = null) use (&$v) { return ['value' => \App\View\Site::get($l_filter, 'slug'), 'label' => \App\View\Site::get($l_filter, 'name'), 'href' => route('work.index', ['service' => \App\View\Site::get($l_filter, 'slug')])]; }]) ?? [])); })();
$v['finalCta'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'final_cta'); })();
\App\View\Site::prepare('Pages/Work/Index', $v);
