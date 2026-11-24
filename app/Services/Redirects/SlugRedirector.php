<?php

declare(strict_types=1);

namespace App\Services\Redirects;

use App\Models\Industry;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Project;
use App\Models\Service;
use App\Models\Redirect;
use App\Models\Translations\PostCategoryTranslation;
use App\Models\Translations\PostTagTranslation;
use App\Models\Translations\PostTranslation;
use App\Models\Translations\ProjectTranslation;
use App\Models\Translations\ServiceTranslation;
use App\Models\Translations\IndustryTranslation;
use Illuminate\Database\Eloquent\Model;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Cache;

/**
 * Yoast/Rank Math "redirect on slug change": when a published item's slug
 * changes, the old URL 301s to the new one. Chains are collapsed (rules that
 * pointed at the old URL now point straight at the new one) and a rule whose
 * source is the new URL is removed so the new URL can never loop.
 *
 * Hooked from translation models' `updating` event (see bootSlugRedirects()).
 *
 * SEO settings → Search appearance: `seo_slug_redirect` (on by default) and
 * `seo_slug_redirect_status` (301 by default).
 */
final class SlugRedirector
{
    /** translation class => [parent relation FK, route name, route param] */
    public const ROUTES = [
        PostTranslation::class => ['post_id', 'insights.show', 'post', Post::class],
        ProjectTranslation::class => ['project_id', 'work.show', 'project', Project::class],
        PostCategoryTranslation::class => ['post_category_id', 'insights.category', 'category', PostCategory::class],
        PostTagTranslation::class => ['post_tag_id', 'insights.tag', 'tag', PostTag::class],
        ServiceTranslation::class => ['service_id', 'services.show', 'service', Service::class],
        IndustryTranslation::class => ['industry_id', 'work.industry', 'industry', Industry::class],
    ];

    /** Content types exposed to editors for automatic redirects. */
    public const CONTENT_TYPES = [
        'post' => ['مقاله', PostTranslation::class],
        'project' => ['پروژه', ProjectTranslation::class],
        'service' => ['خدمت', ServiceTranslation::class],
        'post_category' => ['دسته‌بندی مقالات', PostCategoryTranslation::class],
        'post_tag' => ['برچسب مقالات', PostTagTranslation::class],
        'industry' => ['دسته‌بندی پروژه‌ها', IndustryTranslation::class],
    ];

    public static function enabledForItem(string $type, int $id): bool
    {
        $disabled = SiteSettings::get('seo_slug_redirect_disabled_items', null, []);

        return ! is_array($disabled) || ! ($disabled[$type.':'.$id] ?? false);
    }

    public static function typeFor(string $translationClass): ?string
    {
        foreach (self::CONTENT_TYPES as $type => [, $class]) {
            if ($class === $translationClass) {
                return $type;
            }
        }

        return null;
    }

    public const STATUSES = [301, 302, 303, 307, 308];

    public static function enabled(): bool
    {
        return (bool) SiteSettings::get('seo_slug_redirect', null, true);
    }

    public static function status(): int
    {
        $status = (int) SiteSettings::get('seo_slug_redirect_status', null, 301);

        return in_array($status, self::STATUSES, true) ? $status : 301;
    }

    public static function handle(Model $translation): void
    {
        $config = self::ROUTES[$translation::class] ?? null;
        if ($config === null || ! $translation->isDirty('slug') || ! self::enabled()
            || ! self::enabledForItem((string) self::typeFor($translation::class), (int) $translation->getAttribute($config[0]))) {
            return;
        }

        [$fk, $route, $param, $parentClass] = $config;
        $old = (string) $translation->getOriginal('slug');
        $new = (string) $translation->getAttribute('slug');
        if ($old === '' || $new === '' || $old === $new) {
            return;
        }

        $parent = $parentClass::query()->find($translation->getAttribute($fk));
        // Never-published URLs have no inbound links to keep. Categories have
        // no publication state: they are public as soon as they exist.
        if ($parent === null || (method_exists($parent, 'isPublished') && ! $parent->isPublished())) {
            return;
        }

        $locale = (string) $translation->getAttribute('locale');
        $from = Redirect::normalize(route($route, ['locale' => $locale, $param => $old], false));
        $to = Redirect::normalize(route($route, ['locale' => $locale, $param => $new], false));

        // The new URL must stay reachable: drop any rule that would capture it.
        Redirect::query()->where('match_type', 'exact')->where('source_path', $to)->delete();

        // Collapse chains: anything that pointed at the old URL now points at the new one.
        Redirect::query()->where('destination_path', $from)->update(['destination_path' => $to]);

        Redirect::query()->updateOrCreate(
            ['source_path' => $from],
            [
                'destination_path' => $to,
                'match_type' => 'exact',
                'status_code' => self::status(),
                'is_active' => true,
                'origin' => 'auto',
                'notes' => 'ساخته‌شده خودکار پس از تغییر نامک (Auto: slug changed)',
            ],
        );

        // Bulk update() skips model events — flush the cached map explicitly.
        Cache::forget(Redirect::CACHE_KEY);
    }
}
