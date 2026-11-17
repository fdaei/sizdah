<?php

declare(strict_types=1);

namespace App\Services\Content;

use DOMElement;

/**
 * TOC Plus equivalent: gives every heading of the chosen levels a unique,
 * Persian-safe id and returns the outline. Existing ids are respected.
 */
final class TableOfContents
{
    /**
     * @param  list<string>  $levels  e.g. ['h2', 'h3']
     * @return list<array{id: string, text: string, level: int}>
     */
    public static function apply(HtmlFragment $fragment, array $levels): array
    {
        $used = [];
        foreach ($fragment->xpath->query('//*[@id]') as $el) {
            if ($el instanceof DOMElement) {
                $used[$el->getAttribute('id')] = true;
            }
        }

        $query = implode(' | ', array_map(fn (string $h): string => "//{$h}", $levels));
        $items = [];

        foreach ($fragment->xpath->query($query) as $heading) {
            if (! $heading instanceof DOMElement) {
                continue;
            }

            $text = trim((string) preg_replace('/\s+/u', ' ', $heading->textContent));
            if ($text === '') {
                continue;
            }

            $id = $heading->getAttribute('id');
            if ($id === '') {
                $id = self::uniqueId(self::slug($text), $used);
                $heading->setAttribute('id', $id);
                // Lets the TOC move keyboard focus to the section it scrolled to.
                if (! $heading->hasAttribute('tabindex')) {
                    $heading->setAttribute('tabindex', '-1');
                }
            }

            $items[] = ['id' => $id, 'text' => $text, 'level' => (int) substr($heading->nodeName, 1)];
        }

        return $items;
    }

    /** Keeps Persian/Arabic letters; spaces and ZWNJ become hyphens. */
    public static function slug(string $text): string
    {
        $text = mb_strtolower(str_replace(["\u{200C}", 'ي', 'ك'], [' ', 'ی', 'ک'], $text));
        $text = (string) preg_replace('/[\x{064B}-\x{065F}\x{0670}]/u', '', $text); // diacritics
        $text = (string) preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        $text = trim($text, '-');

        return $text === '' ? 'section' : mb_substr($text, 0, 80);
    }

    /** @param array<string, true> $used */
    private static function uniqueId(string $base, array &$used): string
    {
        $id = $base;
        for ($n = 2; isset($used[$id]); $n++) {
            $id = "{$base}-{$n}";
        }
        $used[$id] = true;

        return $id;
    }
}
