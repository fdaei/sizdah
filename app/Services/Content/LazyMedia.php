<?php

declare(strict_types=1);

namespace App\Services\Content;

use DOMElement;

/**
 * Lazy-loads article media and swaps YouTube iframes for a click-to-load
 * facade (thumbnail + play button) so no third-party JS loads before intent.
 * Images that already declare `loading`/`fetchpriority` (e.g. an LCP hero)
 * are left as authored.
 */
final class LazyMedia
{
    public static function apply(HtmlFragment $fragment, bool $facade, string $playLabel): void
    {
        foreach (iterator_to_array($fragment->xpath->query('//img')) as $img) {
            if ($img instanceof DOMElement && ! $img->hasAttribute('loading') && ! $img->hasAttribute('fetchpriority')) {
                $img->setAttribute('loading', 'lazy');
                if (! $img->hasAttribute('decoding')) {
                    $img->setAttribute('decoding', 'async');
                }
            }
        }

        foreach (iterator_to_array($fragment->xpath->query('//iframe')) as $iframe) {
            if (! $iframe instanceof DOMElement) {
                continue;
            }

            $id = $facade ? self::youtubeId($iframe->getAttribute('src')) : null;

            if ($id === null) {
                if (! $iframe->hasAttribute('loading')) {
                    $iframe->setAttribute('loading', 'lazy');
                }

                continue;
            }

            $title = $iframe->getAttribute('title') ?: 'YouTube';
            $doc = $fragment->doc;

            $wrapper = $doc->createElement('div');
            $wrapper->setAttribute('class', 'video-facade');
            $wrapper->setAttribute('data-embed', "https://www.youtube-nocookie.com/embed/{$id}?autoplay=1");
            $wrapper->setAttribute('data-title', $title);

            $button = $doc->createElement('button');
            $button->setAttribute('type', 'button');
            $button->setAttribute('class', 'video-facade__play');
            $button->setAttribute('aria-label', str_replace(':title', $title, $playLabel));

            $thumb = $doc->createElement('img');
            $thumb->setAttribute('src', "https://i.ytimg.com/vi/{$id}/hqdefault.jpg");
            $thumb->setAttribute('alt', '');
            $thumb->setAttribute('width', '480');
            $thumb->setAttribute('height', '360');
            $thumb->setAttribute('loading', 'lazy');
            $thumb->setAttribute('decoding', 'async');

            $button->appendChild($thumb);
            $wrapper->appendChild($button);
            $iframe->parentNode?->replaceChild($wrapper, $iframe);
        }
    }

    public static function youtubeId(string $src): ?string
    {
        return preg_match('#^(?:https?:)?//(?:www\.)?(?:youtube(?:-nocookie)?\.com/embed/|youtu\.be/)([A-Za-z0-9_-]{11})#', $src, $m)
            ? $m[1]
            : null;
    }
}
