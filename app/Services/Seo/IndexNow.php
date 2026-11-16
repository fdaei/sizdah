<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Setting;
use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    /** Ping every locale URL of a published Post/Project that has a slug. */
    public static function submitFor(?Model $model): void
    {
        if ($model === null || ! method_exists($model, 'isPublished') || ! $model->isPublished() || ! self::enabled()) {
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

        dispatch(function () use ($urls, $key, $host): void {
            try {
                Http::timeout(5)->asJson()->post(self::ENDPOINT, [
                    'host' => $host,
                    'key' => $key,
                    'keyLocation' => url("/{$key}.txt"),
                    'urlList' => $urls,
                ]);
            } catch (\Throwable $e) {
                Log::info('IndexNow ping failed: '.$e->getMessage());
            }
        })->afterResponse();
    }
}
