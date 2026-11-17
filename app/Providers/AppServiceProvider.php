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
use App\Services\Sitemap\SitemapBuilder;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Support\UiStrings;
use Inertia\Inertia;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // IndexNow: ping when a published post/project (or its translation:
        // slug/content edits) is saved. Never throws — a new record is saved
        // before its translations exist, so it may have no URL yet.
        foreach ([Post::class, Project::class] as $model) {
            $model::saved(fn ($m) => IndexNow::submitFor($m));
        }
        PostTranslation::saved(fn ($t) => IndexNow::submitFor(Post::query()->find($t->post_id)));
        ProjectTranslation::saved(fn ($t) => IndexNow::submitFor(Project::query()->find($t->project_id)));

        // Page cache: any model write that can change public output busts it.
        // Denylist (not allowlist) so a new content model can never serve stale pages.
        Event::listen(['eloquent.saved: *', 'eloquent.deleted: *'], function (string $event): void {
            $model = trim(explode(':', $event, 2)[1]);

            if (! in_array($model, PageCache::IGNORED_MODELS, true)) {
                PageCache::flush();
            }
        });

        // Sitemaps are cached; any content / SEO / settings write invalidates them.
        foreach ([
            Page::class, Post::class, Project::class, Service::class,
            SeoMeta::class, Setting::class,
            PostTranslation::class, ProjectTranslation::class,
            ServiceTranslation::class, PageTranslation::class,
            \App\Models\PostCategory::class, \App\Models\Translations\PostCategoryTranslation::class,
        ] as $model) {
            $model::saved(fn () => SitemapBuilder::bust());
            $model::deleted(fn () => SitemapBuilder::bust());
        }

        // Category chips on /insights are cached per locale.
        foreach ([\App\Models\PostCategory::class, \App\Models\Translations\PostCategoryTranslation::class] as $model) {
            $flush = function (): void {
                foreach (array_keys(config('locales.supported')) as $l) {
                    \Illuminate\Support\Facades\Cache::forget("insights.categories.{$l}");
                }
            };
            $model::saved($flush);
            $model::deleted($flush);
        }

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

        // Vite prefetches lazily-loaded chunks once the page is idle.
        Vite::prefetch(concurrency: 3);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            // Canonical, hreflang, sitemap and OG URLs always use APP_URL's
            // host, never whichever host (e.g. www.) the request came in on.
            URL::forceRootUrl(config('app.url'));
        }

        $this->shareTranslations();
    }

    /**
     * Expose the active locale's PHP translation files to the frontend.
     *
     * Read by resources/js/Composables/useTranslations.ts, so Vue components
     * and Blade resolve identical strings from one source of truth.
     *
     * Cached per-locale in production; re-read on every request in local so
     * editing a lang file shows up without clearing anything.
     */
    private function shareTranslations(): void
    {
        Inertia::share('translations', fn (): array => UiStrings::tree(app()->getLocale()));
    }
}
