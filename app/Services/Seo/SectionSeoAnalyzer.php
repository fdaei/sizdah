<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\PageSection;
use Illuminate\Database\Eloquent\Model;

/**
 * SEO checks for one content section of a Page/Project, plus an HTML
 * rendering of a section that the page-level SeoAnalyzer reads as body.
 *
 * A section is not its own URL, so it has no title/meta/slug; what matters is
 * that it carries a heading, enough text, the keyphrase it targets (its own,
 * or the page's focus keyword as fallback), image alt text, and coherent
 * buttons. Works on raw form state so the panel updates live.
 */
final class SectionSeoAnalyzer
{
    /**
     * Section form state (translations.{locale} + items) → HTML, mirroring the
     * public markup: title as <h2>, cards as <h3>, image with its alt.
     *
     * @param  array<string, mixed>  $t  translations.{locale}
     * @param  list<array<string, mixed>>  $items  each with translations.{locale}
     */
    public static function html(array $t, array $items, string $locale, ?string $imagePath = null): string
    {
        $out = [];
        $str = fn (mixed $v): string => is_array($v) ? implode(' ', array_filter($v, 'is_string')) : trim((string) $v);
        $p = fn (string $v): string => str_contains($v, '<') ? $v : '<p>'.e($v).'</p>';

        if (($title = $str($t['title'] ?? '')) !== '') {
            $out[] = '<h2>'.e($title).'</h2>';
        }
        foreach (['subtitle', 'description', 'content'] as $f) {
            if (($v = $str($t[$f] ?? '')) !== '') {
                $out[] = $p($v);
            }
        }
        if ($imagePath) {
            $out[] = '<img src="'.e($imagePath).'" alt="'.e($str($t['image_alt'] ?? '')).'">';
        }
        if (($label = $str($t['primary_cta_label'] ?? '')) !== '') {
            $out[] = '<a href="'.e($str($t['primary_cta_url'] ?? '')).'">'.e($label).'</a>';
        }
        foreach ($items as $item) {
            $it = (array) ($item['translations'][$locale] ?? []);
            if (($title = $str($it['title'] ?? '')) !== '') {
                $out[] = '<h3>'.e($title).'</h3>';
            }
            foreach (['description', 'features'] as $f) {
                if (($v = $str($it[$f] ?? '')) !== '') {
                    $out[] = $p($v);
                }
            }
        }

        return implode("\n", $out);
    }

    /** Visible sections of a saved Page/Project rendered as one HTML body. */
    public static function ownerHtml(?Model $owner, string $locale): string
    {
        if ($owner === null || ! $owner->exists || ! method_exists($owner, 'sections')) {
            return '';
        }

        return $owner->sections()
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->with(['translations', 'items.translations'])
            ->get()
            ->map(fn (PageSection $s): string => self::html(
                self::translationArray($s, $locale),
                $s->items->map(fn ($i): array => ['translations' => [$locale => self::translationArray($i, $locale)]])->all(),
                $locale,
                $s->image_path,
            ))
            ->implode("\n");
    }

    /**
     * @param  array<string, mixed>  $t  translations.{locale}
     * @param  list<array<string, mixed>>  $items
     * @return array{score:int, keyword:string, inherited:bool, checks: list<array{id:string,status:string,text:string}>}
     */
    public static function analyze(array $t, array $items, string $locale, ?string $imagePath, ?string $pageKeyword): array
    {
        $own = trim((string) ($t['focus_keyword'] ?? ''));
        $keyword = $own !== '' ? $own : trim((string) $pageKeyword);
        $kw = SeoAnalyzer::normalize($keyword);
        $title = trim((string) ($t['title'] ?? ''));
        $titleN = SeoAnalyzer::normalize($title);
        $text = SeoAnalyzer::plain(self::html($t, $items, $locale));
        $textN = SeoAnalyzer::normalize($text);
        $wordCount = count(SeoAnalyzer::words($text));
        $c = [];

        // Heading
        $titleLen = mb_strlen($title);
        $c[] = match (true) {
            $title === '' => self::check('bad', 'title', 'عنوان بخش (Section heading): این بخش عنوان ندارد؛ موتورهای جستجو ساختار صفحه را از زیرعنوان‌ها (H2) می‌فهمند.'),
            $titleLen > 70 => self::check('ok', 'title', "طول عنوان بخش: {$titleLen} نویسه — کوتاه‌تر از ۷۰ نویسه خواناتر است."),
            default => self::check('good', 'title', "عنوان بخش: {$titleLen} نویسه — مناسب است."),
        };

        // Keyphrase
        if ($kw === '') {
            $c[] = self::check('bad', 'keyphrase', 'کلمه کلیدی بخش: نه این بخش و نه صفحه کلمه کلیدی ندارند. یک کلمه کلیدی برای این بخش یا در تب سئوی صفحه وارد کنید.');
        } else {
            $c[] = $own === ''
                ? self::check('ok', 'keyphrase', "کلمه کلیدی بخش: از کلمه کلیدی صفحه («{$keyword}») استفاده می‌شود. برای پوشش بهتر، یک کلمه کلیدی مرتبط مخصوص این بخش تعیین کنید.")
                : self::check('good', 'keyphrase', "کلمه کلیدی بخش: «{$keyword}».");

            $c[] = SeoAnalyzer::contains($titleN, $kw)
                ? self::check('good', 'keyphrase_title', 'کلمه کلیدی در عنوان بخش آمده است.')
                : self::check($own === '' ? 'ok' : 'bad', 'keyphrase_title', 'کلمه کلیدی در عنوان بخش نیامده است.');

            $body = SeoAnalyzer::normalize(SeoAnalyzer::plain(self::html(array_diff_key($t, ['title' => 1]), $items, $locale)));
            $c[] = SeoAnalyzer::contains($body, $kw)
                ? self::check('good', 'keyphrase_body', 'کلمه کلیدی در متن بخش آمده است.')
                : self::check('ok', 'keyphrase_body', 'کلمه کلیدی در متن بخش (توضیحات، محتوا یا کارت‌ها) نیامده است.');

            $occ = $textN === '' ? 0 : preg_match_all('/(^|\s)'.preg_quote($kw, '/').'/u', $textN);
            if ($wordCount >= 30 && $occ * count(SeoAnalyzer::words($kw)) / $wordCount > 0.05) {
                $c[] = self::check('bad', 'keyphrase_stuffing', "تکرار بیش از حد کلمه کلیدی در این بخش ({$occ} بار) — طبیعی‌تر بنویسید.");
            }
        }

        // Length
        $c[] = match (true) {
            $wordCount >= 40 => self::check('good', 'length', "طول متن بخش: {$wordCount} کلمه."),
            $wordCount >= 15 => self::check('ok', 'length', "طول متن بخش: {$wordCount} کلمه — متن کوتاه است؛ در صورت امکان توضیح بیشتری بدهید."),
            default => self::check('bad', 'length', "طول متن بخش: {$wordCount} کلمه — محتوای متنی این بخش برای موتورهای جستجو بسیار کم است."),
        };

        // Image alt
        if ($imagePath) {
            $alt = trim((string) ($t['image_alt'] ?? ''));
            $c[] = match (true) {
                $alt === '' => self::check('bad', 'image_alt', 'تصویر بخش متن جایگزین (alt) ندارد.'),
                $kw !== '' && ! SeoAnalyzer::contains(SeoAnalyzer::normalize($alt), $kw) => self::check('ok', 'image_alt', 'متن جایگزین تصویر وجود دارد اما شامل کلمه کلیدی نیست.'),
                default => self::check('good', 'image_alt', 'تصویر بخش متن جایگزین (alt) مناسب دارد.'),
            };
        }

        // Buttons
        foreach (['primary' => 'اول', 'secondary' => 'دوم'] as $k => $fa) {
            $label = trim((string) ($t["{$k}_cta_label"] ?? ''));
            $url = trim((string) ($t["{$k}_cta_url"] ?? ''));
            if ($label !== '' xor $url !== '') {
                $c[] = self::check('bad', "{$k}_cta", "دکمه {$fa}: ".($label === '' ? 'لینک دارد اما متن ندارد.' : 'متن دارد اما لینک ندارد.'));
            } elseif ($label !== '' && in_array(SeoAnalyzer::normalize($label), ['اینجا', 'کلیک کنید', 'بیشتر', 'click here', 'here', 'more'], true)) {
                $c[] = self::check('ok', "{$k}_cta", "دکمه {$fa}: متن «{$label}» توصیفی نیست؛ متنی بنویسید که مقصد لینک را بگوید.");
            }
        }

        // Cards
        $untitled = count(array_filter($items, fn (array $i): bool => trim((string) ($i['translations'][$locale]['title'] ?? '')) === ''
            && trim((string) ($i['translations'][$locale]['value'] ?? '')) === ''));
        if ($untitled > 0) {
            $c[] = self::check('ok', 'items', "{$untitled} کارت بدون عنوان است.");
        }

        return SeoAnalyzer::score($c) + ['keyword' => $keyword, 'inherited' => $own === '' && $keyword !== ''];
    }

    /** @return array<string, mixed> */
    private static function translationArray(Model $m, string $locale): array
    {
        $tr = $m->translations->firstWhere('locale', $locale);

        return $tr ? $tr->only($tr->getFillable()) : [];
    }

    private static function check(string $status, string $id, string $text): array
    {
        return ['id' => $id, 'status' => $status, 'text' => $text];
    }
}
