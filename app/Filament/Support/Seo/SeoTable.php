<?php

declare(strict_types=1);

namespace App\Filament\Support\Seo;

use App\Models\SeoMeta;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Yoast's list-table SEO / Readability score columns and filters.
 * Reads the default content locale's cached scores from seo_meta.
 */
final class SeoTable
{
    /** Append the score columns + filters to a configured table. */
    public static function apply(Table $table): Table
    {
        return $table->pushColumns(self::columns())->pushFilters(self::filters());
    }

    /** @return list<TextColumn> */
    public static function columns(): array
    {
        return [self::column('seo_score', 'سئو (SEO)'), self::column('readability_score', 'خوانایی (Readability)')];
    }

    /** @return list<SelectFilter> */
    public static function filters(): array
    {
        return [self::filter('seo_score', 'امتیاز سئو (SEO score)'), self::filter('readability_score', 'امتیاز خوانایی (Readability score)')];
    }

    private static function column(string $field, string $label): TextColumn
    {
        return TextColumn::make("seo_{$field}")
            ->label($label)
            ->badge()
            ->state(fn (Model $record): string => (string) (self::row($record)?->{$field} ?? '—'))
            ->color(fn (Model $record): string => SeoSection::color(self::row($record)?->{$field}))
            ->toggleable();
    }

    private static function filter(string $field, string $label): SelectFilter
    {
        return SelectFilter::make("seo_{$field}")
            ->label($label)
            ->options([
                'good' => 'خوب (Good)',
                'ok' => 'متوسط (OK)',
                'bad' => 'ضعیف (Needs improvement)',
                'none' => 'بدون کلمه کلیدی / تحلیل نشده (Not analyzed)',
            ])
            ->query(function (Builder $query, array $data) use ($field): Builder {
                $value = $data['value'] ?? null;
                if (blank($value)) {
                    return $query;
                }
                $locale = config('locales.fallback');
                $in = fn (Builder $q) => $q->where('locale', $locale);

                return match ($value) {
                    'good' => $query->whereHas('seoMeta', fn ($q) => $in($q)->where($field, '>=', 70)),
                    'ok' => $query->whereHas('seoMeta', fn ($q) => $in($q)->whereBetween($field, [40, 69])),
                    'bad' => $query->whereHas('seoMeta', fn ($q) => $in($q)->where($field, '<', 40)),
                    'none' => $query->whereDoesntHave('seoMeta', fn ($q) => $in($q)->whereNotNull($field)),
                    default => $query,
                };
            });
    }

    private static function row(Model $record): ?SeoMeta
    {
        return method_exists($record, 'seoFor') ? $record->seoFor(config('locales.fallback')) : null;
    }
}
