<?php

declare(strict_types=1);

namespace App\Enums;

/** Yoast "Page type" (+ Rank Math extras). Values are schema.org @types. */
enum SchemaPageType: string
{
    case WebPage = 'WebPage';
    case ItemPage = 'ItemPage';
    case AboutPage = 'AboutPage';
    case FAQPage = 'FAQPage';
    case QAPage = 'QAPage';
    case ProfilePage = 'ProfilePage';
    case ContactPage = 'ContactPage';
    case MedicalWebPage = 'MedicalWebPage';
    case CollectionPage = 'CollectionPage';
    case CheckoutPage = 'CheckoutPage';
    case RealEstateListing = 'RealEstateListing';
    case SearchResultsPage = 'SearchResultsPage';
    case Service = 'Service';
    case CreativeWork = 'CreativeWork';

    /** @return array<string, string> */
    public static function options(): array
    {
        $fa = [
            'WebPage' => 'صفحه وب', 'ItemPage' => 'صفحه آیتم', 'AboutPage' => 'درباره ما',
            'FAQPage' => 'پرسش‌های متداول', 'QAPage' => 'پرسش و پاسخ', 'ProfilePage' => 'صفحه پروفایل',
            'ContactPage' => 'تماس با ما', 'MedicalWebPage' => 'صفحه پزشکی', 'CollectionPage' => 'صفحه مجموعه / آرشیو',
            'CheckoutPage' => 'صفحه پرداخت', 'RealEstateListing' => 'آگهی ملک', 'SearchResultsPage' => 'نتایج جستجو',
            'Service' => 'خدمت', 'CreativeWork' => 'نمونه‌کار',
        ];

        return collect(self::cases())->mapWithKeys(fn (self $c): array => [$c->value => "{$fa[$c->value]} ({$c->value})"])->all();
    }
}
