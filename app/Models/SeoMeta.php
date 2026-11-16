<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Per-item, per-locale SEO metadata. See the seo_meta migration and HasSeo.
 */
final class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    /** Fields the admin SEO tab edits (scores are computed, not submitted). */
    public const EDITABLE = [
        'title', 'description', 'focus_keyword', 'secondary_keywords', 'cornerstone',
        'canonical_url', 'robots_index', 'robots_follow', 'robots_advanced', 'breadcrumb_title',
        'og_title', 'og_description', 'og_image', 'og_type',
        'twitter_title', 'twitter_description', 'twitter_image', 'twitter_card',
        'schema_type', 'article_type', 'schema_data', 'custom_schema',
        'exclude_from_sitemap', 'sitemap_priority', 'sitemap_changefreq',
    ];

    protected $guarded = ['id', 'seoable_type', 'seoable_id'];

    protected function casts(): array
    {
        return [
            'secondary_keywords' => 'array',
            'robots_advanced' => 'array',
            'schema_data' => 'array',
            'cornerstone' => 'boolean',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'exclude_from_sitemap' => 'boolean',
            'sitemap_priority' => 'float',
            'seo_score' => 'integer',
            'readability_score' => 'integer',
        ];
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Traffic-light bucket used by the admin column/filter, Yoast thresholds. */
    public static function rating(?int $score): string
    {
        return match (true) {
            $score === null => 'none',
            $score >= 70 => 'good',
            $score >= 40 => 'ok',
            default => 'bad',
        };
    }
}
