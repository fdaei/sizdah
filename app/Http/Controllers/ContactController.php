<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactSubmissionRequest;
use App\Models\Page;
use App\Models\Service;
use App\Services\ContentTransformer;
use App\Services\SeoBuilder;
use App\Services\SubmissionHandler;
use App\View\PublicPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Contact page. Figma 1363:8934 (desktop) / 1494:9544 (mobile).
 */
final class ContactController extends Controller
{
    public function __construct(
        private readonly SubmissionHandler $handler,
    ) {}

    public function create(Request $request): PublicPage
    {
        $page = Page::query()
            ->key('contact')
            ->published()
            ->withContent()
            ->first();

        return PublicPage::render('Contact', [
            'sections' => $page === null
                ? []
                : ContentTransformer::sectionMap($page->sections),

            // Generic page-builder blocks (rich text, gallery, features, CTA), in order.
            'blocks' => $page === null ? [] : ContentTransformer::blocks($page->sections),

            'heading' => [
                'eyebrow' => (string) $page?->getTranslation('subtitle'),
                'title' => (string) $page?->getTranslation('title'),
                'description' => (string) $page?->getTranslation('description'),
            ],

            // Options for the services multi-select.
            'services' => Service::query()
                ->forDisplay()
                ->get()
                ->map(fn (Service $s): array => [
                    'id' => $s->id,
                    'title' => (string) $s->getTranslation('title'),
                ])
                ->all(),

            'seo' => SeoBuilder::forPage($page, $request->url()),
        ]);
    }

    public function store(ContactSubmissionRequest $request): RedirectResponse|JsonResponse
    {
        $this->handler->handleContact($request);

        if ($request->expectsJson()) {
            return response()->json(['success' => __('forms.contact.success')]);
        }

        return back()->with('success', __('forms.contact.success'));
    }
}
