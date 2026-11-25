<?php
// PHP presentation data migrated from resources/js/Pages/About.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['heroIllustrationUrl'] = '/site/images/sizdah/about/creative-team-sizdah-marketing-kerman-da70b63ee2.webp';
$v['heroScriptUrl'] = '/site/images/sizdah/about/hero-script-555f8bc6a4.svg';
$v['storyMarkUrl'] = '/site/images/sizdah/about/story-mark-4795cafd46.svg';
$v['pathIconUrl'] = '/site/images/sizdah/about/principle-path-797e557538.svg';
$v['impactIconUrl'] = '/site/images/sizdah/about/principle-impact-372ea890c5.svg';
$v['identityIconUrl'] = '/site/images/sizdah/about/principle-identity-0f92245bbb.svg';
$v['discoveryIconUrl'] = '/site/images/sizdah/about/principle-discovery-83f922ec8b.svg';
$v['figmaStoryIconUrl'] = '/site/images/sizdah/about/figma-story-icon-bc056bcb9b.svg';
$v['iconFor'] = function($l_item = null, $l_index = null) use (&$v) {if ((\App\View\Site::truth(\App\View\Site::get($l_item, 'icon')) ? \App\View\Site::get(($v['ICONS'] ?? null), \App\View\Site::get($l_item, 'icon')) : null)) {return \App\View\Site::get(($v['ICONS'] ?? null), \App\View\Site::get($l_item, 'icon'));}
return \App\View\Site::get(($v['ICON_ORDER'] ?? null), $l_index); };
$v['page'] = $page;
$v['hero'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'about_hero'); })();
$v['story'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'story'); })();
$v['think'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'how_we_think'); })();
$v['teamSection'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'team'); })();
$v['finalCta'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'sections'), 'final_cta'); })();
$v['showHeroScript'] = (function() use (&$v) { return (\App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'locale'), 'current') === 'fa'); })();
$v['ICONS'] = ['path' => ($v['pathIconUrl'] ?? null), 'impact' => ($v['impactIconUrl'] ?? null), 'identity' => ($v['identityIconUrl'] ?? null), 'discovery' => ($v['discoveryIconUrl'] ?? null)];
$v['ICON_ORDER'] = [($v['discoveryIconUrl'] ?? null), ($v['identityIconUrl'] ?? null), ($v['impactIconUrl'] ?? null), ($v['pathIconUrl'] ?? null)];
$v['TEAM_LOOP_SECONDS'] = 40;
$v['teamTrack'] = null;
$v['teamList'] = [];
$v['teamDragging'] = false;
$v['teamPos'] = 0;
$v['teamLoopWidth'] = 0;
$v['teamHovered'] = false;
$v['teamDragX'] = 0;
$v['teamPointerId'] = null;
$v['teamFrame'] = 0;
$v['teamLastTime'] = 0;
$v['teamResize'] = null;
\App\View\Site::prepare('Pages/About', $v);
