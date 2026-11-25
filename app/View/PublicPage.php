<?php

declare(strict_types=1);

namespace App\View;

use App\Support\NavigationBuilder;
use App\Support\SiteSettings;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/** Native HTML response for every public page. PHP renders the full design. */
final class PublicPage implements Responsable
{
    public function __construct(private string $component, private array $props = []) {}

    public static function render(string $component, array $props = []): self
    {
        return new self($component, $props);
    }

    public static function version(): string
    {
        $manifest = public_path('build/manifest.json');

        return 'blade-v1-'.(is_file($manifest) ? md5_file($manifest) : 'dev');
    }

    public function toResponse($request): Response
    {
        $locale = app()->getLocale();
        $config = config('locales.supported.'.$locale);
        $shared = [
            'locale' => ['current' => $locale, 'direction' => $config['direction'], 'font' => $config['font'], 'htmlLang' => $config['html_lang'], 'default' => config('locales.default')],
            'settings' => SiteSettings::forFrontend($locale),
            'navigation' => ['header' => NavigationBuilder::header($locale), 'footer' => NavigationBuilder::footer($locale)],
            // Persian-only: no alternate-language discovery or translation payload.
            'flash' => [
                'success' => $request->hasSession() ? $request->session()->get('success') : null,
                'error' => $request->hasSession() ? $request->session()->get('error') : null,
                'download' => $request->hasSession() ? $request->session()->get('download') : null,
            ],
            'auth' => ['user' => $request->user() ? ['id' => $request->user()->id, 'name' => $request->user()->name, 'email' => $request->user()->email] : null],
            // Kept in view data for PHP integrations; never serialized into HTML.
        ];
        $page = ['component' => $this->component, 'props' => array_merge($shared, $this->props), 'url' => $request->getRequestUri(), 'version' => self::version(), 'encryptHistory' => false, 'clearHistory' => false];
        // An open tab on the previous Vue release upgrades through a full visit.
        if ($request->header('X-Inertia')) {
            return response('', 409, ['X-Inertia-Location' => $request->fullUrl(), 'Cache-Control' => 'no-store']);
        }

        return response()->view('site.app', compact('page'));
    }
}
