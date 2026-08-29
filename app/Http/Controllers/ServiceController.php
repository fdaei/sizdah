<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Service;
use App\Services\ContentTransformer;
use App\Services\SeoBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Services page. Figma 308:4492 — the canonical frame; 1323:7189 and
 * 1626:12562 are dead node ids from the superseded generation of the file.
 *
 * Single page, four alternating sections. No detail routes exist in the
 * design — services are still independently manageable in admin.
 */
final class ServiceController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $page = Page::query()
            ->key('services')
            ->published()
            ->withContent()
            ->first();

        return Inertia::render('Services', [
            'heading' => [
                'eyebrow' => (string) $page?->getTranslation('subtitle'),
                'title' => (string) $page?->getTranslation('title'),
                'description' => (string) $page?->getTranslation('description'),
            ],

            'services' => Service::query()
                ->forDisplay()
                ->get()
                ->map(fn (Service $s): array => ContentTransformer::service($s))
                ->all(),

            'sections' => $page === null
                ? []
                : ContentTransformer::sectionMap($page->sections),

            'seo' => SeoBuilder::forPage($page, $request->url()),
        ]);
    }
}
