<?php
// PHP presentation data migrated from resources/js/Components/ClientLogo.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['parsUrl'] = '/site/images/sizdah/clients/pars-42331a28d7.svg';
$v['allDigitallUrl'] = '/site/images/sizdah/clients/alldigitall-c56dd76a4b.svg';
$v['baghcheUrl'] = '/site/images/sizdah/clients/baghche-05a48fccb2.svg';
$v['nooraUrl'] = '/site/images/sizdah/clients/noora-3e756491c0.svg';
$v['vanakUrl'] = '/site/images/sizdah/clients/vanak-51acea787d.svg';
$v['tavakoliUrl'] = '/site/images/sizdah/clients/tavakoli-2e4e2c69c7.svg';
$v['EXPORTS'] = ['کلینیک پارس' => ($v['parsUrl'] ?? null), 'allDigitall.ir' => ($v['allDigitallUrl'] ?? null), 'باغچه' => ($v['baghcheUrl'] ?? null), 'نورا' => ($v['nooraUrl'] ?? null), 'ونک' => ($v['vanakUrl'] ?? null), 'توکلی' => ($v['tavakoliUrl'] ?? null)];
$v['failed'] = [];
$v['source'] = (function() use (&$v) { return (\App\View\Site::truth(\App\View\Site::method([\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'client'), 'logo'), \App\View\Site::get(($v['EXPORTS'] ?? null), \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'client'), 'name'))], 'find', [function($l_url = null) use (&$v) { return (\App\View\Site::truth($l_url) ? (!\App\View\Site::truth(\App\View\Site::method(($v['failed'] ?? null), 'includes', [$l_url]))) : null); }])) ? \App\View\Site::method([\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'client'), 'logo'), \App\View\Site::get(($v['EXPORTS'] ?? null), \App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'client'), 'name'))], 'find', [function($l_url = null) use (&$v) { return (\App\View\Site::truth($l_url) ? (!\App\View\Site::truth(\App\View\Site::method(($v['failed'] ?? null), 'includes', [$l_url]))) : null); }]) : null); })();
\App\View\Site::prepare('Components/ClientLogo', $v);
