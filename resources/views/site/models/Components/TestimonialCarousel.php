<?php
// PHP presentation data migrated from resources/js/Components/TestimonialCarousel.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults([], $input);
$v = \App\View\Site::context($input, $page);
$v['GAP'] = 26;
$v['SWIPE_THRESHOLD'] = 40;
$v['dwell'] = ('' . \App\View\Site::text(\App\View\Site::get(\App\View\Site::get(($v['MOTION'] ?? null), 'testimonials'), 'dwell')) . 's');
$v['viewport'] = null;
$v['track'] = null;
$v['perView'] = 1;
$v['cardWidth'] = 0;
$v['active'] = 0;
$v['paused'] = false;
$v['dragging'] = false;
$v['count'] = (function() use (&$v) { return \App\View\Site::length(\App\View\Site::get(($v['props'] ?? null), 'testimonials')); })();
$v['loops'] = (function() use (&$v) { return (($v['count'] ?? null) > 1); })();
$v['continuous'] = (function() use (&$v) { return (($v['perView'] ?? null) >= 4); })();
$v['offset'] = 0;
$v['lastTime'] = 0;
$v['dwellElapsed'] = 0;
$v['hovered'] = false;
$v['reducedMotion'] = false;
$v['tween'] = null;
$v['drag'] = null;
$v['resize'] = null;
\App\View\Site::prepare('Components/TestimonialCarousel', $v);
