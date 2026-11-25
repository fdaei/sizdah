<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Services\ContentTransformer;
use App\Services\SeoBuilder;
use App\View\PublicPage;
use Illuminate\Http\Request;

/**
 * Projects listing (Figma 1362:7198) and case-study detail (1323:7541).
 */
final class ProjectController extends Controller
{
    public function index(Request $request): PublicPage
    {
        return $this->listing($request);
    }

    public function industry(Request $request, string $locale, Industry $industry): PublicPage
    {
        return $this->listing($request, $industry);
    }

    private function listing(Request $request, ?Industry $industry = null): PublicPage
    {
        $page = Page::query()
            ->key('work')
            ->published()
            ->withContent()
            ->first();

        // Figma 542:871 lists the four *services* — Branding, Marketing
        // Design, Content Production, Social Media support — not industries.
        // The industry stays on the card as its category badge.
        $serviceFilter = $request->string('service')->toString();

        $query = Project::query()->forListing();

        if ($industry !== null) {
            $query->where('industry_id', $industry->id);
        }

        if ($serviceFilter !== '') {
            $locale = app()->getLocale();

            $query->whereHas(
                'services.translations',
                fn ($q) => $q->where('locale', $locale)->where('slug', $serviceFilter),
            );
        }

        return PublicPage::render('Work/Index', [
            'heading' => [
                'eyebrow' => (string) ($industry ? $page?->getTranslation('title') : $page?->getTranslation('subtitle')),
                'title' => (string) ($industry?->getTranslation('name') ?? $page?->getTranslation('title')),
                'description' => $industry ? '' : (string) $page?->getTranslation('description'),
            ],

            'projects' => $query
                ->get()
                ->map(fn (Project $p): array => ContentTransformer::projectSummary($p))
                ->all(),

            // Filter list — Figma "Filters" 542:858
            'filters' => Service::query()
                ->forDisplay()
                ->get()
                ->map(fn (Service $s): array => [
                    'slug' => (string) $s->getTranslation('slug'),
                    'name' => (string) $s->getTranslation('title'),
                ])
                ->all(),

            'activeFilter' => $serviceFilter ?: null,

            'sections' => $page === null
                ? []
                : ContentTransformer::sectionMap($page->sections),

            'seo' => $industry ? SeoBuilder::forIndustry($industry) : SeoBuilder::forPage($page, $request->url()),
        ]);
    }

    public function show(string $locale, Project $project): PublicPage
    {
        // Route-model binding already filtered to published + active locale.
        $project->loadMissing([
            'industry.translations',
            'services.translations',
            'images.translations',
            'sections' => fn ($query) => $query->where('is_visible', true),
            'sections.translations',
            'sections.items' => fn ($query) => $query->where('is_visible', true),
            'sections.items.translations',
        ]);

        // The case study closes on the shared final CTA card (Figma 1419:9333,
        // present in 639:1617). It has no page of its own, so it reuses the
        // one authored on the Work page.
        $workPage = Page::query()->key('work')->published()->withContent()->first();

        return PublicPage::render('Work/Show', [
            'project' => ContentTransformer::projectDetail($project),

            'finalCta' => $workPage === null
                ? null
                : (ContentTransformer::sectionMap($workPage->sections)['final_cta'] ?? null),

            'seo' => SeoBuilder::forProject($project),
        ]);
    }
}
