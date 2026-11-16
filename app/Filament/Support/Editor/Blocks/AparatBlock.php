<?php

declare(strict_types=1);

namespace App\Filament\Support\Editor\Blocks;

use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\TiptapBlock;

/**
 * Aparat video embed (the plugin's oEmbed tool only knows YouTube/Vimeo).
 * Accepts a watch URL (aparat.com/v/HASH) or a bare hash.
 */
final class AparatBlock extends TiptapBlock
{
    public string $preview = 'tiptap.blocks.aparat';

    public string $rendered = 'tiptap.blocks.aparat';

    public ?string $identifier = 'aparat';

    public ?string $label = 'ویدیو آپارات (Aparat video)';

    public string $width = 'lg';

    public ?string $icon = 'heroicon-o-play-circle';

    public function getFormSchema(): array
    {
        return [
            TextInput::make('url')
                ->label('آدرس ویدیو آپارات (Aparat URL)')
                ->placeholder('https://www.aparat.com/v/abc123')
                ->extraInputAttributes(['dir' => 'ltr'])
                ->required()
                ->rule(fn () => function (string $attribute, mixed $value, \Closure $fail): void {
                    if (self::hash((string) $value) === null) {
                        $fail('آدرس آپارات معتبر نیست.');
                    }
                }),
            TextInput::make('title')
                ->label('عنوان ویدیو (Title)')
                ->helperText('برای دسترس‌پذیری و سئو.'),
        ];
    }

    public static function hash(string $value): ?string
    {
        $value = trim($value);
        if (preg_match('~aparat\.com/(?:v|video/video/embed/videohash)/([A-Za-z0-9]+)~', $value, $m)) {
            return $m[1];
        }

        return preg_match('~^[A-Za-z0-9]{3,20}$~', $value) ? $value : null;
    }
}
