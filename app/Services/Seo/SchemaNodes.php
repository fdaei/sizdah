<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Faq;
use App\Models\PageSection;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Services\MediaTransformer;
use App\Support\RichContent;
use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Extra JSON-LD nodes merged into SeoBuilder's @graph: FAQPage, team
 * Person nodes, testimonial Reviews (opt-in) and VideoObjects detected in
 * body HTML. All reference the stable `#organization` @id.
 */
final class SchemaNodes
{
    /**
     * @param  Collection<int, Faq>  $faqs
     * @return list<array<string, mixed>>
     */
    public static function faqPage(Collection $faqs, string $canonical): array
    {
        $questions = $faqs
            ->map(fn (Faq $f): ?array => filled($f->getTranslation('question')) && filled($f->getTranslation('answer')) ? [
                '@type' => 'Question',
                'name' => trim(strip_tags((string) $f->getTranslation('question'))),
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim((string) $f->getTranslation('answer'))],
            ] : null)
            ->filter()
            ->values()
            ->all();

        return $questions === [] ? [] : [[
            '@type' => 'FAQPage',
            '@id' => "{$canonical}#faq",
            'url' => $canonical,
            'mainEntity' => $questions,
        ]];
    }

    /**
     * @param  Collection<int, TeamMember>  $team
     * @return list<array<string, mixed>>
     */
    public static function people(Collection $team): array
    {
        $root = rtrim(url('/'), '/');

        return $team->map(fn (TeamMember $m): array => array_filter([
            '@type' => 'Person',
            '@id' => "{$root}/#person-{$m->getKey()}",
            'name' => (string) $m->getTranslation('name'),
            'jobTitle' => $m->getTranslation('role') ?: null,
            'image' => $m->photo_path ? self::absolute(MediaTransformer::url($m->photo_path)) : null,
            'worksFor' => ['@id' => "{$root}/#organization"],
        ]))->filter(fn (array $n): bool => filled($n['name']))->values()->all();
    }

    /**
     * Testimonials as Review nodes. Off by default: Google does not show
     * review stars for an organisation reviewing itself, and testimonials
     * carry no rating, so no AggregateRating is ever invented.
     *
     * @param  Collection<int, Testimonial>  $testimonials
     * @return list<array<string, mixed>>
     */
    public static function reviews(Collection $testimonials): array
    {
        if (! (bool) SiteSettings::get('seo_schema_reviews', null, false)) {
            return [];
        }
        $root = rtrim(url('/'), '/');

        return $testimonials->map(fn (Testimonial $t): array => [
            '@type' => 'Review',
            '@id' => "{$root}/#review-{$t->getKey()}",
            'itemReviewed' => ['@id' => "{$root}/#organization"],
            'author' => array_filter(['@type' => 'Person', 'name' => (string) $t->getTranslation('author_name'), 'jobTitle' => $t->getTranslation('author_role') ?: null]),
            'reviewBody' => trim(strip_tags((string) $t->getTranslation('quote'))),
        ])->filter(fn (array $r): bool => filled($r['author']['name'] ?? null) && filled($r['reviewBody']))->values()->all();
    }

    /**
     * The page's full authored content as one HTML string — the source for
     * articleBody / text and for VideoObject detection. Rich-text fields go
     * through RichContent so custom blocks (Aparat) render to real embeds.
     * Sections are only read when already eager-loaded: never a query here.
     *
     * @param  list<?string>  $richText  rich-text HTML fields
     * @param  list<mixed>  $plain  plain-text fields (strings or string lists)
     */
    public static function bodyHtml(array $richText = [], array $plain = [], ?Model $owner = null): string
    {
        $parts = array_map(fn (?string $html): string => RichContent::render($html), $richText);
        $parts = [...$parts, ...array_map(self::plainToHtml(...), $plain)];

        if ($owner !== null && $owner->relationLoaded('sections')) {
            foreach ($owner->getRelation('sections') as $section) {
                /** @var PageSection $section */
                if ($section->is_visible === false) {
                    continue;
                }
                $parts[] = self::plainToHtml([$section->getTranslation('title'), $section->getTranslation('subtitle'), $section->getTranslation('description')]);
                $parts[] = RichContent::render($section->getTranslation('content'));

                if ($section->relationLoaded('items')) {
                    foreach ($section->getRelation('items') as $item) {
                        if ($item->is_visible === false) {
                            continue;
                        }
                        $parts[] = self::plainToHtml([
                            $item->getTranslation('title'),
                            $item->getTranslation('description'),
                            $item->getTranslation('features'),
                        ]);
                    }
                }
            }
        }

        return trim(implode("\n", array_filter($parts, fn (string $p): bool => trim($p) !== '')));
    }

    /**
     * VideoObject for each YouTube / Aparat embed and each self-hosted
     * <video> / .mp4 / .webm file in the body.
     *
     * @return list<array<string, mixed>>
     */
    public static function videos(string $html, string $canonical, string $name, ?string $description, ?string $thumbnail, ?string $uploadDate): array
    {
        $found = [];

        preg_match_all('~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?v=|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $html, $yt);
        foreach (array_unique($yt[1]) as $id) {
            $found[] = [
                'embedUrl' => "https://www.youtube.com/embed/{$id}",
                'thumbnailUrl' => "https://i.ytimg.com/vi/{$id}/hqdefault.jpg",
            ];
        }

        preg_match_all('~aparat\.com/(?:v/|video/video/embed/videohash/)([A-Za-z0-9]+)~', $html, $ap);
        foreach (array_unique($ap[1]) as $hash) {
            $found[] = [
                'embedUrl' => "https://www.aparat.com/video/video/embed/videohash/{$hash}/vt/frame",
                'thumbnailUrl' => $thumbnail,
            ];
        }

        // Self-hosted files: <video src>, <source src>, or a plain link to the file.
        preg_match_all('~(?:src|href)\s*=\s*["\']([^"\']+\.(?:mp4|webm|ogv|mov|m4v))(?:\?[^"\']*)?["\']~i', $html, $files);
        preg_match_all('~<video\b[^>]*\bposter\s*=\s*["\']([^"\']+)["\']~i', $html, $posters);
        foreach (array_values(array_unique($files[1])) as $i => $file) {
            $found[] = [
                'contentUrl' => self::absolute(html_entity_decode($file)),
                'thumbnailUrl' => isset($posters[1][$i]) ? self::absolute(html_entity_decode($posters[1][$i])) : $thumbnail,
            ];
        }

        $description = $description ? trim(strip_tags($description)) : null;

        return array_values(array_filter(array_map(fn (array $v, int $i): ?array => $v['thumbnailUrl'] ? array_filter([
            '@type' => 'VideoObject',
            '@id' => "{$canonical}#video-".($i + 1),
            'name' => count($found) > 1 ? "{$name} — ".($i + 1) : $name,
            'description' => $description ?: $name,
            'thumbnailUrl' => $v['thumbnailUrl'],
            'uploadDate' => $uploadDate,
            'embedUrl' => $v['embedUrl'] ?? null,
            'contentUrl' => $v['contentUrl'] ?? null,
            'inLanguage' => config('locales.supported.'.app()->getLocale().'.html_lang'),
            'isPartOf' => ['@id' => "{$canonical}#main"],
            'publisher' => ['@id' => rtrim(url('/'), '/').'/#organization'],
        ]) : null, $found, array_keys($found))));
    }

    /** @param  mixed  $value  string, list of strings, or list of {text|title|label} rows */
    private static function plainToHtml(mixed $value): string
    {
        if (is_array($value)) {
            return implode("\n", array_map(
                fn ($v): string => self::plainToHtml(is_array($v) ? ($v['text'] ?? $v['title'] ?? $v['label'] ?? $v) : $v),
                $value,
            ));
        }

        $text = trim((string) $value);

        return $text === '' ? '' : '<p>'.e($text).'</p>';
    }

    private static function absolute(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        return preg_match('#^https?://#i', $url) ? $url : url('/'.ltrim($url, '/'));
    }
}
