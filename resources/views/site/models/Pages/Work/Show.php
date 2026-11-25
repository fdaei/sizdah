<?php
// PHP presentation data migrated from resources/js/Pages/Work/Show.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['servicesIconUrl'] = '/site/images/sizdah/work/meta-services-63c33e799c.svg';
$v['instagramIconUrl'] = '/site/images/sizdah/work/meta-instagram-e9ce06358c.svg';
$v['yearIconUrl'] = '/site/images/sizdah/work/meta-year-2eaf4fdae6.svg';
$v['industryIconUrl'] = '/site/images/sizdah/work/meta-industry-37a644189f.svg';
$v['challengeMarkUrl'] = '/site/images/sizdah/work/challenge-mark-571100e5d6.svg';
$v['nextArrowUrl'] = '/site/images/sizdah/work/next-arrow-cbfef64704.svg';
$v['resultRoiUrl'] = '/site/images/sizdah/work/result-roi-e7356ceae8.svg';
$v['resultReachUrl'] = '/site/images/sizdah/work/result-reach-4e433d538e.svg';
$v['resultInteractionUrl'] = '/site/images/sizdah/work/result-interaction-e95b6e05c4.svg';
$v['resultFollowerUrl'] = '/site/images/sizdah/work/result-follower-859ee69469.svg';
$v['resultViewUrl'] = '/site/images/sizdah/work/result-view-721ea86a71.svg';
$v['strategyRuleUrl'] = '/site/images/sizdah/work/strategy-rule-h-177612dd9f.svg';
$v['strategyRuleVerticalUrl'] = '/site/images/sizdah/work/strategy-rule-v-7ebdd5aa48.svg';
$v['resultIcon'] = function($l_result = null) use (&$v) {$l_key = (\App\View\Site::get(($v['resultIconAliases'] ?? null), (\App\View\Site::method(\App\View\Site::method(\App\View\Site::get($l_result, 'icon'), 'trim', []), 'toLowerCase', []) ?? '')) ?? \App\View\Site::get(($v['resultIconAliases'] ?? null), \App\View\Site::method(\App\View\Site::method(\App\View\Site::get($l_result, 'label'), 'trim', []), 'toLowerCase', [])));
return (\App\View\Site::truth($l_key) ? \App\View\Site::get(($v['resultIcons'] ?? null), $l_key) : null); };
$v['ordinal'] = function($l_index = null) use (&$v) {return \App\View\Site::method(\App\View\Site::text(\App\View\Site::add($l_index, 1)), 'padStart', [2, '0']); };
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['meta'] = \App\View\Site::method([['key' => 'industry', 'icon' => ($v['industryIconUrl'] ?? null), 'value' => \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'industry')], ['key' => 'year', 'icon' => ($v['yearIconUrl'] ?? null), 'value' => \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'year')], ['key' => 'instagram', 'icon' => ($v['instagramIconUrl'] ?? null), 'value' => \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'instagram'), 'underline' => true], ['key' => 'services', 'icon' => ($v['servicesIconUrl'] ?? null), 'value' => \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'services'), 'join', ['، '])]], 'filter', [function($l_item = null) use (&$v) { return \App\View\Site::get($l_item, 'value'); }]);
$v['cardBlocks'] = \App\View\Site::method([['key' => 'goals', 'items' => \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'goals'), 'columns' => 'gap-4 sm:grid-cols-2 lg:grid-cols-4', 'lead' => 'mt-20 md:mt-[136px]'], ['key' => 'deliverables', 'items' => \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'deliverables'), 'columns' => 'gap-x-4 gap-y-10 sm:grid-cols-2 lg:grid-cols-3', 'lead' => 'mt-[104px] md:mt-[233px]']], 'filter', [function($l_block = null) use (&$v) { return (\App\View\Site::length(\App\View\Site::get($l_block, 'items')) > 0); }]);
$v['resultIcons'] = ['roi' => ['src' => ($v['resultRoiUrl'] ?? null), 'width' => 32, 'height' => 32], 'reach' => ['src' => ($v['resultReachUrl'] ?? null), 'width' => 32, 'height' => 32], 'interaction' => ['src' => ($v['resultInteractionUrl'] ?? null), 'width' => 28, 'height' => 28], 'follower' => ['src' => ($v['resultFollowerUrl'] ?? null), 'width' => 32, 'height' => 32], 'view' => ['src' => ($v['resultViewUrl'] ?? null), 'width' => 32, 'height' => 32]];
$v['resultIconAliases'] = ['roi' => 'roi', 'بازگشت سرمایه' => 'roi', 'reach' => 'reach', 'دسترسی' => 'reach', 'interaction' => 'interaction', 'تعامل' => 'interaction', 'follower' => 'follower', 'دنبال‌کننده' => 'follower', 'view' => 'view', 'بازدید' => 'view'];
$v['activeTag'] = null;
$v['showcaseFilters'] = (function() use (&$v) {$l_tags = (array_values(array_unique(\App\View\Site::method(\App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'showcase'), 'map', [function($l_image = null) use (&$v) { return \App\View\Site::get($l_image, 'tag'); }]), 'filter', [($v['Boolean'] ?? null)]), SORT_REGULAR)) ?? []);
if ((\App\View\Site::length($l_tags) === 0)) {return [];}
return array_merge([['value' => null, 'label' => (($v['t'] ?? null))('common.all')]], (\App\View\Site::method($l_tags, 'map', [function($l_tag = null) use (&$v) { return ['value' => $l_tag, 'label' => $l_tag]; }]) ?? [])); })();
$v['visibleShowcase'] = (function() use (&$v) { return (\App\View\Site::truth((($v['activeTag'] ?? null) === null)) ? \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'showcase') : \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'showcase'), 'filter', [function($l_image = null) use (&$v) { return (\App\View\Site::get($l_image, 'tag') === ($v['activeTag'] ?? null)); }])); })();
$v['strategyColumns'] = (function() use (&$v) {$l_cells = \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'strategy'), 'map', [function($l_item = null, $l_index = null) use (&$v) { return ['item' => $l_item, 'index' => $l_index]; }]);
return [\App\View\Site::method($l_cells, 'filter', [function($l_cell = null) use (&$v) { return ((\App\View\Site::get($l_cell, 'index') % 2) === 0); }]), \App\View\Site::method($l_cells, 'filter', [function($l_cell = null) use (&$v) { return ((\App\View\Site::get($l_cell, 'index') % 2) === 1); }])]; })();
$v['strategyLead'] = (function() use (&$v) { return (\App\View\Site::truth((\App\View\Site::length(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'project'), 'deliverables')) > 0)) ? 'mt-[104px] md:mt-[121px]' : 'mt-[104px] md:mt-[233px]'); })();
\App\View\Site::prepare('Pages/Work/Show', $v);
