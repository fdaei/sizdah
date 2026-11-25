<?php
// PHP presentation data migrated from resources/js/Components/BrandLogo.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['width' => 104, 'variant' => 'default'], $input);
$v = \App\View\Site::context($input, $page);
$v['logoUrl'] = '/site/images/sizdah/shared/logo-af74333583.svg';
$v['footerLogoUrl'] = '/site/images/sizdah/shared/logo-footer-df6fd3e87e.svg';
$v['src'] = (function() use (&$v) { return (\App\View\Site::truth((\App\View\Site::get(($v['props'] ?? null), 'variant') === 'footer')) ? ($v['footerLogoUrl'] ?? null) : ($v['logoUrl'] ?? null)); })();
$v['height'] = (function() use (&$v) { return round((\App\View\Site::get(($v['props'] ?? null), 'width') * (\App\View\Site::truth((\App\View\Site::get(($v['props'] ?? null), 'variant') === 'footer')) ? (28 / 80) : (59.568 / 104)))); })();
\App\View\Site::prepare('Components/BrandLogo', $v);
