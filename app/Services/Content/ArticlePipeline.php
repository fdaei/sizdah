<?php

declare(strict_types=1);

namespace App\Services\Content;

use App\Models\InternalLink;
use App\Support\ContentTools;
use App\Support\Performance;
use Illuminate\Support\Facades\Cache;

/**
 * Render-time transforms for article bodies: heading ids + TOC, auto internal
 * links, lazy media / video facades. Output is cached by a hash of every
 * input, so editing the post, a link rule or a setting naturally misses.
 * Stored content in the database is never modified.
 */
final class ArticlePipeline
{
    /**
     * @return array{html: string, toc: list<array{id: string, text: string, level: int}>}
     */
    public static function render(string $html, string $locale, ?string $selfUrl, ?bool $postTocEnabled): array
    {
        if (trim($html) === '') {
            return ['html' => $html, 'toc' => []];
        }

        $tocOn = $postTocEnabled ?? (bool) ContentTools::get('toc_enabled');
        $rules = ContentTools::get('autolink_enabled') ? InternalLink::rulesFor($locale) : [];

        $key = 'article_pipeline.'.sha1(implode('|', [
            $html, $locale, (string) $selfUrl, $tocOn ? '1' : '0',
            ContentTools::fingerprint(), serialize($rules), __('common.play_video'),
        ]));

        return Cache::remember($key, 86400 * 7, function () use ($html, $selfUrl, $tocOn, $rules): array {
            $fragment = new HtmlFragment($html);

            // Ids are always assigned (deep links work even with the box hidden).
            $toc = TableOfContents::apply($fragment, ContentTools::tocLevels());

            AutoLinker::apply($fragment, $rules, $selfUrl, (int) ContentTools::get('autolink_max_total'));

            if (Performance::lazyMedia()) {
                LazyMedia::apply($fragment, Performance::videoFacade(), (string) __('common.play_video'));
            }

            $show = $tocOn && count($toc) >= max(1, (int) ContentTools::get('toc_min_headings'));

            return ['html' => $fragment->html(), 'toc' => $show ? $toc : []];
        });
    }
}
