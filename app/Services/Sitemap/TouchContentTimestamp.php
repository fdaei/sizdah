<?php

declare(strict_types=1);

namespace App\Services\Sitemap;

use App\Models\PageSection;
use App\Models\ProjectImage;
use App\Models\SectionItem;
use App\Models\SeoMeta;
use App\Models\Translations\PageSectionTranslation;
use App\Models\Translations\SectionItemTranslation;
use App\Models\Translations\ProjectImageTranslation;
use Illuminate\Database\Eloquent\Model;

/** Keeps a page's lastmod current when an owned block or SEO field changes. */
final class TouchContentTimestamp
{
    public static function touchOwner(Model $model): void
    {
        $owner = match (true) {
            $model instanceof SeoMeta => $model->seoable,
            $model instanceof PageSection => $model->sectionable,
            $model instanceof PageSectionTranslation => PageSection::query()->find($model->page_section_id)?->sectionable,
            $model instanceof SectionItem => $model->section?->sectionable,
            $model instanceof SectionItemTranslation => SectionItem::query()->with('section.sectionable')->find($model->section_item_id)?->section?->sectionable,
            $model instanceof ProjectImage => $model->project,
            $model instanceof ProjectImageTranslation => ProjectImage::query()->with('project')->find($model->project_image_id)?->project,
            default => null,
        };

        $owner?->touch();
    }
}
