<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Setting;
use App\Enums\PublicationStatus;
use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * IndexNow (Bing, Yandex, Seznam, Naver…): tells search engines a URL
 * changed the moment it is published. Enabled in SEO settings; the key file
 * is served by the app at /{key}.txt. Pings only from production and after
 * the response, so admin saves never wait on it.
 */
final class IndexNow
{
    public const ENDPOINT = 'https://api.indexnow.org/indexnow';

    public static function enabled(): bool
    {
        return (bool) SiteSettings::get('seo_indexnow_enabled', null, false) && filled(self::key());
    }

    public static function key(): ?string
    {
        $key = SiteSettings::get('seo_indexnow_key');

        return is_string($key) && preg_match('/^[a-f0-9]{32}$/', $key) ? $key : null;
    }

    public static function ensureKey(): string
    {
        if ($key = self::key()) {
            return $key;
        }
        $key = bin2hex(random_bytes(16));
        Setting::updateOrCreate(['key' => 'seo_indexnow_key'], ['group' => 'seo', 'value' => ['value' => $key], 'is_translatable' => false]);
        SiteSettings::flush();

        return $key;
    }

    /** Ping every locale URL of a published Post/Project/Service that has a slug. */
    public static function submitFor(?Model $model): void
    {
        if ($model === null || ! method_exists($model, 'isPublished') || ! $model->isPublished() || ! self::enabled()) {
            return;
        }

        self::submitModelUrls($model);
    }

    /** Notify crawlers when an existing public URL ceases to be published. */
    public static function submitIfUnpublished(Model $model): void
    {
        if (! method_exists($model, 'isPublished') || $model->isPublished()
            || $model->getRawOriginal('status') !== PublicationStatus::Published->value) {
            return;
        }

        $publishedAt = $model->getRawOriginal('published_at');
        if ($publishedAt !== null && Carbon::parse($publishedAt)->isFuture()) {
            return;
        }

        self::submitModelUrls($model);
    }

    /** Notify crawlers that the previous URL changed after a slug edit. */
    public static function submitPreviousSlug(Model $translation, ?Model $parent, string $routeName, string $parameter): void
    {
        $old = $translation->getRawOriginal('slug');
        $locale = (string) $translation->getAttribute('locale');
        if (! $translation->wasChanged('slug') || blank($old) || ! array_key_exists($locale, config('locales.supported'))
            || $parent === null || ! method_exists($parent, 'isPublished') || ! $parent->isPublished()) {
            return;
        }

        self::submit([route($routeName, ['locale' => $locale, $parameter => $old])]);
    }

    private static function submitModelUrls(Model $model): void
    {
        if (! self::enabled()) {
            return;
        }

        $urls = [];
        foreach (array_keys(config('locales.supported')) as $locale) {
            try {
                if (filled($model->getTranslation('slug', $locale, false))) {
                    $urls[] = $model->url($locale);
                }
            } catch (\Throwable) {
                // no URL for this locale yet
            }
        }

        self::submit($urls);
    }

    /** Queue a ping for these absolute URLs (no-op unless enabled + production). */
    public static function submit(array $urls): void
    {
        $urls = array_values(array_unique(array_filter($urls)));
        if ($urls === [] || ! self::enabled() || ! app()->isProduction()) {
            return;
        }

        $key = (string) self::key();
        $host = (string) parse_url((string) config('app.url'), PHP_URL_HOST);
        $keyLocation = url("/{$key}.txt");

        DB::afterCommit(function () use ($urls, $key, $host, $keyLocation): void {
            app(IndexNowBuffer::class)->add($urls, $key, $host, $keyLocation);
        });
    }
}
