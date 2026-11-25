<?php
// PHP presentation data migrated from resources/js/Components/SmartText.vue; no JavaScript runs on the server.
$input = \App\View\Site::defaults(['as' => 'span', 'weight' => 400, 'withDir' => true], $input);
$v = \App\View\Site::context($input, $page);
$v['isMixed'] = (function() use (&$v) { return (\App\View\Site::get(($v['contentType'] ?? null), 'value') === 'mixed'); })();
\App\View\Site::prepare('Components/SmartText', $v);
