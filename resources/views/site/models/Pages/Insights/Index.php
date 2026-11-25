<?php
// PHP presentation data migrated from resources/js/Pages/Insights/Index.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['paperclipUrl'] = '/site/images/sizdah/insights/paperclip-534a87b995.svg';
$v['parentOf'] = function($l_slug = null) use (&$v) {$l_parent = (\App\View\Site::get(\App\View\Site::method(($v['bySlug'] ?? null), 'get', [$l_slug]), 'parent') ?? null);
return (\App\View\Site::truth((\App\View\Site::truth($l_parent) ? \App\View\Site::method(($v['bySlug'] ?? null), 'has', [$l_parent]) : null)) ? $l_parent : null); };
$v['optionFor'] = function($l_slug = null, $l_label = null) use (&$v) {return ['value' => $l_slug, 'label' => $l_label, 'href' => route('insights.category', ['category' => $l_slug])]; };
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['finalCta'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'final_cta'); })();
$v['bySlug'] = (function() use (&$v) { return \App\View\Site::object('fromEntries', [\App\View\Site::method(\App\View\Site::get(($v['props'] ?? null), 'categories'), 'map', [function($l_c = null) use (&$v) { return [\App\View\Site::get($l_c, 'slug'), $l_c]; }])]); })();
$v['activeRoot'] = (function() use (&$v) {$l_slug = \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'filters'), 'category');
for ($l_i = 0; (\App\View\Site::truth($l_slug) ? ($l_i < 10) : null); ($l_i++)) {$l_parent = (($v['parentOf'] ?? null))($l_slug);
if (($l_parent === null)) {return $l_slug;}
($l_slug = $l_parent);}
return null; })();
$v['filterOptions'] = (function() use (&$v) { return array_merge([['value' => null, 'label' => (($v['t'] ?? null))('common.all'), 'href' => route('insights.index')]], (\App\View\Site::method(\App\View\Site::method(\App\View\Site::get(($v['props'] ?? null), 'categories'), 'filter', [function($l_c = null) use (&$v) { return ((($v['parentOf'] ?? null))(\App\View\Site::get($l_c, 'slug')) === null); }]), 'map', [function($l_c = null) use (&$v) { return (($v['optionFor'] ?? null))(\App\View\Site::get($l_c, 'slug'), \App\View\Site::get($l_c, 'name')); }]) ?? [])); })();
$v['rootActive'] = (function() use (&$v) { return ($v['activeRoot'] ?? null); })();
$v['subParent'] = (function() use (&$v) {$l_active = \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'filters'), 'category');
if ((!\App\View\Site::truth($l_active))) {return null;}
$l_hasChildren = \App\View\Site::method(\App\View\Site::get(($v['props'] ?? null), 'categories'), 'some', [function($l_c = null) use (&$v, $l_active) { return ((($v['parentOf'] ?? null))(\App\View\Site::get($l_c, 'slug')) === $l_active); }]);
return (\App\View\Site::truth($l_hasChildren) ? $l_active : (($v['parentOf'] ?? null))($l_active)); })();
$v['subOptions'] = (function() use (&$v) {$l_parent = ($v['subParent'] ?? null);
if ((!\App\View\Site::truth($l_parent))) {return [];}
$l_children = \App\View\Site::method(\App\View\Site::get(($v['props'] ?? null), 'categories'), 'filter', [function($l_c = null) use (&$v, $l_parent) { return ((($v['parentOf'] ?? null))(\App\View\Site::get($l_c, 'slug')) === $l_parent); }]);
if ((\App\View\Site::length($l_children) === 0)) {return [];}
return array_merge([(($v['optionFor'] ?? null))($l_parent, (($v['t'] ?? null))('common.all'))], (\App\View\Site::method($l_children, 'map', [function($l_c = null) use (&$v) { return (($v['optionFor'] ?? null))(\App\View\Site::get($l_c, 'slug'), \App\View\Site::get($l_c, 'name')); }]) ?? [])); })();
$v['wide'] = (function() use (&$v) { return \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'posts'), 'data'), 'slice', [0, 2]); })();
$v['rest'] = (function() use (&$v) { return \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'posts'), 'data'), 'slice', [2]); })();
\App\View\Site::prepare('Pages/Insights/Index', $v);
