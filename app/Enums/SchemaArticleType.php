<?php

declare(strict_types=1);

namespace App\Enums;

/** Yoast "Article type". `None` suppresses the Article node. */
enum SchemaArticleType: string
{
    case Article = 'Article';
    case BlogPosting = 'BlogPosting';
    case SocialMediaPosting = 'SocialMediaPosting';
    case NewsArticle = 'NewsArticle';
    case AdvertiserContentArticle = 'AdvertiserContentArticle';
    case SatiricalArticle = 'SatiricalArticle';
    case ScholarlyArticle = 'ScholarlyArticle';
    case TechArticle = 'TechArticle';
    case Report = 'Report';
    case None = 'None';

    /** @return array<string, string> */
    public static function options(): array
    {
        $fa = [
            'Article' => 'مقاله', 'BlogPosting' => 'پست وبلاگ', 'SocialMediaPosting' => 'پست شبکه اجتماعی',
            'NewsArticle' => 'خبر', 'AdvertiserContentArticle' => 'رپورتاژ / محتوای تبلیغاتی',
            'SatiricalArticle' => 'طنز', 'ScholarlyArticle' => 'مقاله علمی', 'TechArticle' => 'مقاله فنی',
            'Report' => 'گزارش', 'None' => 'هیچ‌کدام',
        ];

        return collect(self::cases())->mapWithKeys(fn (self $c): array => [$c->value => "{$fa[$c->value]} ({$c->value})"])->all();
    }
}
