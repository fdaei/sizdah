# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Sizdah Marketing — a marketing website built on Laravel 12 · Inertia 2 · Vue 3
(TypeScript, strict) · Tailwind 3 · Vite 6, with Filament 3 as the admin
panel. The Vue/Tailwind layer is a from-scratch rebuild of a Figma design
(`SahraMarketing` file); the Laravel/data layer was carried over from an
earlier, backend-compatible project ("Sahra") and is considered stable — see
"Figma fidelity work" below before touching frontend markup, spacing, or
color values.

Dependencies are not installed in a fresh checkout (`vendor/` and
`node_modules/` are gitignored and absent) — run `composer install` and
`npm install` before anything else.

## Commands

```bash
composer install
npm install

php artisan migrate
php artisan db:seed
php artisan storage:link
```

Run locally (server + queue listener + Vite together):

```bash
composer run dev
```

Testing:

```bash
php artisan test                          # full Pest suite
php artisan test --filter=LocaleRouting   # single test/class by name
npm run typecheck                         # vue-tsc --noEmit, strict
npm run lint                              # eslint, resources/js only
npm run format                            # prettier --write resources/js
npm run test:e2e                          # Playwright; requires the app
                                           # running (BASE_URL env, default
                                           # http://localhost:8000)
```

Pest tests run against an in-memory SQLite DB (`phpunit.xml`), so they need
no local database — but they do need the `pdo_sqlite` PHP extension.

Asset verification (checks the 44-item manifest in `docs/ASSET-MANIFEST.md`
is actually present on disk):

```bash
php artisan sizdah:verify-assets
```

## Locale status — read before touching anything locale-related

The site is **Persian-only in practice**, even though the multilingual
machinery (translation tables, `HasTranslations`, the `{locale}` route
prefix, `lang/en` and `lang/ar`) is fully intact and untouched.
[config/locales.php](config/locales.php) is the single source of truth:
only `fa` is listed under `supported` (English and Arabic sit under
`inactive`, same shape, ready to be moved back). This was a deliberate user
decision (2026-08-21) because the Figma file itself contains no English or
Arabic frame. Consequences:

- `/en/*` and `/ar/*` no longer resolve; the language switcher collapses to
  nothing.
- README.md and `docs/` still describe the site as EN/FA/AR multilingual —
  that description predates the fa-only decision and is stale.
- The Filament **admin panel UI** locale is separate from public content
  locales — see `locales.admin` (`en`, `fa`) in the same config file.
- Re-enabling a locale is a one-line move: cut its block from `inactive`
  back into `supported`. No other code changes needed.

## Documentation layout — several overlapping doc trees, different ages

Multiple design-audit doc trees exist from different points in the project's
history; they are **not** interchangeable and some are known stale:

- `docs/` (FIGMA-AUDIT.md, TRACEABILITY.md, ASSET-MANIFEST.md, RESPONSIVE-QA.md,
  IMPLEMENTATION-LOG.md) — the original audit. Describes an older, two-page
  version of the Figma file with node IDs that no longer exist in the live
  file. `IMPLEMENTATION-LOG.md`'s "known limitations" (e.g. "Vue pages not
  yet written") are also from that earlier snapshot — the Vue pages in
  `resources/js/Pages/` now exist. Treat both as historical background, not
  current fact; verify any specific claim against the code before relying on it.
- `AUDIT/figma-fidelity/` — a separate, earlier fidelity audit pass, also
  keyed to the old node-ID generation.
- `.figma-sync/` (MANIFEST.md, GAPS.md, PROGRESS.md, ASSETS.md) — the
  **current, actively-maintained** record of the Figma rebuild. `PROGRESS.md`
  has the running log and "next unit of work"; `GAPS.md` logs every
  ambiguous/inferred design decision with its resolution. Check this tree
  first for anything Figma-related.
- `FIGMA/state.json` — cached state from the Figma MCP integration.

`routes/web.php` and `config/locales.php` both carry inline comments flagging
exactly which downstream docs they've made stale — read those comments when
in doubt about which source to trust.

## Architecture

**Locale-prefixed routing.** Every public route lives under
`Route::prefix('{locale}')` in [routes/web.php](routes/web.php), constrained
to `config('locales.supported')`. `SetLocale` middleware resolves the
segment and calls `URL::defaults()`, so `route('work.show', $project)` stays
in the current locale without passing it explicitly — route names themselves
are locale-agnostic (`home`, `work.show`, ...). A catch-all `locale.redirect`
group (registered last, so it never shadows a real route) sends bare paths
like `/about` to `/{default-locale}/about`.

**Translation tables.** Every translatable entity (`Post`, `Project`, `Page`,
`Service`, ...) has a sibling `{entity}_translations` table keyed on
`(entity_id, locale)`. [app/Traits/HasTranslations.php](app/Traits/HasTranslations.php)
makes the translated columns readable as if native (`$post->title`), with
locale fallback baked in; the consuming model just declares
`protected array $translatable` and a `translationModel()` method. Always
eager-load via the model's `withTranslations()` / `translation()` scopes in
list/show queries to avoid N+1. `HasLocalisedSlugs` is implemented by models
whose slug differs per locale (`Post`, `Project`, `Page`), so
`LocaleAlternates` can build correct hreflang URLs instead of reusing one
slug across languages.

**Filament translation UX.** [app/Filament/Support/TranslatableForm.php](app/Filament/Support/TranslatableForm.php)
builds one tab per supported locale (`TranslatableForm::tabs(...)`) with
fields named `translations.{locale}.{attribute}`; the
[HandlesTranslations](app/Filament/Concerns/HandlesTranslations.php) concern
(mixed into each resource's Create/Edit pages) splits that nested array off
the form payload, saves the parent record, then persists translations
through `HasTranslations::setTranslations()` — all inside one DB transaction.

**Publishable lifecycle.** [app/Traits/Publishable.php](app/Traits/Publishable.php)
gives content models `draft` / `scheduled` / `published` states via a
`status` enum column + nullable `published_at`. The `published()` scope
double-checks the timestamp (not just status) so a missed cron run can never
leak future content. `php artisan sizdah:publish-scheduled` (see
`routes/console.php`, run every minute via the scheduler) promotes due rows.

**Page composition via sections.** Pages aren't monolithic templates — a
`Page`/`Project` owns ordered `PageSection` rows, each tagged with a
`SectionType` enum case (`hero`, `kpi`, `why_us`, ...) that maps 1:1 to a
Figma section and a Vue component; sections whose type "has items"
(`SectionType::hasItems()`) additionally own ordered `SectionItem` child
rows. Adding a new `SectionType` case without a matching Vue component is a
silent no-op, not a crash — check the frontend switch when adding cases.

**PHP ↔ TypeScript contract.** [app/Services/ContentTransformer.php](app/Services/ContentTransformer.php)
is the single place that maps Eloquent models onto the DTO shapes declared in
`resources/js/types/index.ts`. It assumes translations are already
eager-loaded by the caller's query scope and never triggers a query itself.
Keeping both sides in one class is deliberate — a field added to a TS type
without a matching transformer method should surface as an obvious gap here.

**Cached site settings.** [app/Support/SiteSettings.php](app/Support/SiteSettings.php)
is a static cached accessor over the key/value `Setting` model and
`SocialLink`, edited via the Filament `ManageSettings` page. Cache is flushed
by the models' saved/deleted events, not by TTL (TTL is a 24h backstop).
Settings can be either plain values or per-locale maps (`{"fa": "...", ...}`)
— `SiteSettings::get()` unwraps both shapes transparently.

**Design tokens.** [tailwind.config.js](tailwind.config.js) values are
extracted verbatim from Figma variables (brand/ink color ramps, a
1.25-ratio type scale, `xs`–`2xl` breakpoints) — the file's own comments say
not to hand-edit a value without re-reading the source variable, and note
that the Figma file ships desktop (1440px) frames only, so tablet/mobile
behavior below `lg` is derived, not designed (cross-reference
`.figma-sync/GAPS.md` and `docs/RESPONSIVE-QA.md` for which decisions were
made and why). `tailwindcss-logical` is used for RTL-safe logical properties
(`ms-*`/`me-*` over `ml-*`/`mr-*`, etc.) rather than manual `dir`-based
overrides.

**GSAP lifecycle.** [resources/js/lib/motion.ts](resources/js/lib/motion.ts)
centralizes all animation timing/easing (nothing else should define these).
Because Inertia swaps page components without a full reload, ScrollTrigger
instances from the previous page are explicitly torn down on the `before`
navigation event and rebuilt on `navigate` — a naive GSAP setup would leak
triggers across Inertia page visits. Elements are opted in via
`data-reveal` / `data-reveal-group` attributes; the pre-animation hidden
state lives in CSS (`html.motion-ready` in `app.css`) so a JS failure leaves
content visible rather than stuck at `opacity: 0`. Respects
`prefers-reduced-motion`.

## Notes on tooling

- `npm run lint` was broken for a long time (the flat ESLint config it
  referenced was never committed) — it now works
  ([eslint.config.js](eslint.config.js) exists and is scoped to
  `resources/js` only). Three formatting-only rules are deliberately
  disabled because they only disagreed with Prettier's line-break choices,
  not real defects — see the comments in that file before re-enabling them.
- `vue/multi-word-component-names` is off on purpose: pages/components are
  named by route (`Home.vue`, `About.vue`, `Contact.vue`).
- `v-html` is used in three places for admin-authored rich text (Legal,
  About's story, article bodies), each with an inline `eslint-disable`
  justifying it.
