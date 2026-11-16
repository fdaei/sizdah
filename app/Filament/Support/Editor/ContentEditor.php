<?php

declare(strict_types=1);

namespace App\Filament\Support\Editor;

use App\Filament\Support\Editor\Blocks\AparatBlock;
use FilamentTiptapEditor\TiptapEditor;

/**
 * The site-wide body editor: WordPress-classic/Gutenberg parity on Tiptap.
 * One factory so every resource gets the same toolbar.
 */
final class ContentEditor
{
    /** Custom blocks — shared with RichContent so the site renders what the editor inserts. */
    public const BLOCKS = [AparatBlock::class];

    public static function make(string $name, string $dir = 'rtl'): TiptapEditor
    {
        return TiptapEditor::make($name)
            ->profile('wordpress')
            ->blocks(self::BLOCKS)
            ->disk('public')
            ->directory('content')
            ->maxContentWidth('full')
            ->extraInputAttributes(['dir' => $dir, 'style' => 'min-height: 24rem']);
    }
}
