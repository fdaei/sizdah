<?php
// PHP presentation data migrated from resources/js/Components/HeroJourney.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['mobile' => false], $input);
$v = \App\View\Site::context($input, $page);
$v['strategyUrl'] = '/site/images/sizdah/home/hero/hero-strategy-ebb5d1cc5d.svg';
$v['identityUrl'] = '/site/images/sizdah/home/hero/hero-identity-2dde1cec79.svg';
$v['contentUrl'] = '/site/images/sizdah/home/hero/hero-content-ea16bc3500.svg';
$v['growthUrl'] = '/site/images/sizdah/home/hero/hero-growth-b26278a92a.svg';
$v['arcUrl'] = '/site/images/sizdah/home/hero/hero-arc-e905252940.svg';
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
\App\View\Site::prepare('Components/HeroJourney', $v);
