<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RecordServiceRating;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceRating;
use App\Services\ContentTransformer;
use App\Services\SeoBuilder;
use App\View\PublicPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Services page. Figma 308:4492 — the canonical frame; 1323:7189 and
 * 1626:12562 are dead node ids from the superseded generation of the file.
 *
 * The listing has alternating sections; each service also has a detail route.
 */
final class ServiceController extends Controller
{
    /**
     * `$locale` must be declared: scalar route parameters are passed
     * positionally, so without it `$service` received the {locale} segment
     * ("fa") and every service detail URL 404'd.
     */
    public function show(Request $request, string $locale, string $service): PublicPage
    {
        $record = Service::query()->forDisplay()->whereHas('translations', fn ($query) => $query->where('locale', app()->getLocale())->where('slug', $service)
        )->firstOrFail();
        $title = (string) $record->getTranslation('title');
        $description = (string) $record->getTranslation('description');

        return PublicPage::render('Services/Show', [
            'service' => [
                ...ContentTransformer::service($record),
                'rating' => ServiceRating::summary($record),
            ],
            'seo' => SeoBuilder::forService($record, $request->url()),
        ]);
    }

    public function rate(Request $request, string $locale, string $service, RecordServiceRating $record): RedirectResponse
    {
        $data = $request->validate(['rating' => ['required', 'integer', 'between:1,5']]);

        $model = Service::query()->forDisplay()->whereHas('translations', fn ($query) => $query->where('locale', app()->getLocale())->where('slug', $service)
        )->firstOrFail();

        $stored = $record($model, (int) $data['rating'], $request->ip(), $request->userAgent());

        return back()->with('success', __($stored ? 'forms.rating_thanks' : 'forms.rating_duplicate'));
    }

    public function __invoke(Request $request): PublicPage
    {
        $page = Page::query()
            ->key('services')
            ->published()
            ->withContent()
            ->first();

        return PublicPage::render('Services', [
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
