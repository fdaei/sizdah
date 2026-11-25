<?php
// PHP presentation data migrated from resources/js/Components/ServiceOrbit.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['centerUrl'] = '/site/images/sizdah/home/orbit-center-a8a2efe699.svg';
$v['wordmarkUrl'] = '/site/images/sizdah/home/orbit-wordmark-87b6a3088f.svg';
$v['mobileHeroUrl'] = '/site/images/sizdah/home/hero-mobile-743a2dadb1.svg';
$v['contentUrl'] = '/site/images/sizdah/home/orbit-content-d2dc7c609f.svg';
$v['brandingUrl'] = '/site/images/sizdah/home/orbit-branding-e7d541366f.svg';
$v['marketingUrl'] = '/site/images/sizdah/home/orbit-marketing-4f5707d876.svg';
$v['socialUrl'] = '/site/images/sizdah/home/orbit-social-ddc446c228.svg';
$v['lineTopStartUrl'] = '/site/images/sizdah/home/orbit-line-tl-9d4ef33337.svg';
$v['lineTopEndUrl'] = '/site/images/sizdah/home/orbit-line-tr-e6b404f112.svg';
$v['lineBottomStartUrl'] = '/site/images/sizdah/home/orbit-line-bl-e24cf481bf.svg';
$v['lineBottomEndUrl'] = '/site/images/sizdah/home/orbit-line-br-a89b27e987.svg';
$v['gridDoodleUrl'] = '/site/images/sizdah/home/orbit-grid-ce24b6ed75.svg';
$v['scribbleUrl'] = '/site/images/sizdah/home/orbit-scribble-b0b892fc01.svg';
$v['crossUrl'] = '/site/images/sizdah/home/orbit-cross-19666486ec.svg';
$v['dotsUrl'] = '/site/images/sizdah/home/orbit-dots-9ba8558c05.svg';
$v['SLOTS'] = [['icon' => 'video', 'art' => ($v['contentUrl'] ?? null), 'size' => [240, 246], 'left' => '13.89%', 'top' => '1.97%', 'width' => '16.67%', 'line' => ['src' => ($v['lineTopStartUrl'] ?? null), 'left' => '29.76%', 'top' => '25.86%']], ['icon' => 'palette', 'art' => ($v['brandingUrl'] ?? null), 'size' => [240, 260], 'left' => '69.44%', 'top' => '2.24%', 'width' => '16.67%', 'line' => ['src' => ($v['lineTopEndUrl'] ?? null), 'left' => '58.98%', 'top' => '25.98%']], ['icon' => 'share-2', 'art' => ($v['socialUrl'] ?? null), 'size' => [282, 246], 'left' => '8.89%', 'top' => '48.03%', 'width' => '21.67%', 'line' => ['src' => ($v['lineBottomStartUrl'] ?? null), 'left' => '29.76%', 'top' => '51.45%']], ['icon' => 'megaphone', 'art' => ($v['marketingUrl'] ?? null), 'size' => [240, 246], 'left' => '69.44%', 'top' => '48.42%', 'width' => '16.67%', 'line' => ['src' => ($v['lineBottomEndUrl'] ?? null), 'left' => '57.71%', 'top' => '51.45%']]];
$v['nodes'] = \App\View\Site::orbit($v['SLOTS'], $input['services'] ?? []);
\App\View\Site::prepare('Components/ServiceOrbit', $v);
