<?php

declare(strict_types=1);

namespace App\Services\Redirects;

use App\Models\Post;
use App\Models\Project;
use App\Models\Redirect;
use App\Models\Translations\PostTranslation;
use App\Models\Translations\ProjectTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Yoast/Rank Math "redirect on slug change": when a published item's slug
 * changes, the old URL 301s to the new one. Chains are collapsed (rules that
 * pointed at the old URL now point straight at the new one) and a rule whose
 * source is the new URL is removed so the new URL can never loop.
 *
 * Hooked from translation models' `updating` event (see bootSlugRedirects()).
 */
final class SlugRedirector
{
    /** translation class => [parent relation FK, route name, route param] */
    public const ROUTES = [
        PostTranslation::class => ['post_id', 'insights.show', 'post', Post::class],
        ProjectTranslation::class => ['project_id', 'work.show', 'project', Project::class],
    ];

    public static function handle(Model $translation): void
    {
        $config = self::ROUTES[$translation::class] ?? null;
        if ($config === null || ! $translation->isDirty('slug')) {
            return;
        }

        [$fk, $route, $param, $parentClass] = $config;
        $old = (string) $translation->getOriginal('slug');
        $new = (string) $translation->getAttribute('slug');
        if ($old === '' || $new === '' || $old === $new) {
            return;
        }

        $parent = $parentClass::query()->find($translation->getAttribute($fk));
        if ($parent === null || ! method_exists($parent, 'isPublished') || ! $parent->isPublished()) {
            return; // never-published URLs have no inbound links to keep
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
                'status_code' => 301,
                'is_active' => true,
                'origin' => 'auto',
                'notes' => 'ساخته‌شده خودکار پس از تغییر نامک (Auto: slug changed)',
            ],
        );

        // Bulk update() skips model events — flush the cached map explicitly.
        Cache::forget(Redirect::CACHE_KEY);
    }
}
