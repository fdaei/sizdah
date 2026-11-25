<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SectionType;
use App\Models\Page;
use App\Models\TeamMember;
use App\Services\ContentTransformer;
use App\Services\Seo\SchemaNodes;
use App\Services\SeoBuilder;
use App\View\PublicPage;
use Illuminate\Http\Request;

/**
 * About page. Figma 908:1576 (desktop) / 1557:12225 (mobile).
 *
 * Sections: about hero, story, how-we-think (4 principles), team grid.
 */
final class AboutController extends Controller
{
    public function __invoke(Request $request): PublicPage
    {
        $page = Page::query()
            ->key('about')
            ->published()
            ->withContent()
            ->first();

        $team = $page?->sections->contains('type', SectionType::Team)
            ? TeamMember::query()->forDisplay()->get() : collect();

        return PublicPage::render('About', [
            'sections' => $page === null
                ? []
                : ContentTransformer::sectionMap($page->sections),

            // Generic page-builder blocks (rich text, gallery, features, CTA), in order.
            'blocks' => $page === null ? [] : ContentTransformer::blocks($page->sections),

            'team' => $team
                ->map(fn (TeamMember $m): array => ContentTransformer::teamMember($m))
                ->all(),

            'seo' => SeoBuilder::forPage($page, $request->url(), extraNodes: SchemaNodes::people($team)),
        ]);
    }
}
