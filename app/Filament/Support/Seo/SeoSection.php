<?php

declare(strict_types=1);

namespace App\Filament\Support\Seo;

use App\Enums\SchemaArticleType;
use App\Enums\SchemaPageType;
use App\Filament\Pages\SeoGuide;
use App\Models\SeoMeta;
use App\Services\Seo\OgImageCheck;
use App\Services\Seo\SeoAnalyzer;
use App\Services\Seo\SeoTitle;
use Closure;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * The Yoast-style SEO meta box, added to every content resource:
 *
 *   SEO · Readability · Social · Schema · Advanced
 *
 * Fields are stored under `seo.{locale}.*` (persisted by HandlesTranslations
 * into seo_meta). The slug lives here too — Yoast edits it in the snippet
 * editor — but keeps its `translations.{locale}.{slugField}` state path, so
 * the title→slug auto-fill and translation persistence are unchanged.
 *
 * Analysis is computed server-side by SeoAnalyzer on each Livewire round
 * trip (SEO fields are live), so the panel and the stored table score can
 * never disagree.
 */
final class SeoSection
{
    public static function make(SeoContext $ctx): Component
    {
        $locales = config('locales.supported');

        $perLocale = fn (string $locale): array => [self::innerTabs($ctx, $locale)];

        $body = count($locales) > 1
            ? [Tabs::make('seo-locales')->tabs(collect($locales)->map(
                fn (array $cfg, string $locale): Tabs\Tab => Tabs\Tab::make($cfg['native'])
                    ->schema($perLocale($locale))
                    ->extraAttributes(['dir' => $cfg['direction'], 'lang' => $cfg['html_lang']]),
            )->values()->all())]
            : $perLocale((string) array_key_first($locales));

        return Section::make('سئو (Yoast SEO)')
            ->description(new HtmlString(
                'عنوان، توضیحات، تحلیل سئو و خوانایی، شبکه‌های اجتماعی، اسکیما و تنظیمات پیشرفته — '
                .'<a href="'.e(SeoGuide::getUrl()).'" target="_blank" style="text-decoration:underline">راهنمای سئو (SEO guide)</a>'
            ))
            ->icon('heroicon-o-magnifying-glass-circle')
            ->collapsible()
            ->columnSpanFull()
            ->schema($body);
    }

    private static function innerTabs(SeoContext $ctx, string $locale): Tabs
    {
        $dir = config("locales.supported.{$locale}.direction");

        return Tabs::make("seo-{$locale}")
            ->extraAttributes(['dir' => $dir])
            ->tabs([
                Tabs\Tab::make('سئو (SEO)')
                    ->icon('heroicon-o-magnifying-glass')
                    ->badge(fn (Get $get, ?Model $record): string => (string) self::analysis($ctx, $get, $locale, $record)['seo']['score'])
                    ->badgeColor(fn (Get $get, ?Model $record): string => self::color(self::analysis($ctx, $get, $locale, $record)['seo']['score']))
                    ->schema(self::seoTab($ctx, $locale)),

                Tabs\Tab::make('خوانایی (Readability)')
                    ->icon('heroicon-o-book-open')
                    ->badge(fn (Get $get, ?Model $record): string => (string) self::analysis($ctx, $get, $locale, $record)['readability']['score'])
                    ->badgeColor(fn (Get $get, ?Model $record): string => self::color(self::analysis($ctx, $get, $locale, $record)['readability']['score']))
                    ->schema([
                        Placeholder::make("seo_readability_{$locale}")
                            ->hiddenLabel()
                            ->content(fn (Get $get, ?Model $record): HtmlString => self::panel(
                                self::analysis($ctx, $get, $locale, $record)['readability'],
                                'تحلیل خوانایی (Readability analysis)',
                            )),
                    ]),

                Tabs\Tab::make('شبکه‌های اجتماعی (Social)')
                    ->icon('heroicon-o-share')
                    ->schema(self::socialTab($ctx, $locale)),

                Tabs\Tab::make('اسکیما (Schema)')
                    ->icon('heroicon-o-code-bracket')
                    ->schema(self::schemaTab($ctx, $locale)),

                Tabs\Tab::make('پیشرفته (Advanced)')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema(self::advancedTab($locale)),
            ]);
    }

    /** @return list<Component> */
    private static function seoTab(SeoContext $ctx, string $locale): array
    {
        $p = "seo.{$locale}";
        $required = $locale === config('locales.fallback');

        return [
            Placeholder::make("seo_serp_{$locale}")
                ->label('پیش‌نمایش گوگل (Google preview)')
                ->content(fn (Get $get): HtmlString => new HtmlString(view('filament.seo.serp-preview', [
                    'title' => SeoTitle::render($get("{$p}.title"), (string) $get("translations.{$locale}.{$ctx->titleField}"), $locale),
                    'description' => (string) ($get("{$p}.description") ?: ($ctx->descriptionFallback ? strip_tags((string) $get("translations.{$locale}.{$ctx->descriptionFallback}")) : '')),
                    'url' => self::previewUrl($ctx, $get, $locale),
                    'dir' => config("locales.supported.{$locale}.direction"),
                ])->render())),

            TextInput::make("{$p}.title")
                ->label('عنوان سئو (SEO title)')
                ->placeholder(SeoTitle::DEFAULT_TEMPLATE)
                ->helperText('متغیرها (Variables): %title% · %sep% · %sitename% · %tagline% · %page% — خالی بگذارید تا از قالب پیش‌فرض استفاده شود.')
                ->maxLength(250)
                ->live(debounce: 500),

            ...($ctx->slugField === null ? [] : [
                TextInput::make("translations.{$locale}.{$ctx->slugField}")
                    ->label('نامک (Slug)')
                    ->prefix(self::urlPrefix($ctx, $locale))
                    ->required($required)
                    ->maxLength(250)
                    ->live(onBlur: true)
                    ->dehydrateStateUsing(fn (?string $state): ?string => $state === null ? null : self::cleanSlug($state))
                    ->rule(fn (?Model $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($ctx, $locale, $record): void {
                        if ($ctx->slugTable === null || blank($value)) {
                            return;
                        }
                        $taken = DB::table($ctx->slugTable)
                            ->where('locale', $locale)
                            ->where($ctx->slugField, self::cleanSlug((string) $value))
                            ->when($record?->exists, fn ($q) => $q->where($ctx->slugForeignKey, '!=', $record->getKey()))
                            ->exists();
                        if ($taken) {
                            $fail('این نامک (slug) قبلاً برای محتوای دیگری استفاده شده است.');
                        }
                    })
                    ->helperText('حروف فارسی مجاز است؛ فاصله‌ها به خط تیره تبدیل می‌شوند.'),
            ]),

            Textarea::make("{$p}.description")
                ->label('توضیحات متا (Meta description)')
                ->rows(3)
                ->maxLength(500)
                ->live(debounce: 500)
                ->helperText('۱۲۰ تا ۱۵۶ نویسه توصیه می‌شود.'),

            Grid::make(2)->schema([
                TextInput::make("{$p}.focus_keyword")
                    ->label('کلمه کلیدی کانونی (Focus keyphrase)')
                    ->maxLength(191)
                    ->live(debounce: 500),

                TagsInput::make("{$p}.secondary_keywords")
                    ->label('کلمات کلیدی مرتبط (Related keyphrases)')
                    ->placeholder('افزودن و Enter')
                    ->live(),
            ]),

            Toggle::make("{$p}.cornerstone")
                ->label('محتوای ستون (Cornerstone content)')
                ->helperText('مهم‌ترین و جامع‌ترین محتوای سایت؛ معیار طول متن سخت‌گیرانه‌تر می‌شود.')
                ->live(),

            Placeholder::make("seo_analysis_{$locale}")
                ->hiddenLabel()
                ->content(fn (Get $get, ?Model $record): HtmlString => self::panel(
                    self::analysis($ctx, $get, $locale, $record)['seo'],
                    'تحلیل سئو (SEO analysis)',
                )),
        ];
    }

    /** @return list<Component> */
    private static function socialTab(SeoContext $ctx, string $locale): array
    {
        $p = "seo.{$locale}";

        $image = fn (string $name, string $label): FileUpload => FileUpload::make("{$p}.{$name}")
            ->label($label)
            ->image()
            ->imageEditor()
            ->disk('public')
            ->directory('seo')
            ->maxSize(5 * 1024)
            ->live()
            ->helperText(fn (mixed $state): HtmlString => new HtmlString(
                e(OgImageCheck::warning(...OgImageCheck::dimensions(self::uploadPath($state))) ?? 'ابعاد پیشنهادی: ۱۲۰۰×۶۳۰ پیکسل.')
            ));

        $preview = fn (string $network): Placeholder => Placeholder::make("seo_{$network}_preview_{$locale}")
            ->label('پیش‌نمایش (Preview)')
            ->content(fn (Get $get): HtmlString => new HtmlString(view('filament.seo.social-preview', [
                'network' => $network,
                'title' => $get("{$p}.{$network}_title") ?: ($network === 'twitter' ? $get("{$p}.og_title") : null)
                    ?: SeoTitle::render($get("{$p}.title"), (string) $get("translations.{$locale}.{$ctx->titleField}"), $locale),
                'description' => $get("{$p}.{$network}_description") ?: ($network === 'twitter' ? $get("{$p}.og_description") : null)
                    ?: $get("{$p}.description") ?: '',
                'image' => self::uploadUrl($get("{$p}.{$network}_image") ?: ($network === 'twitter' ? $get("{$p}.og_image") : null)),
                'host' => parse_url((string) config('app.url'), PHP_URL_HOST),
                'card' => $get("{$p}.twitter_card") ?: 'summary_large_image',
                'dir' => config("locales.supported.{$locale}.direction"),
            ])->render()));

        return [
            Fieldset::make('فیسبوک / اوپن گراف (Facebook / Open Graph)')->columns(1)->schema([
                $preview('og'),
                TextInput::make("{$p}.og_title")->label('عنوان فیسبوک (Facebook title)')->maxLength(250)->live(debounce: 500),
                Textarea::make("{$p}.og_description")->label('توضیحات فیسبوک (Facebook description)')->rows(2)->maxLength(500)->live(debounce: 500),
                $image('og_image', 'تصویر فیسبوک (Facebook image)'),
                Placeholder::make("seo_messenger_preview_{$locale}")
                    ->label('پیش‌نمایش لینکدین / تلگرام / واتس‌اپ')
                    ->content(fn (Get $get): HtmlString => new HtmlString(view('filament.seo.messenger-preview', [
                        'title' => $get("{$p}.og_title")
                            ?: SeoTitle::render($get("{$p}.title"), (string) $get("translations.{$locale}.{$ctx->titleField}"), $locale),
                        'description' => $get("{$p}.og_description") ?: $get("{$p}.description") ?: '',
                        'image' => self::uploadUrl($get("{$p}.og_image")),
                        'host' => parse_url((string) config('app.url'), PHP_URL_HOST),
                        'dir' => config("locales.supported.{$locale}.direction"),
                    ])->render())),
                Select::make("{$p}.og_type")->label('نوع (og:type)')->native(false)
                    ->placeholder($ctx->isArticle ? 'article (پیش‌فرض)' : 'website (پیش‌فرض)')
                    ->options(['website' => 'website', 'article' => 'article', 'profile' => 'profile']),
            ]),
            Fieldset::make('توییتر / ایکس (X / Twitter)')->columns(1)->schema([
                $preview('twitter'),
                TextInput::make("{$p}.twitter_title")->label('عنوان توییتر (X title)')->maxLength(250)->live(debounce: 500)
                    ->helperText('خالی = عنوان فیسبوک'),
                Textarea::make("{$p}.twitter_description")->label('توضیحات توییتر (X description)')->rows(2)->maxLength(500)->live(debounce: 500),
                $image('twitter_image', 'تصویر توییتر (X image)'),
                Select::make("{$p}.twitter_card")->label('نوع کارت (Card type)')->native(false)->live()
                    ->placeholder('summary_large_image (پیش‌فرض)')
                    ->options(['summary_large_image' => 'تصویر بزرگ (summary_large_image)', 'summary' => 'خلاصه (summary)']),
            ]),
        ];
    }

    /** @return list<Component> */
    private static function schemaTab(SeoContext $ctx, string $locale): array
    {
        $p = "seo.{$locale}";

        return [
            Select::make("{$p}.schema_type")
                ->label('نوع صفحه (Page type)')
                ->native(false)
                ->placeholder("پیش‌فرض ({$ctx->defaultPageType})")
                ->options(SchemaPageType::options())
                ->live(),

            ...($ctx->isArticle ? [
                Select::make("{$p}.article_type")
                    ->label('نوع مقاله (Article type)')
                    ->native(false)
                    ->placeholder('پیش‌فرض (BlogPosting)')
                    ->options(SchemaArticleType::options())
                    ->live(),
            ] : []),

            Fieldset::make('فیلدهای اسکیما (Schema fields)')
                ->columns(2)
                ->schema([
                    TextInput::make("{$p}.schema_data.headline")->label('عنوان (headline)')->maxLength(110)
                        ->visible($ctx->isArticle),
                    TextInput::make("{$p}.schema_data.service_type")->label('نوع خدمت (serviceType)')
                        ->visible(fn (Get $get): bool => $ctx->defaultPageType === 'Service' || $get("{$p}.schema_type") === 'Service'),
                    TextInput::make("{$p}.schema_data.area_served")->label('محدوده خدمت (areaServed)')
                        ->placeholder('ایران')
                        ->visible(fn (Get $get): bool => $ctx->defaultPageType === 'Service' || $get("{$p}.schema_type") === 'Service'),
                    TextInput::make("{$p}.schema_data.about")->label('موضوع (about)'),
                    TextInput::make("{$p}.schema_data.keywords")->label('کلمات کلیدی (keywords)')
                        ->helperText('خالی = کلمه کلیدی کانونی + مرتبط'),
                ]),

            Textarea::make("{$p}.custom_schema")
                ->label('اسکیمای سفارشی (Custom JSON-LD)')
                ->rows(8)
                ->extraInputAttributes(['dir' => 'ltr', 'style' => 'font-family: ui-monospace, monospace'])
                ->helperText('یک شیء یا آرایه JSON-LD؛ به @graph صفحه اضافه می‌شود. تگ <script> ننویسید.')
                ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                    if (blank($value)) {
                        return;
                    }
                    $decoded = json_decode((string) $value, true);
                    if (! is_array($decoded)) {
                        $fail('JSON نامعتبر است: '.json_last_error_msg());
                    }
                }),
        ];
    }

    /** @return list<Component> */
    private static function advancedTab(string $locale): array
    {
        $p = "seo.{$locale}";

        return [
            Grid::make(2)->schema([
                Select::make("{$p}.robots_index")
                    ->label('نمایش در نتایج جستجو؟ (Allow search engines to show this in search results?)')
                    ->native(false)
                    ->placeholder('پیش‌فرض نوع محتوا (Default)')
                    ->options([1 => 'بله — index', 0 => 'خیر — noindex']),
                Select::make("{$p}.robots_follow")
                    ->label('دنبال کردن لینک‌ها؟ (Should search engines follow links?)')
                    ->native(false)
                    ->placeholder('پیش‌فرض (follow)')
                    ->options([1 => 'بله — follow', 0 => 'خیر — nofollow']),
            ]),

            CheckboxList::make("{$p}.robots_advanced.flags")
                ->label('متای روبات پیشرفته (Meta robots advanced)')
                ->columns(3)
                ->options([
                    'noarchive' => 'بدون آرشیو (No Archive)',
                    'nosnippet' => 'بدون اسنیپت (No Snippet)',
                    'noimageindex' => 'بدون ایندکس تصویر (No Image Index)',
                ]),

            Grid::make(3)->schema([
                TextInput::make("{$p}.robots_advanced.max_snippet")->label('max-snippet')->numeric()->minValue(-1)
                    ->placeholder('-1'),
                Select::make("{$p}.robots_advanced.max_image_preview")->label('max-image-preview')->native(false)
                    ->placeholder('large')
                    ->options(['none' => 'none', 'standard' => 'standard', 'large' => 'large']),
                TextInput::make("{$p}.robots_advanced.max_video_preview")->label('max-video-preview')->numeric()->minValue(-1)
                    ->placeholder('-1'),
            ]),

            TextInput::make("{$p}.canonical_url")
                ->label('آدرس کنونیکال (Canonical URL)')
                ->url()
                ->maxLength(500)
                ->extraInputAttributes(['dir' => 'ltr'])
                ->helperText('خالی = همین صفحه (self-referencing).'),

            TextInput::make("{$p}.breadcrumb_title")
                ->label('عنوان بردکرامب (Breadcrumbs title)')
                ->maxLength(191),

            Fieldset::make('نقشه سایت (Sitemap)')->columns(3)->schema([
                Toggle::make("{$p}.exclude_from_sitemap")->label('حذف از نقشه سایت (Exclude from sitemap)'),
                Select::make("{$p}.sitemap_priority")->label('اولویت (Priority)')->native(false)->placeholder('خودکار')
                    ->options(collect(range(10, 1))->mapWithKeys(fn (int $i): array => [(string) ($i / 10) => number_format($i / 10, 1)])->all()),
                Select::make("{$p}.sitemap_changefreq")->label('تناوب تغییر (Change frequency)')->native(false)->placeholder('خودکار')
                    ->options(array_combine($f = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'], $f)),
            ]),
        ];
    }

    // ── helpers ─────────────────────────────────────────────────────────

    /** Memoised per request+state so badge, colour and panel share one run. */
    private static function analysis(SeoContext $ctx, Get $get, string $locale, ?Model $record): array
    {
        static $memo = [];

        $translation = (array) $get("translations.{$locale}");
        $seo = (array) $get("seo.{$locale}");
        $key = md5(serialize([$translation, $seo, $locale, $record?->getKey()]));

        return $memo[$key] ??= SeoAnalyzer::analyze($ctx->analysisInput($translation, $seo, $locale, $record));
    }

    private static function panel(array $result, string $heading): HtmlString
    {
        return new HtmlString(view('filament.seo.analysis-panel', [
            'heading' => $heading,
            'score' => $result['score'],
            'checks' => $result['checks'],
        ])->render());
    }

    public static function color(?int $score): string
    {
        return match (SeoMeta::rating($score)) {
            'good' => 'success',
            'ok' => 'warning',
            'bad' => 'danger',
            default => 'gray',
        };
    }

    private static function urlPrefix(SeoContext $ctx, string $locale): string
    {
        $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'sizdahmarketing.com';

        return $host.'/'.$locale.'/'.($ctx->pathPrefix ? trim($ctx->pathPrefix, '/').'/' : '');
    }

    private static function previewUrl(SeoContext $ctx, Get $get, string $locale): string
    {
        $slug = $ctx->slugField ? (string) $get("translations.{$locale}.{$ctx->slugField}") : '';

        return rtrim(self::urlPrefix($ctx, $locale).$slug, '/');
    }

    /** Persian-safe slug: keep letters/digits of any script, hyphenate the rest. */
    public static function cleanSlug(string $value): string
    {
        $value = strtr(trim($value), ['ي' => 'ی', 'ك' => 'ک', "\u{200C}" => '-']);
        $value = preg_replace('/[^\p{L}\p{N}]+/u', '-', $value) ?? $value;

        return mb_strtolower(trim($value, '-'));
    }

    private static function uploadPath(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = reset($state);
        }
        if ($state instanceof TemporaryUploadedFile) {
            return $state->getRealPath() ?: null;
        }

        return is_string($state) && $state !== '' ? Storage::disk('public')->path($state) : null;
    }

    private static function uploadUrl(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = reset($state);
        }
        if ($state instanceof TemporaryUploadedFile) {
            return $state->isPreviewable() ? $state->temporaryUrl() : null;
        }

        return is_string($state) && $state !== '' ? Storage::disk('public')->url($state) : null;
    }
}
