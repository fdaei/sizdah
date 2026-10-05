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
    /**
     * `$locale` must be declared: scalar route parameters are passed
     * positionally, so without it `$service` received the {locale} segment
     * ("fa") and every service detail URL 404'd.
     */
    public function show(Request $request, string $locale, string $service): Response
    {
        $record = Service::query()->forDisplay()->whereHas('translations', fn ($query) =>
            $query->where('locale', app()->getLocale())->where('slug', $service)
        )->firstOrFail();
        $title = (string) $record->getTranslation('title');
        $description = (string) $record->getTranslation('description');

        return Inertia::render('Services/Show', [
            'service' => ['title' => $title, 'description' => $description, 'features' => $record->getTranslation('features') ?? []],
            'seo' => SeoBuilder::forService($record, $request->url()),
        ]);
    }

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
