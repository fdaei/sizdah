<?php
// PHP presentation data migrated from resources/js/Components/SocialIcon.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['instagramUrl'] = '/site/images/sizdah/social/instagram-b9f41a9667.svg';
$v['linkedinUrl'] = '/site/images/sizdah/social/linkedin-a0911e96e7.svg';
$v['whatsappUrl'] = '/site/images/sizdah/social/whatsapp-e9a3792e63.svg';
$v['telegramUrl'] = '/site/images/sizdah/social/telegram-4b632d736d.svg';
$v['xUrl'] = '/site/images/sizdah/social/x-316f6c4585.svg';
$v['youtubeUrl'] = '/site/images/sizdah/social/youtube-6d968c5b5e.svg';
$v['glyphs'] = ['instagram' => ($v['instagramUrl'] ?? null), 'linkedin' => ($v['linkedinUrl'] ?? null), 'whatsapp' => ($v['whatsappUrl'] ?? null), 'telegram' => ($v['telegramUrl'] ?? null), 'x' => ($v['xUrl'] ?? null), 'twitter' => ($v['xUrl'] ?? null), 'youtube' => ($v['youtubeUrl'] ?? null)];
$v['glyph'] = (function() use (&$v) { return \App\View\Site::get(($v['glyphs'] ?? null), \App\View\Site::method(\App\View\Site::get(\App\View\Site::get(($v['props'] ?? null), 'link'), 'platform'), 'toLowerCase', [])); })();
\App\View\Site::prepare('Components/SocialIcon', $v);
