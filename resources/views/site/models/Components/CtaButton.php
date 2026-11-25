<?php
// PHP presentation data migrated from resources/js/Components/CtaButton.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['href' => null, 'variant' => 'solid', 'size' => 'md', 'withArrow' => false, 'iconPosition' => 'after', 'type' => 'button', 'disabled' => false, 'external' => false], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
$v['arrowUrl'] = '/site/images/sizdah/shared/cta-arrow-cca5b7936f.svg';
$v['page'] = $page;
$v['classes'] = (function() use (&$v) { return ['inline-flex items-center justify-center gap-1 rounded-sm', 'transition-colors duration-200 ease-brand', (\App\View\Site::truth((\App\View\Site::get(($v['props'] ?? null), 'size') === 'lg')) ? 'gap-2 px-8 py-4 text-title-md' : 'px-6 py-3 text-title-sm')]; })();
\App\View\Site::prepare('Components/CtaButton', $v);
