<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Sitemap\SitemapBuilder;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * XML sitemaps.
 *
 * /sitemap.xml                 index: one child per content type and page
 * /sitemap-{type}-{n}.xml      up to 1000 URLs (every live locale, hreflang
 *                              alternates + x-default, lastmod, images)
 * /sitemap-{locale}.xml        legacy, 301 to the index
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
        // Editable in "SEO settings"; any Sitemap: lines are dropped and the
        // absolute sitemap URL is always appended (SEO checklist item 7).
        $custom = trim((string) SiteSettings::get('seo_robots_txt', null, ''));
        $rules = $custom !== ''
            ? preg_replace('/^\s*sitemap\s*:.*$\R?/im', '', str_replace("\r\n", "\n", $custom))
            : implode("\n", ['User-agent: *', 'Allow: /', '', 'Disallow: /admin', 'Disallow: /admin/*']);

        $body = rtrim((string) $rules)."\n\nSitemap: ".route('sitemap')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function index(SitemapBuilder $builder): Response
    {
        $xml = view('sitemap.index', ['sitemaps' => $builder->index()])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** Human-readable rendering of the XML in browsers (search engines ignore it). */
    public function stylesheet(): Response
    {
        return response(view('sitemap.xsl')->render(), 200, ['Content-Type' => 'text/xsl; charset=UTF-8']);
    }

    public function type(SitemapBuilder $builder, string $type, int $page): Response
    {
        $urls = $builder->page($type, $page);
        if ($urls === null) {
            throw new NotFoundHttpException;
        }

        $xml = view('sitemap.urlset', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Former per-locale sitemap (may still be registered in Search Console):
     * permanently moved to the index.
     */
    public function legacyLocale(): RedirectResponse
    {
        return redirect()->route('sitemap', status: 301);
    }
}
