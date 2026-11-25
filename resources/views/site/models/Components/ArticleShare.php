<?php
// PHP presentation data migrated from resources/js/Components/ArticleShare.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['copyLinkUrl'] = '/site/images/sizdah/blog/copy-link-8a931eb203.svg';
$v['xUrl'] = '/site/images/sizdah/social/x-316f6c4585.svg';
$v['linkedinUrl'] = '/site/images/sizdah/social/linkedin-a0911e96e7.svg';
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['copied'] = false;
$v['xShareUrl'] = ('https://twitter.com/intent/tweet?url=' . \App\View\Site::text(rawurlencode(\App\View\Site::get(($v['props'] ?? null), 'url'))) . '&text=' . \App\View\Site::text(rawurlencode(\App\View\Site::get(($v['props'] ?? null), 'title'))) . '');
$v['linkedinShareUrl'] = ('https://www.linkedin.com/sharing/share-offsite/?url=' . \App\View\Site::text(rawurlencode(\App\View\Site::get(($v['props'] ?? null), 'url'))) . '');
\App\View\Site::prepare('Components/ArticleShare', $v);
