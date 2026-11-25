<?php
// PHP presentation data migrated from resources/js/Components/WhyUsGrid.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['outcomesUrl'] = '/site/images/sizdah/home/why-outcomes-61a735b8a8.svg';
$v['brandUrl'] = '/site/images/sizdah/home/why-brand-b75bc20a3d.svg';
$v['endToEndUrl'] = '/site/images/sizdah/home/why-endtoend-abcce49c33.svg';
$v['qualityUrl'] = '/site/images/sizdah/home/why-quality-c3b0edae3b.svg';
$v['ruleVerticalUrl'] = '/site/images/sizdah/home/why-rule-vertical-f81dbcb104.svg';
$v['ruleTopUrl'] = '/site/images/sizdah/home/why-rule-a-4eae720d48.svg';
$v['ruleBottomUrl'] = '/site/images/sizdah/home/why-rule-b-4eae720d48.svg';
$v['GLYPHS'] = [($v['outcomesUrl'] ?? null), ($v['brandUrl'] ?? null), ($v['endToEndUrl'] ?? null), ($v['qualityUrl'] ?? null)];
$v['DESKTOP_POSITIONS'] = ['xl:left-[360px] xl:top-[71px] xl:w-[283px] xl:justify-between', 'xl:left-[46px] xl:top-[81px] xl:w-[266px] xl:justify-between', 'xl:left-[362px] xl:top-[244px] xl:w-[275px] xl:justify-between', 'xl:left-[46px] xl:top-[282px] xl:w-[264px] xl:justify-between'];
$v['claims'] = (function() use (&$v) { return \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'section'), 'items'), 'map', [function($l_item = null, $l_index = null) use (&$v) { return ['item' => $l_item, 'glyph' => \App\View\Site::get(($v['GLYPHS'] ?? null), ($l_index % \App\View\Site::length(($v['GLYPHS'] ?? null))))]; }]); })();
\App\View\Site::prepare('Components/WhyUsGrid', $v);
