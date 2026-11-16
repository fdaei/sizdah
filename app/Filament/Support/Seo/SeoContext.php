<?php

declare(strict_types=1);

namespace App\Filament\Support\Seo;

use App\Models\SeoMeta;
use App\Services\Seo\SeoAnalyzer;
use App\Services\Seo\SeoTitle;
use Illuminate\Database\Eloquent\Model;

/**
 * Describes, per resource, which translation fields feed the SEO tab:
 * the title (for %title%), the slug (edited in the snippet editor), the
 * body fields the analysis reads, and the public path prefix for the SERP
 * preview. Resources expose it via `public static function seoContext()`.
 */
final class SeoContext
{
    /**
     * @param  list<string>  $contentFields  translation attributes concatenated as the analysed body
     * @param  string|null  $slugField  translation attribute holding the slug (null = fixed URL)
     * @param  string|null  $pathPrefix  path segment(s) before the slug in the public URL
     * @param  string|null  $descriptionFallback  translation attribute used when meta description is empty
     * @param  string|null  $slugTable  translation table, for per-locale slug uniqueness
     */
    public function __construct(
        public readonly string $titleField = 'title',
        public readonly ?string $slugField = null,
        public readonly array $contentFields = [],
        public readonly ?string $pathPrefix = null,
        public readonly ?string $descriptionFallback = null,
        public readonly bool $isArticle = false,
        public readonly ?string $slugTable = null,
        public readonly ?string $slugForeignKey = null,
        public readonly string $defaultPageType = 'WebPage',
    ) {}

    /**
     * Analyzer input from raw form state (the same shape the form submits).
     *
     * @param  array<string, mixed>  $translation  translations.{locale}
     * @param  array<string, mixed>  $seo  seo.{locale}
     */
    public function analysisInput(array $translation, array $seo, string $locale, ?Model $record): array
    {
        $keyword = trim((string) ($seo['focus_keyword'] ?? ''));

        return [
            'keyword' => $keyword,
            'secondary' => (array) ($seo['secondary_keywords'] ?? []),
            'title' => SeoTitle::render(
                (string) ($seo['title'] ?? ''),
                (string) ($translation[$this->titleField] ?? ''),
                $locale,
            ),
            'description' => (string) (($seo['description'] ?? '') ?: ($this->descriptionFallback ? ($translation[$this->descriptionFallback] ?? '') : '')),
            'slug' => $this->slugField ? (string) ($translation[$this->slugField] ?? '') : '',
            'content' => $this->content($translation),
            'cornerstone' => (bool) ($seo['cornerstone'] ?? false),
            'usedElsewhere' => $keyword !== '' && SeoMeta::query()
                ->where('locale', $locale)
                ->where('focus_keyword', $keyword)
                ->when($record?->exists, fn ($q) => $q->whereNot(fn ($q) => $q
                    ->where('seoable_type', $record->getMorphClass())
                    ->where('seoable_id', $record->getKey())))
                ->exists(),
        ];
    }

    /**
     * @return array{seo_score:int, readability_score:int}
     */
    public function scores(array $translation, array $seo, string $locale, ?Model $record): array
    {
        $result = SeoAnalyzer::analyze($this->analysisInput($translation, $seo, $locale, $record));

        return [
            'seo_score' => $result['seo']['score'],
            'readability_score' => $result['readability']['score'],
        ];
    }

    /** @param array<string, mixed> $translation */
    public function content(array $translation): string
    {
        return collect($this->contentFields)
            ->map(function (string $f) use ($translation): string {
                $v = $translation[$f] ?? '';
                if (is_array($v)) {
                    // Repeater/list fields (e.g. features, challenge_points)
                    $v = '<ul><li>'.implode('</li><li>', array_map(
                        fn ($i): string => is_array($i) ? implode(' ', array_filter($i, 'is_string')) : (string) $i,
                        $v,
                    )).'</li></ul>';
                }
                $v = (string) $v;

                return str_contains($v, '<') ? $v : '<p>'.e($v).'</p>';
            })
            ->implode("\n");
    }
}
