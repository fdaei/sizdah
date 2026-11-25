<?php
// PHP presentation data migrated from resources/js/Components/FilterChips.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['variant' => 'outline', 'singleLine' => false], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['frameClass'] = function($l_value = null) use (&$v) {$l_active = ($l_value === \App\View\Site::get(($v['props'] ?? null), 'active'));
return ['pointer-events-none absolute inset-y-0 transition-colors duration-200 ease-brand', (\App\View\Site::truth((!\App\View\Site::truth($l_active))) ? 'bg-ink-300 group-hover:bg-paper' : null), (\App\View\Site::truth($l_active) ? 'bg-brand' : null)]; };
$v['chipClass'] = function($l_value = null) use (&$v) {$l_active = ($l_value === \App\View\Site::get(($v['props'] ?? null), 'active'));
return ['group relative inline-flex min-h-[48px] items-center justify-center whitespace-nowrap rounded-lg px-[26px] py-[14px] text-body-lg transition-colors max-md:text-[12px] max-md:leading-[15px] duration-200 ease-brand', (\App\View\Site::truth((!\App\View\Site::truth($l_active))) ? 'text-paper' : null), (\App\View\Site::truth((\App\View\Site::truth($l_active) ? (\App\View\Site::get(($v['props'] ?? null), 'variant') === 'solid') : null)) ? 'bg-brand text-ink-1000' : null), (\App\View\Site::truth((\App\View\Site::truth($l_active) ? (\App\View\Site::get(($v['props'] ?? null), 'variant') === 'outline') : null)) ? 'bg-ink-900 text-brand' : null)]; };
$v['emit'] = null;
\App\View\Site::prepare('Components/FilterChips', $v);
