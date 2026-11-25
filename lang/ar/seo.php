<?php

declare(strict_types=1);

/*
 | BreadcrumbList labels for JSON-LD — App\Services\SeoBuilder::breadcrumbs().
 */
return [

    'pagination_title' => 'الصفحة :page',
    'archive_descriptions' => [
        'category' => 'مقالات :name في :site.',
        'tag' => 'مقالات بوسم :name في :site.',
        'industry' => 'مشاريع :name لدى :site.',
    ],

    'breadcrumbs' => [
        'home' => 'الرئيسية',
        'work' => ['index' => 'أعمالنا'],
        'insights' => ['index' => 'المقالات'],
        'services' => 'الخدمات',
        'about' => 'من نحن',
        'contact' => 'اتصل بنا',
        'legal' => ['privacy' => 'سياسة الخصوصية', 'terms' => 'شروط الاستخدام'],
    ],

];
