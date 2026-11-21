<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Filament\Resources\MediaResource\Pages\CreateMedia;
use App\Filament\Resources\MediaResource\Pages\EditMedia;
use App\Models\Media;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * "انتخاب از کتابخانه" (Choose from library) — added to every image
 * FileUpload in the panel by AppServiceProvider, so an editor can reuse a
 * file from the Media library instead of uploading it again.
 *
 * The chosen file is *copied* into the field's own directory: the record
 * then owns its file, and deleting it from the library can never break the
 * record. The copy is registered in the library with the original's SEO
 * metadata, so its alt keeps working as the fallback.
 */
final class MediaLibraryPicker
{
    public static function register(): void
    {
        FileUpload::configureUsing(fn (FileUpload $upload): FileUpload => $upload->hintAction(self::action()));
    }

    public static function action(): Action
    {
        return Action::make('fromMediaLibrary')
            ->label('انتخاب از کتابخانه (Media library)')
            ->icon('heroicon-o-photo')
            ->visible(fn (FileUpload $component): bool => self::appliesTo($component))
            ->modalHeading('کتابخانه رسانه (Media library)')
            ->modalSubmitActionLabel('استفاده از این تصویر')
            ->form([
                Select::make('media')
                    ->label('تصویر')
                    ->required()
                    ->searchable()
                    ->allowHtml()
                    ->getSearchResultsUsing(fn (string $search): array => self::options($search))
                    ->options(function (): array {
                        Media::sync();

                        return self::options();
                    }),
            ])
            ->action(function (array $data, FileUpload $component): void {
                $media = Media::query()->with('translations')->findOrFail($data['media']);
                $disk = Storage::disk($component->getDiskName());
                $directory = trim((string) $component->getDirectory(), '/');
                $copy = ($directory !== '' ? $directory.'/' : '').Str::ulid().'.'.pathinfo($media->path, PATHINFO_EXTENSION);

                $disk->put($copy, Storage::disk(Media::DISK)->get($media->path));

                if ($component->getDiskName() === Media::DISK) {
                    $clone = Media::register($copy, auth()->id());
                    foreach ($media->translations as $t) {
                        $clone->translations()->updateOrCreate(
                            ['locale' => $t->locale],
                            $t->only(['title', 'alt', 'caption', 'description']),
                        );
                    }
                }

                $state = $component->isMultiple() ? (array) $component->getState() : [];
                $state[(string) Str::uuid()] = $copy;
                $component->state($state);
            });
    }

    /** Image fields only, and never the library's own upload field. */
    private static function appliesTo(FileUpload $component): bool
    {
        $livewire = $component->getLivewire();
        if ($livewire instanceof CreateMedia || $livewire instanceof EditMedia) {
            return false;
        }
        // The TipTap editor's own media modal (field `src`) has its own flow.
        if ($component->getName() === 'src') {
            return false;
        }

        return collect($component->getAcceptedFileTypes() ?? [])
            ->contains(fn (string $type): bool => str_starts_with($type, 'image/'));
    }

    /** @return array<int, string> */
    private static function options(string $search = ''): array
    {
        return Media::query()
            ->where('type', 'image')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->limit(60)
            ->get()
            ->mapWithKeys(fn (Media $m): array => [$m->getKey() => sprintf(
                '<span style="display:flex;gap:.75rem;align-items:center"><img src="%s" alt="" loading="lazy" style="width:56px;height:56px;object-fit:cover;border-radius:6px"><span>%s<br><small style="opacity:.6">%s</small></span></span>',
                e($m->url()),
                e($m->name),
                $m->width ? "{$m->width}×{$m->height}" : '',
            )])
            ->all();
    }
}
