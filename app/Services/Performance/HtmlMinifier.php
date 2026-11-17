<?php

declare(strict_types=1);

namespace App\Services\Performance;

/**
 * Conservative HTML minifier: removes comments (except IE conditionals and
 * Vue/SSR hydration markers `<!--[-->`, `<!--]-->`, `<!---->`) and collapses
 * whitespace between tags. `pre`, `textarea`, `script` and `style` blocks are
 * protected verbatim, so inline JSON-LD, analytics and the Inertia page
 * payload are never altered.
 */
final class HtmlMinifier
{
    public static function minify(string $html): string
    {
        $protected = [];

        $html = (string) preg_replace_callback(
            '#<(pre|textarea|script|style)\b[^>]*>.*?</\1>#is',
            function (array $m) use (&$protected): string {
                $protected[] = $m[0];

                return '%%MINIFY_KEEP_'.(count($protected) - 1).'%%';
            },
            $html,
        );

        // Comments, but keep conditional comments and Vue SSR fragment anchors.
        $html = (string) preg_replace('#<!--(?!\[if|\[|\]|-->|\s*\]).*?-->#s', '', $html);

        // Whitespace runs between tags → nothing; elsewhere → one space.
        $html = (string) preg_replace('#>\s+<#', '> <', $html);
        $html = (string) preg_replace('#\s{2,}#', ' ', $html);

        return (string) preg_replace_callback(
            '#%%MINIFY_KEEP_(\d+)%%#',
            fn (array $m): string => $protected[(int) $m[1]],
            trim($html),
        );
    }
}
