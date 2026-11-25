<?php

declare(strict_types=1);

/*
 | BreadcrumbList labels for JSON-LD — App\Services\SeoBuilder::breadcrumbs().
 */
return [

    'pagination_title' => 'صفحهٔ :page',
    'archive_descriptions' => [
        'category' => 'مقالات :name در :site.',
        'tag' => 'مقالات با برچسب :name در :site.',
        'industry' => 'نمونه‌کارهای :name در :site.',
    ],

    'breadcrumbs' => [
        'home' => 'خانه',
        'work' => ['index' => 'نمونه‌کارها'],
        'insights' => ['index' => 'مقالات', 'category' => 'دسته‌بندی'],
        'services' => 'خدمات',
        'about' => 'درباره ما',
        'contact' => 'تماس با ما',
        'legal' => ['privacy' => 'حریم خصوصی', 'terms' => 'شرایط استفاده'],
    ],

];
