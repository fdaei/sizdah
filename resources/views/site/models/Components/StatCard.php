<?php
// PHP presentation data migrated from resources/js/Components/StatCard.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['engagementUrl'] = '/site/images/sizdah/home/kpi-engagement-4530e4e71c.svg';
$v['audienceUrl'] = '/site/images/sizdah/home/kpi-audience-e12cd02f0c.svg';
$v['retentionUrl'] = '/site/images/sizdah/home/kpi-retention-58db3a0e12.svg';
$v['cardBgUrl'] = '/site/images/sizdah/home/kpi-card-bg-beeaf6879d.svg';
$v['ICONS'] = ['engagement' => ($v['engagementUrl'] ?? null), 'audience' => ($v['audienceUrl'] ?? null), 'retention' => ($v['retentionUrl'] ?? null)];
$v['iconUrl'] = (function() use (&$v) { return (\App\View\Site::truth(\App\View\Site::get(($v['props'] ?? null), 'icon')) ? \App\View\Site::get(($v['ICONS'] ?? null), \App\View\Site::get(($v['props'] ?? null), 'icon')) : null); })();
\App\View\Site::prepare('Components/StatCard', $v);
