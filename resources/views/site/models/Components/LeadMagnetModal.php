<?php
// PHP presentation data migrated from resources/js/Components/LeadMagnetModal.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['userFieldUrl'] = '/site/images/sizdah/contact/field-user-8e6750959b.svg';
$v['envelopeIconUrl'] = '/site/images/sizdah/shared/lead-magnet-envelope-d7f0d7cc29.svg';
$v['successIconUrl'] = '/site/images/sizdah/shared/lead-magnet-success-b664269324.svg';
$v['successScribbleUrl'] = '/site/images/sizdah/shared/lead-magnet-success-scribble-9e2b2d91d5.svg';
$v['tickMarksUrl'] = '/site/images/sizdah/shared/lead-magnet-tick-marks-57b5ff81d5.svg';
$v['emit'] = null;
$v['t'] = fn ($key, $params = []) => \App\View\Site::t($key, $params); $v['locale'] = app()->getLocale();
$v['panel'] = null;
$v['nameInput'] = null;
$v['succeeded'] = false;
$v['page'] = $page;
$v['downloadUrl'] = null;
$v['form'] = array_merge(['name' => '', 'email' => '', 'source' => \App\View\Site::get(($v['props'] ?? null), 'source'), 'post_id' => (\App\View\Site::get(($v['props'] ?? null), 'postId') ?? null), 'website' => ''], ['errors' => [], 'processing' => false, 'recentlySuccessful' => false, 'wasSuccessful' => false]);
\App\View\Site::prepare('Components/LeadMagnetModal', $v);
