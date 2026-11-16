<?php

declare(strict_types=1);

namespace App\Services\Seo;

/**
 * Yoast-style SEO + readability analysis, Persian-aware.
 *
 * Single source of truth: the admin SEO tab renders these results live (via
 * Filament reactive state) and the save hook stores the two scores on
 * seo_meta for the resource-table column/filter.
 *
 * Persian handling: Arabic ي/ك are normalised to Persian ی/ک, diacritics and
 * tatweel stripped, ZWNJ (نیم‌فاصله) treated as a word joiner when matching
 * keyphrases ("بازاریابی‌دیجیتال" matches "بازاریابی دیجیتال"), and Persian
 * punctuation (؟ ؛ ،) counts for sentence/word splitting.
 *
 * Each check yields good|ok|bad; score = weighted mean (9/6/3 like Yoast),
 * scaled to 0–100.
 */
final class SeoAnalyzer
{
    public const GOOD = 'good';

    public const OK = 'ok';

    public const BAD = 'bad';

    /** Persian + common Arabic-script transition words (Yoast's list concept). */
    private const TRANSITION_WORDS = [
        'اما', 'ولی', 'بنابراین', 'درنتیجه', 'در نتیجه', 'همچنین', 'علاوه بر این', 'به علاوه',
        'سپس', 'پس', 'زیرا', 'چون', 'چرا که', 'در حالی که', 'درحالیکه', 'با این حال', 'باوجوداین',
        'با وجود این', 'به عنوان مثال', 'برای مثال', 'مثلا', 'مثلاً', 'به طور خلاصه', 'در نهایت',
        'درنهایت', 'سرانجام', 'نخست', 'اول', 'دوم', 'سوم', 'همچنان', 'از طرفی', 'از طرف دیگر',
        'از سوی دیگر', 'در مقابل', 'به همین دلیل', 'به این ترتیب', 'بعد از آن', 'قبل از آن',
        'یعنی', 'به عبارت دیگر', 'در واقع', 'درواقع', 'البته', 'به‌علاوه', 'به‌عنوان مثال',
        'به‌طور خلاصه', 'به‌همین‌دلیل', 'از این رو', 'ازاین‌رو', 'لذا', 'در ضمن', 'ضمناً',
        'however', 'therefore', 'because', 'also', 'moreover', 'finally', 'for example',
    ];

    /**
     * @param  array{
     *   keyword?: ?string, secondary?: array<int,string>|null, title?: ?string,
     *   description?: ?string, slug?: ?string, content?: ?string,
     *   cornerstone?: bool, siteHost?: ?string, usedElsewhere?: bool
     * }  $in
     * @return array{seo: array{score:int, checks: list<array{id:string,status:string,text:string}>},
     *               readability: array{score:int, checks: list<array{id:string,status:string,text:string}>}}
     */
    public static function analyze(array $in): array
    {
        $html = (string) ($in['content'] ?? '');
        $text = self::plain($html);
        $words = self::words($text);

        return [
            'seo' => self::score(self::seoChecks($in, $html, $text, $words)),
            'readability' => self::score(self::readabilityChecks($html, $text, $words)),
        ];
    }

    /**
     * @return list<array{id:string,status:string,text:string}>
     */
    private static function seoChecks(array $in, string $html, string $text, array $words): array
    {
        $kw = self::normalize((string) ($in['keyword'] ?? ''));
        $title = (string) ($in['title'] ?? '');
        $desc = (string) ($in['description'] ?? '');
        $slug = urldecode((string) ($in['slug'] ?? ''));
        $cornerstone = (bool) ($in['cornerstone'] ?? false);
        $wordCount = count($words);
        $c = [];

        // ── Keyphrase checks ────────────────────────────────────────────
        if ($kw === '') {
            $c[] = self::bad('keyphrase', 'کلمه کلیدی کانونی (Focus keyphrase) تعیین نشده است. یک کلمه کلیدی وارد کنید تا امتیاز سئو محاسبه شود.');
        } else {
            $kwWords = count(self::words($kw));
            $c[] = $kwWords <= 4
                ? self::good('keyphrase_length', "طول کلمه کلیدی (Keyphrase length): {$kwWords} کلمه — عالی است.")
                : self::ok('keyphrase_length', "طول کلمه کلیدی (Keyphrase length): {$kwWords} کلمه — کوتاه‌تر بهتر است (حداکثر ۴).");

            $titleN = self::normalize($title);
            $c[] = match (true) {
                $titleN !== '' && str_starts_with($titleN, $kw) => self::good('keyphrase_title', 'کلمه کلیدی در عنوان سئو (Keyphrase in SEO title): در ابتدای عنوان آمده است. آفرین!'),
                self::contains($titleN, $kw) => self::ok('keyphrase_title', 'کلمه کلیدی در عنوان سئو (Keyphrase in SEO title): آمده، اما بهتر است در ابتدای عنوان باشد.'),
                default => self::bad('keyphrase_title', 'کلمه کلیدی در عنوان سئو (Keyphrase in SEO title): عنوان شامل کلمه کلیدی نیست.'),
            };

            $c[] = self::contains(self::normalize(self::firstParagraph($html)), $kw)
                ? self::good('keyphrase_intro', 'کلمه کلیدی در مقدمه (Keyphrase in introduction): در پاراگراف اول آمده است.')
                : self::bad('keyphrase_intro', 'کلمه کلیدی در مقدمه (Keyphrase in introduction): کلمه کلیدی را در پاراگراف اول بیاورید.');

            $c[] = self::contains(self::normalize($desc), $kw)
                ? self::good('keyphrase_meta', 'کلمه کلیدی در توضیحات متا (Keyphrase in meta description): آمده است.')
                : self::bad('keyphrase_meta', 'کلمه کلیدی در توضیحات متا (Keyphrase in meta description): توضیحات متا شامل کلمه کلیدی نیست.');

            $slugN = self::normalize(str_replace(['-', '_'], ' ', $slug));
            $c[] = self::contains($slugN, $kw)
                ? self::good('keyphrase_slug', 'کلمه کلیدی در نامک (Keyphrase in slug): آمده است.')
                : self::ok('keyphrase_slug', 'کلمه کلیدی در نامک (Keyphrase in slug): نامک (URL) شامل کلمه کلیدی نیست.');

            $headings = self::headings($html);
            if ($headings === []) {
                $c[] = self::ok('keyphrase_subheadings', 'کلمه کلیدی در زیرعنوان‌ها (Keyphrase in subheadings): متن زیرعنوان (H2/H3) ندارد.');
            } else {
                $hits = count(array_filter($headings, fn (string $h): bool => self::contains(self::normalize($h), $kw)));
                $ratio = $hits / count($headings);
                $c[] = match (true) {
                    $ratio >= 0.3 && $ratio <= 0.75 => self::good('keyphrase_subheadings', "کلمه کلیدی در زیرعنوان‌ها (Keyphrase in subheadings): در {$hits} زیرعنوان آمده است. عالی!"),
                    $hits > 0 => self::ok('keyphrase_subheadings', "کلمه کلیدی در زیرعنوان‌ها (Keyphrase in subheadings): در {$hits} از ".count($headings).' زیرعنوان — بین ۳۰٪ تا ۷۵٪ مناسب است.'),
                    default => self::bad('keyphrase_subheadings', 'کلمه کلیدی در زیرعنوان‌ها (Keyphrase in subheadings): هیچ زیرعنوانی شامل کلمه کلیدی نیست.'),
                };
            }

            if ($wordCount > 0) {
                $occ = self::occurrences(self::normalize($text), $kw);
                $density = $occ * $kwWords / $wordCount * 100;
                $d = number_format($density, 1);
                $c[] = match (true) {
                    $density >= 0.5 && $density <= 3 => self::good('keyphrase_density', "چگالی کلمه کلیدی (Keyphrase density): {$d}٪ ({$occ} بار) — عالی است."),
                    $density > 3 => self::bad('keyphrase_density', "چگالی کلمه کلیدی (Keyphrase density): {$d}٪ — بیش از حد تکرار شده (Keyword stuffing)."),
                    default => self::bad('keyphrase_density', "چگالی کلمه کلیدی (Keyphrase density): {$d}٪ ({$occ} بار) — کمتر از حد مطلوب ۰٫۵٪."),
                };
            }

            if (($in['usedElsewhere'] ?? false) === true) {
                $c[] = self::bad('previously_used', 'کلمه کلیدی تکراری (Previously used keyphrase): این کلمه کلیدی قبلاً برای محتوای دیگری استفاده شده است.');
            } else {
                $c[] = self::good('previously_used', 'کلمه کلیدی تکراری (Previously used keyphrase): قبلاً استفاده نشده است.');
            }

            $images = self::images($html);
            if ($images !== []) {
                $alts = array_filter($images, fn (string $alt): bool => self::contains(self::normalize($alt), $kw));
                $c[] = $alts !== []
                    ? self::good('image_keyphrase', 'کلمه کلیدی در متن جایگزین تصاویر (Image keyphrase): آمده است.')
                    : self::ok('image_keyphrase', 'کلمه کلیدی در متن جایگزین تصاویر (Image keyphrase): هیچ alt تصویری شامل کلمه کلیدی نیست.');
            }
        }

        // ── Content checks (independent of keyphrase) ───────────────────

        // Yoast "Single title": the page title is already the H1.
        $h1 = preg_match_all('/<h1\b/iu', $html);
        $c[] = $h1 === 0
            ? self::good('single_title', 'عنوان H1 تکی (Single title): متن H1 اضافه ندارد؛ عنوان صفحه همان H1 است.')
            : self::bad('single_title', "عنوان H1 تکی (Single title): متن {$h1} عنوان H1 دارد. عنوان صفحه خودش H1 است؛ در متن از H2 تا H6 استفاده کنید.");

        $min = $cornerstone ? 900 : 300;
        $c[] = match (true) {
            $wordCount >= $min => self::good('text_length', "طول متن (Text length): {$wordCount} کلمه — عالی است."),
            $wordCount >= (int) ($min * 0.8) => self::ok('text_length', "طول متن (Text length): {$wordCount} کلمه — کمی کمتر از حداقل توصیه‌شده ({$min})."),
            default => self::bad('text_length', "طول متن (Text length): {$wordCount} کلمه — حداقل {$min} کلمه توصیه می‌شود".($cornerstone ? ' برای محتوای ستون (Cornerstone).' : '.')),
        };

        [$internal, $outbound] = self::links($html, $in['siteHost'] ?? null);
        $c[] = $internal > 0
            ? self::good('internal_links', "لینک‌های داخلی (Internal links): {$internal} لینک داخلی دارید.")
            : self::bad('internal_links', 'لینک‌های داخلی (Internal links): هیچ لینک داخلی ندارید. به محتوای مرتبط سایت لینک دهید.');
        $c[] = $outbound > 0
            ? self::good('outbound_links', "لینک‌های خروجی (Outbound links): {$outbound} لینک خروجی دارید.")
            : self::ok('outbound_links', 'لینک‌های خروجی (Outbound links): هیچ لینک خروجی ندارید.');

        $images = self::images($html);
        if ($images === []) {
            $c[] = self::ok('images', 'تصاویر (Images): متن هیچ تصویری ندارد.');
        } else {
            $missing = count(array_filter($images, fn (string $a): bool => trim($a) === ''));
            $c[] = $missing === 0
                ? self::good('images', 'تصاویر (Images): همه تصاویر متن جایگزین (alt) دارند.')
                : self::bad('images', "تصاویر (Images): {$missing} تصویر بدون متن جایگزین (alt) است.");
        }

        $tLen = mb_strlen(trim($title));
        $c[] = match (true) {
            $tLen === 0 => self::bad('title_width', 'عرض عنوان سئو (SEO title width): عنوان سئو خالی است.'),
            $tLen < 30 => self::ok('title_width', "عرض عنوان سئو (SEO title width): {$tLen} نویسه — خیلی کوتاه است."),
            $tLen <= 60 => self::good('title_width', "عرض عنوان سئو (SEO title width): {$tLen} نویسه — عالی است."),
            default => self::bad('title_width', "عرض عنوان سئو (SEO title width): {$tLen} نویسه — بیش از ۶۰ است و در گوگل بریده می‌شود."),
        };

        $dLen = mb_strlen(trim($desc));
        $c[] = match (true) {
            $dLen === 0 => self::bad('meta_length', 'طول توضیحات متا (Meta description length): خالی است؛ گوگل بخشی از متن را نمایش می‌دهد.'),
            $dLen < 120 => self::ok('meta_length', "طول توضیحات متا (Meta description length): {$dLen} نویسه — کوتاه است (۱۲۰ تا ۱۵۶ مناسب است)."),
            $dLen <= 156 => self::good('meta_length', "طول توضیحات متا (Meta description length): {$dLen} نویسه — عالی است."),
            default => self::ok('meta_length', "طول توضیحات متا (Meta description length): {$dLen} نویسه — بیش از ۱۵۶ است و بریده می‌شود."),
        };

        foreach (array_filter((array) ($in['secondary'] ?? [])) as $related) {
            $r = self::normalize((string) $related);
            if ($r === '') {
                continue;
            }
            $c[] = self::contains(self::normalize($text), $r)
                ? self::good('related_'.md5($r), "کلمه کلیدی مرتبط (Related keyphrase) «{$related}»: در متن آمده است.")
                : self::ok('related_'.md5($r), "کلمه کلیدی مرتبط (Related keyphrase) «{$related}»: در متن نیامده است.");
        }

        return $c;
    }

    /**
     * @return list<array{id:string,status:string,text:string}>
     */
    private static function readabilityChecks(string $html, string $text, array $words): array
    {
        $c = [];
        $sentences = self::sentences($text);
        $wordCount = count($words);

        if ($wordCount < 50) {
            return [self::bad('not_enough_content', 'محتوای کافی (Not enough content): برای تحلیل خوانایی، حداقل ۵۰ کلمه بنویسید.')];
        }

        // Sentence length: ≤25% of sentences over 20 words.
        $long = count(array_filter($sentences, fn (string $s): bool => count(self::words($s)) > 20));
        $pct = $sentences === [] ? 0 : round($long / count($sentences) * 100);
        $c[] = match (true) {
            $pct <= 25 => self::good('sentence_length', "طول جمله‌ها (Sentence length): {$pct}٪ جمله‌ها بیش از ۲۰ کلمه‌اند — عالی است."),
            $pct <= 30 => self::ok('sentence_length', "طول جمله‌ها (Sentence length): {$pct}٪ جمله‌ها طولانی‌اند — حداکثر ۲۵٪ توصیه می‌شود."),
            default => self::bad('sentence_length', "طول جمله‌ها (Sentence length): {$pct}٪ جمله‌ها بیش از ۲۰ کلمه‌اند. جمله‌ها را کوتاه کنید."),
        };

        // Paragraph length: none over 150 words.
        $paragraphs = self::paragraphs($html);
        $longP = count(array_filter($paragraphs, fn (string $p): bool => count(self::words($p)) > 150));
        $c[] = $longP === 0
            ? self::good('paragraph_length', 'طول پاراگراف‌ها (Paragraph length): هیچ پاراگرافی طولانی نیست.')
            : self::bad('paragraph_length', "طول پاراگراف‌ها (Paragraph length): {$longP} پاراگراف بیش از ۱۵۰ کلمه دارد.");

        // Subheading distribution: no run of >300 words without a subheading.
        if ($wordCount > 300) {
            $chunks = preg_split('/<h[2-6][^>]*>.*?<\/h[2-6]>/isu', $html) ?: [$html];
            $over = count(array_filter($chunks, fn (string $ch): bool => count(self::words(self::plain($ch))) > 300));
            $c[] = $over === 0
                ? self::good('subheading_distribution', 'توزیع زیرعنوان‌ها (Subheading distribution): عالی است.')
                : self::bad('subheading_distribution', "توزیع زیرعنوان‌ها (Subheading distribution): {$over} بخش بیش از ۳۰۰ کلمه بدون زیرعنوان دارد.");
        } else {
            $c[] = self::good('subheading_distribution', 'توزیع زیرعنوان‌ها (Subheading distribution): متن کوتاه است و نیازی به زیرعنوان بیشتر ندارد.');
        }

        // Transition words: ≥30% of sentences.
        if (count($sentences) > 0) {
            $tw = array_map(self::normalize(...), self::TRANSITION_WORDS);
            $withTw = count(array_filter($sentences, function (string $s) use ($tw): bool {
                $n = ' '.self::normalize($s).' ';
                foreach ($tw as $w) {
                    if (str_contains($n, ' '.$w.' ')) {
                        return true;
                    }
                }

                return false;
            }));
            $tp = round($withTw / count($sentences) * 100);
            $c[] = match (true) {
                $tp >= 30 => self::good('transition_words', "کلمات ربط (Transition words): {$tp}٪ جمله‌ها کلمه ربط دارند — عالی است."),
                $tp >= 20 => self::ok('transition_words', "کلمات ربط (Transition words): {$tp}٪ — حداقل ۳۰٪ توصیه می‌شود."),
                default => self::bad('transition_words', "کلمات ربط (Transition words): فقط {$tp}٪ جمله‌ها کلمه ربط (مانند «بنابراین»، «همچنین»، «اما») دارند."),
            };
        }

        // Consecutive sentences starting with the same word (≥3 in a row).
        $run = 1;
        $maxRun = 1;
        $prev = null;
        foreach ($sentences as $s) {
            $first = self::words($s)[0] ?? null;
            $run = ($first !== null && $first === $prev) ? $run + 1 : 1;
            $maxRun = max($maxRun, $run);
            $prev = $first;
        }
        $c[] = $maxRun < 3
            ? self::good('consecutive_sentences', 'شروع تکراری جمله‌ها (Consecutive sentences): تنوع خوبی دارد.')
            : self::bad('consecutive_sentences', "شروع تکراری جمله‌ها (Consecutive sentences): {$maxRun} جمله پشت سر هم با یک کلمه شروع شده‌اند.");

        return $c;
    }

    // ── Text utilities ─────────────────────────────────────────────────

    public static function normalize(string $s): string
    {
        $s = strtr($s, ['ي' => 'ی', 'ى' => 'ی', 'ك' => 'ک', 'ة' => 'ه', 'ۀ' => 'ه', 'أ' => 'ا', 'إ' => 'ا', 'ٱ' => 'ا', "\u{0640}" => '']);
        $s = preg_replace('/[\x{064B}-\x{065F}\x{0670}]/u', '', $s) ?? $s; // diacritics
        $s = str_replace(["\u{200C}", "\u{200D}", "\u{00A0}"], ' ', $s);    // ZWNJ/ZWJ/nbsp
        $s = mb_strtolower($s);
        $s = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $s) ?? $s;

        return trim(preg_replace('/\s+/u', ' ', $s) ?? $s);
    }

    /** @return list<string> */
    public static function words(string $text): array
    {
        $n = self::normalize($text);

        return $n === '' ? [] : explode(' ', $n);
    }

    public static function plain(string $html): string
    {
        $html = preg_replace('/<(script|style)[^>]*>.*?<\/\1>/isu', ' ', $html) ?? $html;
        $html = preg_replace('/<\/(p|h[1-6]|li|div|blockquote|br)\s*>|<br\s*\/?>/iu', "$0\n", $html) ?? $html;

        return trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /** @return list<string> */
    private static function sentences(string $text): array
    {
        $parts = preg_split('/(?<=[.!?؟!…])\s+|\n+/u', $text) ?: [];

        return array_values(array_filter(array_map('trim', $parts), fn (string $s): bool => self::words($s) !== []));
    }

    /** @return list<string> */
    private static function paragraphs(string $html): array
    {
        if (preg_match_all('/<p[^>]*>(.*?)<\/p>/isu', $html, $m) > 0) {
            return array_map(self::plain(...), $m[1]);
        }

        return array_values(array_filter(preg_split('/\n\s*\n/u', self::plain($html)) ?: []));
    }

    private static function firstParagraph(string $html): string
    {
        return self::paragraphs($html)[0] ?? mb_substr(self::plain($html), 0, 600);
    }

    /** @return list<string> */
    private static function headings(string $html): array
    {
        preg_match_all('/<h[2-6][^>]*>(.*?)<\/h[2-6]>/isu', $html, $m);

        return array_map(self::plain(...), $m[1]);
    }

    /** @return list<string> alt texts ('' when missing) */
    private static function images(string $html): array
    {
        preg_match_all('/<img\b[^>]*>/iu', $html, $m);

        return array_map(
            fn (string $tag): string => preg_match('/\balt\s*=\s*(["\'])(.*?)\1/iu', $tag, $a) ? $a[2] : '',
            $m[0],
        );
    }

    /** @return array{0:int,1:int} [internal, outbound] */
    private static function links(string $html, ?string $siteHost): array
    {
        preg_match_all('/<a\b[^>]*\bhref\s*=\s*(["\'])(.*?)\1/iu', $html, $m);
        $siteHost ??= parse_url((string) config('app.url'), PHP_URL_HOST);
        $internal = $outbound = 0;

        foreach ($m[2] as $href) {
            if ($href === '' || str_starts_with($href, '#') || preg_match('/^(mailto|tel|javascript):/i', $href)) {
                continue;
            }
            $host = parse_url($href, PHP_URL_HOST);
            ($host === null || $host === false || $host === $siteHost) ? $internal++ : $outbound++;
        }

        return [$internal, $outbound];
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return $needle !== '' && str_contains(' '.$haystack.' ', ' '.$needle.' ')
            // Persian suffixes (ها، های، ی، ای) attach to the word; accept prefix matches.
            || ($needle !== '' && preg_match('/(^|\s)'.preg_quote($needle, '/').'/u', $haystack) === 1);
    }

    private static function occurrences(string $haystack, string $needle): int
    {
        return $needle === '' ? 0 : preg_match_all('/(^|\s)'.preg_quote($needle, '/').'/u', $haystack);
    }

    /**
     * @param  list<array{id:string,status:string,text:string}>  $checks
     * @return array{score:int, checks: list<array{id:string,status:string,text:string}>}
     */
    public static function score(array $checks): array
    {
        $points = [self::GOOD => 9, self::OK => 6, self::BAD => 3];
        $sum = array_sum(array_map(fn (array $c): int => $points[$c['status']], $checks));
        $score = $checks === [] ? 0 : (int) round(($sum - 3 * count($checks)) / (6 * count($checks)) * 100);

        // Bad first, like Yoast's "Problems / Improvements / Good results" grouping.
        usort($checks, fn (array $a, array $b): int => array_search($a['status'], [self::BAD, self::OK, self::GOOD], true)
            <=> array_search($b['status'], [self::BAD, self::OK, self::GOOD], true));

        return ['score' => $score, 'checks' => $checks];
    }

    private static function good(string $id, string $text): array
    {
        return ['id' => $id, 'status' => self::GOOD, 'text' => $text];
    }

    private static function ok(string $id, string $text): array
    {
        return ['id' => $id, 'status' => self::OK, 'text' => $text];
    }

    private static function bad(string $id, string $text): array
    {
        return ['id' => $id, 'status' => self::BAD, 'text' => $text];
    }
}
