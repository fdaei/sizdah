<?php
// PHP presentation data migrated from resources/js/Components/BlogCard.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['variant' => 'default', 'headingLevel' => 'h3'], $input);
$v = \App\View\Site::context($input, $page);
$v['Link'] = 'a';
\App\View\Site::prepare('Components/BlogCard', $v);
