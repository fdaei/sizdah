<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Translations\PageTranslation;
use App\Models\Translations\PostTranslation;
use App\Models\Translations\ProjectTranslation;
use App\Models\Translations\ServiceTranslation;
use App\Services\Performance\PageCache;
use App\Services\Seo\IndexNow;
use App\Services\Seo\IndexNowBuffer;
use App\Services\Sitemap\SitemapBuilder;
use App\Services\Sitemap\TouchContentTimestamp;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Support\UiStrings;
use App\Support\TransactionalCache;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(IndexNowBuffer::class);
    }

    public function boot(): void
    {
        // IndexNow: ping when a published post/project/service (or its translation:
        // slug/content edits) is saved. Never throws — a new record is saved
        // before its translations exist, so it may have no URL yet.
        foreach ([Post::class, Project::class, Service::class] as $model) {
            $model::saved(fn ($m) => IndexNow::submitFor($m));
            $model::updated(fn ($m) => IndexNow::submitIfUnpublished($m));
            $model::deleting(function ($m): void {
                if ($m->isPublished() && IndexNow::enabled()) {
                    // A force delete cascades translations before the deleted event.
                    $m->loadMissing('translations');
                }
            });
            $model::deleted(fn ($m) => IndexNow::submitFor($m));
        }
        PostTranslation::saved(fn ($t) => IndexNow::submitFor(Post::query()->find($t->post_id)));
        ProjectTranslation::saved(fn ($t) => IndexNow::submitFor(Project::query()->find($t->project_id)));
        ServiceTranslation::saved(fn ($t) => IndexNow::submitFor(Service::query()->find($t->service_id)));
        foreach ([
            PostTranslation::class => [Post::class, 'post_id', 'insights.show', 'post'],
            ProjectTranslation::class => [Project::class, 'project_id', 'work.show', 'project'],
            ServiceTranslation::class => [Service::class, 'service_id', 'services.show', 'service'],
        ] as $translation => [$parent, $foreignKey, $route, $parameter]) {
            $translation::updated(function ($record) use ($parent, $foreignKey, $route, $parameter): void {
                if ($record->wasChanged('slug') && IndexNow::enabled()) {
                    IndexNow::submitPreviousSlug($record, $parent::query()->find($record->{$foreignKey}), $route, $parameter);
                }
            });
        }

        // Last modification must include translated text, SEO, and authored blocks.
        foreach ([
            PageTranslation::class => [Page::class, 'page_id'],
            PostTranslation::class => [Post::class, 'post_id'],
            ProjectTranslation::class => [Project::class, 'project_id'],
            ServiceTranslation::class => [Service::class, 'service_id'],
            \App\Models\Translations\PostCategoryTranslation::class => [\App\Models\PostCategory::class, 'post_category_id'],
            \App\Models\Translations\PostTagTranslation::class => [\App\Models\PostTag::class, 'post_tag_id'],
            \App\Models\Translations\IndustryTranslation::class => [\App\Models\Industry::class, 'industry_id'],
        ] as $translation => [$parent, $foreignKey]) {
            $touch = fn ($record) => $parent::query()->find($record->{$foreignKey})?->touch();
            $translation::saved(function ($record) use ($touch): void {
                if ($record->wasRecentlyCreated || $record->wasChanged()) {
                    $touch($record);
                }
            });
            $translation::deleted(function ($record) use ($touch): void {
                $touch($record);
            });
        }
        foreach ([
            SeoMeta::class, \App\Models\PageSection::class, \App\Models\SectionItem::class,
            \App\Models\ProjectImage::class, \App\Models\Translations\PageSectionTranslation::class,
            \App\Models\Translations\SectionItemTranslation::class, \App\Models\Translations\ProjectImageTranslation::class,
        ] as $child) {
            $child::saved(function ($record): void {
                if ($record->wasRecentlyCreated || $record->wasChanged()) {
                    TouchContentTimestamp::touchOwner($record);
                }
            });
            $child::deleted(fn ($record) => TouchContentTimestamp::touchOwner($record));
        }

        // Page cache: any model write that can change public output busts it.
        // Denylist (not allowlist) so a new content model can never serve stale pages.
        Event::listen(['eloquent.saved: *', 'eloquent.deleted: *'], function (string $event, array $payload): void {
            $model = trim(explode(':', $event, 2)[1]);

            if (! in_array($model, PageCache::IGNORED_MODELS, true)) {
                TransactionalCache::invalidate($payload[0], PageCache::flush(...));
            }
        });

        // Sitemaps are cached; any content / SEO / settings write invalidates them.
        foreach ([
            Page::class, Post::class, Project::class, Service::class,
            SeoMeta::class, Setting::class,
            PostTranslation::class, ProjectTranslation::class,
            ServiceTranslation::class, PageTranslation::class,
            \App\Models\PostCategory::class, \App\Models\Translations\PostCategoryTranslation::class,
            \App\Models\PostTag::class, \App\Models\Translations\PostTagTranslation::class,
            \App\Models\Industry::class, \App\Models\Translations\IndustryTranslation::class,
            \App\Models\ProjectImage::class,
        ] as $model) {
            $model::saved(fn ($record) => TransactionalCache::invalidate($record, SitemapBuilder::bust(...)));
            $model::deleted(fn ($record) => TransactionalCache::invalidate($record, SitemapBuilder::bust(...)));
        }

        // Category chips on /insights are cached per locale.
        foreach ([\App\Models\PostCategory::class, \App\Models\Translations\PostCategoryTranslation::class] as $model) {
            $flush = function (): void {
                foreach (array_keys(config('locales.supported')) as $l) {
                    \Illuminate\Support\Facades\Cache::forget("insights.categories.v2.{$l}");
                }
            };
            $model::saved($flush);
            $model::deleted($flush);
        }

        \App\Filament\Support\WebpUploads::register();

        // "Choose from library" on every image upload field.
        \App\Filament\Support\MediaLibraryPicker::register();

        Field::configureUsing(
            fn (Field $field): Field => $field->translateLabel(),
        );
        Section::configureUsing(function (Section $section): void {
            $heading = $section->getHeading();

            if (is_string($heading)) {
                $section->heading(fn (): string => __($heading));
            }
        });
        Column::configureUsing(
            fn (Column $column): Column => $column->translateLabel(),
        );
        BaseFilter::configureUsing(
            fn (BaseFilter $filter): BaseFilter => $filter->translateLabel(),
        );

        // Remove Livewire's default 12 MB temporary-upload ceiling.
        config()->set('livewire.temporary_file_upload.rules', ['required', 'file']);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            // Canonical, hreflang, sitemap and OG URLs always use APP_URL's
            // host, never whichever host (e.g. www.) the request came in on.
            URL::forceRootUrl(config('app.url'));
        }
    }

}
