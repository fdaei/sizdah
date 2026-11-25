<?php
// PHP presentation data migrated from resources/js/Layouts/AppFooter.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['footerBloomUrl'] = '/site/images/sizdah/shared/footer-bloom-f85cd24e25.svg';
$v['locationIconUrl'] = '/site/images/sizdah/shared/footer-location-450c832910.svg';
$v['phoneIconUrl'] = '/site/images/sizdah/shared/footer-phone-93ea5fe7c3.svg';
$v['emailIconUrl'] = '/site/images/sizdah/shared/footer-email-42868227cc.svg';
$v['page'] = $page;
$v['settings'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'settings'); })();
$v['columns'] = (function() use (&$v) { return \App\View\Site::get(\App\View\Site::get(\App\View\Site::get(($v['page'] ?? null), 'props'), 'navigation'), 'footer'); })();
$v['year'] = \App\View\Site::year();
$v['contact'] = (function() use (&$v) { return \App\View\Site::get(($v['settings'] ?? null), 'contact'); })();
\App\View\Site::prepare('Layouts/AppFooter', $v);
