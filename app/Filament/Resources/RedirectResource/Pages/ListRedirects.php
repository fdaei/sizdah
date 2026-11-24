<?php

declare(strict_types=1);

namespace App\Filament\Resources\RedirectResource\Pages;

use App\Filament\Resources\RedirectResource;
use App\Filament\Pages\ManageSeoSettings;
use App\Models\Redirect;
use App\Services\Redirects\RedirectResolver;
use App\Services\Redirects\SlugRedirector;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ListRedirects extends ListRecords
{
    protected static string $resource = RedirectResource::class;

    /** Accepted header names (ours, Rank Math, "Redirection" plugin, Yoast Premium). */
    private const COLUMNS = [
        'source' => ['source', 'source_path', 'from', 'url', 'old', 'origin'],
        'destination' => ['destination', 'destination_path', 'to', 'target', 'action_data', 'new'],
        'status' => ['status', 'status_code', 'code', 'type', 'action_code', 'header_code'],
        'match' => ['match_type', 'match', 'regex', 'type_match', 'sources_comparison'],
        'active' => ['active', 'is_active', 'enabled', 'status_active'],
    ];

    public function getSubheading(): ?string
    {
        $state = SlugRedirector::enabled()
            ? 'فعال، با کد '.SlugRedirector::status()
            : 'غیرفعال';

        return "ریدایرکت خودکار هنگام تغییر نامک: {$state}. پس از تغییر آدرس محتوای منتشرشده، آدرس قدیمی در زبانه «خودکار» ثبت می‌شود. این قابلیت برای همه خطاهای ۴۰۴ ریدایرکت ایجاد نمی‌کند.";
    }

    /** @return array<string, Tab> */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('همه'),
            'automatic' => Tab::make('خودکار (تغییر نامک)')
                ->badge(fn (): int => Redirect::query()->where('origin', 'auto')->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('origin', 'auto')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('ریدایرکت جدید (Add new)'),

            Actions\Action::make('redirectSettings')
                ->label('تنظیمات ریدایرکت خودکار')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->url(fn (): string => ManageSeoSettings::getUrl()),

            Actions\Action::make('export')
                ->label('خروجی CSV (Export)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn (): StreamedResponse => response()->streamDownload(function (): void {
                    $out = fopen('php://output', 'w');
                    fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows Persian correctly
                    fputcsv($out, ['source', 'destination', 'status', 'match_type', 'active', 'hits', 'notes']);
                    Redirect::query()->orderBy('id')->each(function (Redirect $r) use ($out): void {
                        fputcsv($out, [$r->source_path, $r->destination_path, $r->status_code, $r->match_type, $r->is_active ? 1 : 0, $r->hits, $r->notes]);
                    });
                    fclose($out);
                }, 'redirects-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8'])),

            Actions\Action::make('import')
                ->label('درون‌ریزی CSV (Import)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->modalDescription('ستون‌ها: source, destination, status, match_type, active — خروجی CSV افزونه‌های Rank Math / Redirection / Yoast هم معمولاً بدون تغییر قبول می‌شود. ردیف‌های تکراری به‌روزرسانی می‌شوند.')
                ->form([
                    FileUpload::make('file')
                        ->label('فایل CSV')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                        ->maxSize(5 * 1024)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $path = Storage::disk('local')->path($data['file']);
                    [$created, $updated, $skipped] = self::import($path);
                    Storage::disk('local')->delete($data['file']);

                    Notification::make()
                        ->title("درون‌ریزی انجام شد: {$created} جدید، {$updated} به‌روزرسانی، {$skipped} رد شد")
                        ->success()
                        ->send();
                }),
        ];
    }

    /**
     * @return array{0:int,1:int,2:int} created, updated, skipped
     */
    public static function import(string $path): array
    {
        $fh = fopen($path, 'r');
        if ($fh === false) {
            return [0, 0, 0];
        }

        $header = fgetcsv($fh) ?: [];
        $header = array_map(fn ($h): string => strtolower(trim((string) preg_replace('/^\xEF\xBB\xBF/', '', (string) $h))), $header);
        $index = [];
        foreach (self::COLUMNS as $field => $aliases) {
            foreach ($aliases as $alias) {
                $i = array_search($alias, $header, true);
                if ($i !== false) {
                    $index[$field] = $i;
                    break;
                }
            }
        }
        // Headerless file: assume source,destination[,status].
        if (! isset($index['source'])) {
            rewind($fh);
            $index = ['source' => 0, 'destination' => 1, 'status' => 2];
        }

        $created = $updated = $skipped = 0;

        DB::transaction(function () use ($fh, $index, &$created, &$updated, &$skipped): void {
            while (($row = fgetcsv($fh)) !== false) {
                $source = trim((string) ($row[$index['source']] ?? ''));
                if ($source === '') {
                    $skipped++;

                    continue;
                }

                $status = (int) preg_replace('/\D/', '', (string) ($row[$index['status'] ?? -1] ?? '')) ?: 301;
                if (! array_key_exists($status, Redirect::STATUSES)) {
                    $status = 301;
                }
                $matchRaw = strtolower((string) ($row[$index['match'] ?? -1] ?? ''));
                $match = in_array($matchRaw, ['regex', '1', 'true', 'yes'], true) ? 'regex' : 'exact';
                $destination = trim((string) ($row[$index['destination'] ?? -1] ?? ''));
                $activeRaw = $row[$index['active'] ?? -1] ?? '1';
                $active = ! in_array(strtolower((string) $activeRaw), ['0', 'false', 'no', 'inactive', 'disabled'], true);

                $key = $match === 'regex' ? $source : Redirect::normalize($source);
                if ($match === 'regex' && RedirectResolver::delimit($key) === null
                    || ($destination === '' && ! in_array($status, Redirect::GONE, true))
                    || ($match === 'exact' && ($key === '' || str_starts_with($key, 'admin')))
                    || ($match === 'exact' && ! in_array($status, Redirect::GONE, true)
                        && app(RedirectResolver::class)->finalDestination($key, $destination) === null)) {
                    $skipped++;

                    continue;
                }

                $existing = Redirect::query()->where('source_path', $key)->first();
                $attrs = [
                    'destination_path' => $destination ?: null,
                    'status_code' => $status,
                    'match_type' => $match,
                    'is_active' => $active,
                ];

                if ($existing) {
                    $existing->update($attrs);
                    $updated++;
                } else {
                    Redirect::query()->create(['source_path' => $key, 'origin' => 'import'] + $attrs);
                    $created++;
                }
            }
        });

        fclose($fh);

        return [$created, $updated, $skipped];
    }
}
