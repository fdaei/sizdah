<?php
// PHP presentation data migrated from resources/js/Components/LeadMagnetBanner.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['size' => 'sm', 'postId' => null], $input);
$v = \App\View\Site::context($input, $page);
$v['doodleUrl'] = '/site/images/sizdah/shared/checklist-doodle-051cf9f909.svg';
$v['articleAccentUrl'] = '/site/images/sizdah/blog/lead-magnet-accent-4d2808228d.svg';
$v['leadMagnetDotsUrl'] = '/site/images/sizdah/home/lead-magnet-dots-22e4905f18.svg';
$v['leadMagnetGridUrl'] = '/site/images/sizdah/home/lead-magnet-grid-83c48c59ca.svg';
$v['leadMagnetScribbleUrl'] = '/site/images/sizdah/home/lead-magnet-scribble-8a1c7de463.svg';
$v['modalOpen'] = false;
\App\View\Site::prepare('Components/LeadMagnetBanner', $v);
