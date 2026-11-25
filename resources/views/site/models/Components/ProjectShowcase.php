<?php
// PHP presentation data migrated from resources/js/Components/ProjectShowcase.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['arrowUrl'] = '/site/images/sizdah/shared/cta-arrow-cca5b7936f.svg';
$v['underlineUrl'] = '/site/images/sizdah/shared/nav-underline-4611dcc1ea.svg';
$v['sidewalkProjectUrl'] = '/site/images/sizdah/home/sidewalk-project-fb9c243504.webp';
$v['projectCategoryUrl'] = '/site/images/sizdah/home/project-category-890d38be1c.svg';
$v['page'] = $page;
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['projectDetailsFallbacks'] = ['fa' => 'جزییات پروژه', 'en' => 'Project details', 'ar' => 'تفاصيل المشروع'];
$v['projectDetailsLabel'] = (function() use (&$v) {if (\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'section'), 'content')) {return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'section'), 'content');}
$l_label = (($v['t'] ?? null))('work.project_details');
return (\App\View\Site::truth(($l_label === 'work.project_details')) ? (\App\View\Site::get(($v['projectDetailsFallbacks'] ?? null), \App\View\Site::get(($v['locale'] ?? null), 'value')) ?? $l_label) : $l_label); })();
$v['active'] = 0;
$v['touchStartX'] = null;
$v['AUTO_ADVANCE_MS'] = 5000;
$v['autoAdvanceTimer'] = null;
\App\View\Site::prepare('Components/ProjectShowcase', $v);
