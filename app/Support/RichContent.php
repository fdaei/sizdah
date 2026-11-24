<?php

declare(strict_types=1);

namespace App\Support;

use App\Filament\Support\Editor\ContentEditor;

/**
 * Admin-authored rich text -> HTML for the public site.
 *
 * The Tiptap editor stores custom blocks (e.g. Aparat) as <tiptap-block>
 * placeholders; only then is the content run through the package converter,
 * so legacy RichEditor HTML is passed through byte-for-byte.
 */
final class RichContent
{
    /** Recover plain text from documents saved by older versions of the editor. */
    public static function plain(?string $value): string
    {
        $value = (string) $value;
        $document = json_decode($value, true);
        if (! is_array($document) || ($document['type'] ?? null) !== 'doc' || ! is_array($document['content'] ?? null)) {
            return $value;
        }

        $text = static function (array $node) use (&$text): string {
            if (($node['type'] ?? null) === 'text') {
                return is_string($node['text'] ?? null) ? $node['text'] : '';
            }
            if (($node['type'] ?? null) === 'hardBreak') {
                return "\n";
            }
            $children = array_filter($node['content'] ?? [], 'is_array');
            $separator = in_array($node['type'] ?? '', ['doc', 'bulletList', 'orderedList'], true) ? "\n" : '';

            return implode($separator, array_map($text, $children));
        };

        return $text($document);
    }

    public static function render(?string $html): string
    {
        $html = (string) $html;

        if (! str_contains($html, '<tiptap-block')) {
            return $html;
        }

        return (string) tiptap_converter()->blocks(ContentEditor::BLOCKS)->asHTML($html);
    }
}
