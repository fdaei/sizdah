<?php

declare(strict_types=1);

/*
 | BreadcrumbList labels for JSON-LD — App\Services\SeoBuilder::breadcrumbs().
 */
return [

    'pagination_title' => 'Page :page',
    'archive_descriptions' => [
        'category' => ':name articles on :site.',
        'tag' => 'Articles tagged :name on :site.',
        'industry' => ':name projects by :site.',
    ],

    'breadcrumbs' => [
        'home' => 'Home',
        'work' => ['index' => 'Work'],
        'insights' => ['index' => 'Insights'],
        'services' => 'Services',
        'about' => 'About us',
        'contact' => 'Contact us',
        'legal' => ['privacy' => 'Privacy policy', 'terms' => 'Terms of use'],
    ],

];
