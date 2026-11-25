<?php
// PHP presentation data migrated from resources/js/Components/SparkMark.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['width' => 20, 'height' => 25, 'title' => ''], $input);
$v = \App\View\Site::context($input, $page);
$v['sparkUrl'] = '/site/images/sizdah/shared/spark-0b0c7de551.svg';
\App\View\Site::prepare('Components/SparkMark', $v);
