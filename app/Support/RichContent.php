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
    public static function render(?string $html): string
    {
        $html = (string) $html;

        if (! str_contains($html, '<tiptap-block')) {
            return $html;
        }

        return (string) tiptap_converter()->blocks(ContentEditor::BLOCKS)->asHTML($html);
    }
}
