<?php
// PHP presentation data migrated from resources/js/Components/ProcessStepCard.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['discoveryUrl'] = '/site/images/sizdah/home/step-discovery-4e57c901ab.svg';
$v['strategyUrl'] = '/site/images/sizdah/home/step-strategy-7f308accd7.svg';
$v['productionUrl'] = '/site/images/sizdah/home/step-production-cab152b77c.svg';
$v['publishingUrl'] = '/site/images/sizdah/home/step-publishing-8ac19f4252.svg';
$v['campaignUrl'] = '/site/images/sizdah/home/step-campaign-5cedb352d2.svg';
$v['optimizationUrl'] = '/site/images/sizdah/home/step-optimization-6027154a08.svg';
$v['approvalUrl'] = '/site/images/sizdah/home/step-approval-a5622e5b8c.svg';
$v['ICONS'] = ['discovery' => ($v['discoveryUrl'] ?? null), 'strategy' => ($v['strategyUrl'] ?? null), 'production' => ($v['productionUrl'] ?? null), 'approval' => ($v['approvalUrl'] ?? null), 'publishing' => ($v['publishingUrl'] ?? null), 'campaign' => ($v['campaignUrl'] ?? null), 'optimization' => ($v['optimizationUrl'] ?? null)];
$v['iconUrl'] = (function() use (&$v) { return (\App\View\Site::truth(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'item'), 'icon')) ? \App\View\Site::get(($v['ICONS'] ?? null), \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'item'), 'icon')) : null); })();
\App\View\Site::prepare('Components/ProcessStepCard', $v);
