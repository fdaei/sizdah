<?php
// PHP presentation data migrated from resources/js/Pages/Insights/Show.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['relatedRuleUrl'] = '/site/images/sizdah/insights/related-rule-ecc7ae7fbe.svg';
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['tags'] = (function() use (&$v) { return \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'post'), 'tags'), 'filter', [function($l_tag = null) use (&$v) { return (\App\View\Site::truth(\App\View\Site::get($l_tag, 'slug')) ? \App\View\Site::get($l_tag, 'name') : null); }]); })();
$v['articleBody'] = null;
$v['articleContent'] = (function() use (&$v) {$l_marker = '[[lead_magnet]]';
$l_content = \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'post'), 'content');
$l_index = \App\View\Site::method($l_content, 'indexOf', [$l_marker]);
if (($l_index < 0)) {return ['before' => $l_content, 'after' => '', 'hasMarker' => false];}
return ['before' => \App\View\Site::method($l_content, 'slice', [0, $l_index]), 'after' => \App\View\Site::method($l_content, 'slice', [\App\View\Site::add($l_index, \App\View\Site::length($l_marker))]), 'hasMarker' => true]; })();
\App\View\Site::prepare('Pages/Insights/Show', $v);
