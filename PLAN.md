# PLAN — Native SEO / CMS feature parity (Rank Math · Yoast · Schema Pro · WP Rocket · TOC Plus)

Status: **draft, awaiting approval** (2026-11-17). Based on a step-zero survey of the repo.

## 0. What already exists (do NOT rebuild)

| Requested feature | Already in repo |
|---|---|
| Settings system + cache invalidation on save | `Setting` + `SiteSettings` (per-locale maps, flushed on saved/deleted) + `ManageSettings` page |
| Multi-level menus | `Menu` / `MenuItem` + `MenuResource` (items relation manager), `NavSubmenu.vue`, `MobileNavTree.vue` |
| Section-based pages | `Page` → `PageSection` (`SectionType`, 24 cases) → `SectionItem` |
| Polymorphic SEO meta | `seo_meta` / `SeoMeta`: focus + secondary keywords, title, description, canonical, robots (+advanced), OG, Twitter, schema type/data, custom schema, sitemap flags, scores |
| SERP preview | `resources/views/filament/seo/serp-preview.blade.php` via `SeoSection` |
| Content analysis | `SeoAnalyzer` (416 lines), `SectionSeoAnalyzer` |
| Global SEO settings, title template, robots.txt editor | `ManageSeoSettings` (robots tab, `SeoTitle`) |
| 404 monitor | `NotFoundLog` + `NotFoundLogResource` |
| Redirect manager + slug auto-redirect | `Redirect`, `RedirectResolver`, `SlugRedirector`, `ApplyRedirects` |
| Sitemap | `SitemapBuilder`, `SitemapController`, `ManageSitemap` |
| Schema | `SeoBuilder` + `SchemaNodes` (FAQPage, Person, Review, Video…) |
| FAQ | `Faq` model + `FaqAccordion.vue` + `faq` section type |
| Revisions, IndexNow, image optimisation | `ContentRevision`, `IndexNow`, `ImageOptimizer` |

Locale: site is **fa-only by decision** (`config/locales.php`); everything new stays translation-table-ready but is only exercised in `fa`.

## 1. Gaps — the actual work

### Phase 1 — CMS completeness
1. **Audit hardcoded copy.** Persian literals remain in `Pages/Work/Show.vue` (25), `ProjectShowcase.vue` (12), `LeadMagnetModal.vue` (12), `Home.vue` (11), `SmartText`, `ClientLogo`, `TestimonialCarousel`, `SectionHeading`; plus `lang/fa/*.php` UI strings (footer, home, common, errors, forms). Move content-like copy (headings, CTAs, footer text, error-page copy) into settings groups; keep true UI micro-labels (aria, validation) in `lang/` — editing those in a CMS is an anti-pattern.
2. **Settings groups** — extend `ManageSettings` with tabs: Header, Footer (columns repeater, copyright, bottom links), Buttons/CTAs (text, link, style, new-tab, enabled), Error pages. Typed values (text/textarea/rich/image/link/bool/json/color).
3. **Shared accessors** — `setting()` PHP helper over `SiteSettings`; `useSetting()` composable over an Inertia-shared `settings` prop.
4. **Seeder/migration** that writes current literal values, so the site renders identically after migrate (verified by snapshot test of rendered Inertia props before/after).
5. **Page builder**: keep `PageSection` model (Figma-mapped, stable). Add generic blocks only where missing: `gallery`, generic `features`, generic `cta`. No switch to Filament `Builder` JSON — it would orphan the section/translation tables.

### Phase 2 — SEO gaps
- SERP preview: verify pixel-width truncation + mobile/desktop toggle + RTL; add if missing.
- OG: 1200×630 dimension warning, social card previews (LinkedIn/Telegram/WhatsApp), confirm fallback chain in `SeoBuilder`.
- Analyzer: verify Persian normalisation (ی/ي, ک/ك, ZWNJ, diacritics) with unit tests.
- 404 monitor: bot detection + "create redirect" row action (check existing).
- Redirects: regex + 410 + loop guard (check existing).
- Google/Bing verification codes (check `ManageSeoSettings`).

### Phase 3 — Schema gaps
- Organization/LocalBusiness, WebSite+SearchAction, BreadcrumbList, Article, Service — audit `SeoBuilder` and fill missing nodes.
- **New: Review & Rating** — `service_ratings` table (service_id, stars, ip_hash, fingerprint, approved_at), throttled POST endpoint, one-vote-per-hash, Filament moderation resource, `AggregateRating` only from approved rows.
- Per-page schema disable/override (`custom_schema` exists; add an explicit off switch).
- Pest tests asserting JSON-LD structure per page type.

### Phase 4 — Performance (all new)
- `MinifyHtml` middleware, toggle in settings, skips `pre`/`textarea`/`script`/`style`.
- Image component: width/height, `loading="lazy"`, `fetchpriority="high"` for LCP prop; video facade component; map lazy via IntersectionObserver.
- Preload/preconnect/prefetch lists managed in settings, rendered in `app.blade.php`.
- Full-page response cache for guest GETs (custom middleware + cache tag), invalidated by model saved events + settings flush; "Clear cache" Filament action.
- WebP/AVIF + srcset — extend existing `ImageOptimizer`.

### Phase 5 — Content (all new)
- `TableOfContents` service: parse H2/H3 from rendered body, Persian-safe unique ids, injected server-side; `TocBox.vue` collapsible; per-post toggle + global min-headings/levels.
- `internal_links` table (keyword, url, locale, max_per_page, enabled) + `AutoLinker` DOM pass at render time, cached by content hash; skips headings, `a`, `code`, self-links; ZWNJ/case-insensitive.

### Cross-cutting
- Filament nav groups regrouped: محتوا / سئو / ظاهر سایت / عملکرد / تنظیمات.
- Feature tests per phase; `php artisan test`, `npm run typecheck`, `npm run lint` green after each.
- `SEO_FEATURES.md` at the end: where each feature lives in the panel and how to use it.

## 2. Order & commits
Phase 1 → 5, one branch (`feat/native-seo-cms`), one commit per phase after tests pass.

## 3. Blockers before starting
- **Working tree has ~53 uncommitted modified files on `main`** (Filament resources, models, SEO services, sitemap, routes). These overlap heavily with this plan. They must be committed/stashed by the owner first, otherwise per-phase commits would mix that work in.
