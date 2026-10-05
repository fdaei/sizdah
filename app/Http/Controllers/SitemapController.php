<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * XML sitemaps.
 *
 * /sitemap.xml            index listing one child per locale
 * /sitemap-{locale}.xml   every public URL in that locale, with hreflang
 *                         alternates so search engines pair the translations
 */
final class SitemapController extends Controller
{
    /**
     * robots.txt (SEO-CHK-04). Served by the app rather than public/ so the
     * Sitemap line is an absolute URL on the canonical origin — the protocol
     * does not accept a relative one.
     */
    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Allow: /',
            '',
            'Disallow: /admin',
            'Disallow: /admin/*',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function index(): Response
    {
        $locales = array_keys(config('locales.supported'));

        $xml = view('sitemap.index', [
            'sitemaps' => array_map(
                fn (string $locale): array => [
                    'url' => route('sitemap.locale', ['locale' => $locale]),
                    'lastmod' => now()->toAtomString(),
                ],
                $locales,
            ),
        ])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function locale(string $locale): Response
    {
        if (! array_key_exists($locale, config('locales.supported'))) {
            throw new NotFoundHttpException;
        }

        app()->setLocale($locale);

        $urls = [];

        // Static routes
        foreach ([
            'home' => 1.0,
            'work.index' => 0.9,
            'services' => 0.9,
            'about' => 0.8,
            'insights.index' => 0.8,
            'contact' => 0.7,
            'legal.privacy' => 0.3,
            'legal.terms' => 0.3,
        ] as $name => $priority) {
            $urls[] = [
                'loc' => route($name, ['locale' => $locale]),
                // No lastmod: a fake "now" on every fetch teaches crawlers to
                // ignore the field for the URLs where it is real.
                'lastmod' => null,
                'priority' => $priority,
                'changefreq' => 'weekly',
                'alternates' => $this->alternatesForRoute($name),
            ];
        }

        // Projects
        Project::query()
            ->published()
            ->withTranslations()
            ->get()
            ->each(function (Project $project) use (&$urls, $locale): void {
                $urls[] = [
                    'loc' => $project->url($locale),
                    'lastmod' => $project->updated_at?->toAtomString(),
                    'priority' => 0.7,
                    'changefreq' => 'monthly',
                    'alternates' => $this->alternatesForModel($project, 'work.show', 'project'),
                ];
            });

        // Service detail pages — live, indexable routes that nothing links
        // to yet, so the sitemap is their only discovery path.
        Service::query()
            ->forDisplay()
            ->get()
            ->each(function (Service $service) use (&$urls, $locale): void {
                $slug = (string) $service->getTranslation('slug', $locale);

                if ($slug === '') {
                    return;
                }

                $urls[] = [
                    'loc' => route('services.show', ['locale' => $locale, 'service' => $slug]),
                    'lastmod' => $service->updated_at?->toAtomString(),
                    'priority' => 0.6,
                    'changefreq' => 'monthly',
                    'alternates' => [],
                ];
            });

        // Posts
        Post::query()
            ->published()
            ->withTranslations()
            ->get()
            ->each(function (Post $post) use (&$urls, $locale): void {
                $urls[] = [
                    'loc' => $post->url($locale),
                    'lastmod' => $post->updated_at?->toAtomString(),
                    'priority' => 0.6,
                    'changefreq' => 'monthly',
                    'alternates' => $this->alternatesForModel($post, 'insights.show', 'post'),
                ];
            });

        // x-default alongside the per-language alternates (SEO-CHK-02).
        foreach ($urls as &$url) {
            if ($url['alternates'] === []) {
                continue;
            }

            $default = config("locales.supported.".config('locales.default').'.html_lang');
            $match = collect($url['alternates'])->firstWhere('hreflang', $default);

            $url['alternates'][] = [
                'hreflang' => 'x-default',
                'href' => $match['href'] ?? $url['alternates'][0]['href'],
            ];
        }
        unset($url);

        $xml = view('sitemap.locale', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * @return array<int, array{hreflang: string, href: string}>
     */
    private function alternatesForRoute(string $name): array
    {
        return collect(config('locales.supported'))
            ->map(fn (array $config, string $code): array => [
                'hreflang' => $config['html_lang'],
                'href' => route($name, ['locale' => $code]),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{hreflang: string, href: string}>
     */
    private function alternatesForModel(
        Project|Post $model,
        string $routeName,
        string $parameter,
    ): array {
        return collect(config('locales.supported'))
            ->map(fn (array $config, string $code): array => [
                'hreflang' => $config['html_lang'],
                'href' => route($routeName, [
                    'locale' => $code,
                    $parameter => $model->slugForLocale($code),
                ]),
            ])
            ->values()
            ->all();
    }
}
