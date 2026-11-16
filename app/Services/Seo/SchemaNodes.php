<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Faq;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Services\MediaTransformer;
use App\Support\SiteSettings;
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
     * VideoObject for each YouTube / Aparat embed in the body.
     *
     * @return list<array<string, mixed>>
     */
    public static function videos(string $html, string $canonical, string $name, ?string $description, ?string $thumbnail, ?string $uploadDate): array
    {
        $found = [];

        preg_match_all('~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?v=)|youtu\.be/)([A-Za-z0-9_-]{11})~', $html, $yt);
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

        return array_values(array_filter(array_map(fn (array $v, int $i): ?array => $v['thumbnailUrl'] ? array_filter([
            '@type' => 'VideoObject',
            '@id' => "{$canonical}#video-".($i + 1),
            'name' => $name,
            'description' => $description ?: $name,
            'thumbnailUrl' => $v['thumbnailUrl'],
            'uploadDate' => $uploadDate,
            'embedUrl' => $v['embedUrl'],
            'isPartOf' => ['@id' => "{$canonical}#main"],
        ]) : null, $found, array_keys($found))));
    }

    private static function absolute(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        return preg_match('#^https?://#i', $url) ? $url : url('/'.ltrim($url, '/'));
    }
}
