<?php
// PHP presentation data migrated from resources/js/Components/SectionHeading.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['eyebrow' => '', 'subtitle' => '', 'layout' => 'split', 'tone' => 'dark', 'gap' => 'sm', 'subtitleWidth' => '', 'splitClass' => '', 'colors' => function() use (&$v) { return []; }], $input);
$v = \App\View\Site::context($input, $page);

\App\View\Site::prepare('Components/SectionHeading', $v);
