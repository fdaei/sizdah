# Sizdah — Implementation Progress

Single-page status of the Figma → production rebuild. **This file is the
index, not the record.** The working record is `.figma-sync/`:

| File | Holds |
| --- | --- |
| `.figma-sync/MANIFEST.md` | every frame in the file, with node IDs |
| `.figma-sync/GAPS.md` | G1–G46 — every inferred, ambiguous or wrong thing, and how it was resolved |
| `.figma-sync/PROGRESS.md` | the running log and the next unit of work |
| `.figma-sync/ASSETS.md` | every exported asset, with its source node |

The trees under `docs/` (FIGMA-AUDIT, TRACEABILITY, RESPONSIVE-QA,
IMPLEMENTATION-LOG) and `AUDIT/figma-fidelity/` are **stale**: they describe a
two-page version of the Figma file whose node IDs no longer exist (G1). Treat
them as history. `routes/web.php` and `config/locales.php` carry inline
comments naming exactly which docs they have made stale.

- **Figma file:** `v1l4ANft5Wtb8wPThyP7P9`, page `0:1` "User Interface"
- **Locale:** Persian only (G15). `/en/*` and `/ar/*` do not resolve; the
  translation tables, `HasTranslations`, the `{locale}` prefix and `lang/{en,ar}`
  are all deliberately intact, and restoring a locale is a one-line move in
  `config/locales.php`.
- **Last updated:** 2026-08-30

---

## Page frames

Every page frame in the file is 1440-wide desktop. There are **no mobile or
tablet frames anywhere** (G3), so everything below `lg` is derived, not
designed — which is why the "Responsive QA" column is a separate judgement from
"Visual QA".

| # | Figma frame | Node | Route | Backend | Build | Visual QA (1440) | Responsive QA |
| --- | --- | --- | --- | --- | --- | --- | --- |
| 1 | Home | `268:2962` | `/{locale}` | `HomeController` → `Page` + sections | done | measured, section by section (G32) | **clean 320→1920** (G46 fixed 2) |
| 2 | projects (Work index) | `222:1989` | `/{locale}/work` | `ProjectController@index` | done | structure verified (G20) | clean 320→1920 |
| 3 | case study (Work show) | `336:5374` | `/{locale}/work/{slug}` | `ProjectController@show` | done | card treatments measured (G39) | not swept — needs a seeded slug |
| 4 | services | `308:4492` | `/{locale}/services` | `ServiceController` | done | **fully re-measured (G44)** — 9 defects | clean 320→1920 |
| 5 | about | `336:5623` | `/{locale}/about` | `AboutController` | done | rebuilt end to end (G19) | clean 320→1920 |
| 6 | blog list (Insights index) | `268:4158` | `/{locale}/insights` | `PostController@index` | done | structure verified | clean 320→1920 |
| 6 | blog list (Insights show) | `285:4590` | `/{locale}/insights/{slug}` | `PostController@show` | done | prose/meta chips not value-checked | not swept — needs a seeded slug |
| 7 | contact us | `279:6325` | `/{locale}/contact` | `ContactController` + throttle | done | details card measured (G36), grid mirrored (G40) | **clean 320→1920** (G46 fixed) |
| 8 | privacy policy | `279:5924` | `/{locale}/privacy-policy` | `LegalController@privacy` | done | not value-checked | clean 320→1920 |
| 9 | term of use | `281:6773` | `/{locale}/terms` | `LegalController@terms` | done | not value-checked | clean 320→1920 |
| 10 | 404 (Persian, dark) | `266:2825` | fallback | — | done | not value-checked | clean 320→1920 |

**Superseded frames — no work owed.** Home `13:2766` / `504:7399` / `450:4908`
(light twin + two dark variants, G2); projects `261:2545` (light twin, still on
the old English card) and `226:3080` (a short state, not a page); 404
`82:2059` / `88:2490` (Latin and light twins — the one named "405" is the
Persian 404's light twin, so there was never a 405 to build, G20).
`416:4959` / `416:5834` remain unresolved 1024h states (G2).

**Off-canvas, not product UI** (G28): `411:7564`, `149:2691`, `299:7811`,
`615:6178`, `537:5561`, `598:5751` — mood boards, sprite sheets and discarded
drafts.

## Shared components

Mined from the component sheet `21:2152` in G25, which until then had never
been read even though every page was marked "verified" against it — every
shared component carried at least one defect.

| Component | Figma | Status |
| --- | --- | --- |
| `AppHeader` | `20:2087` | done (G25) |
| `AppFooter` | in every frame | done (G25) — the giant outlined wordmark is not shipped (G24) |
| `CtaButton` | `21:2164` | done (G25) — 57px min-height was the widest-reaching miss |
| `Eyebrow` | `450:66xx` | **done (G45)** — rim was generalised from the one dissenting instance |
| `SectionHeading` | 7 instances | **done (G45)** — 48px gap on 2 of 7 |
| `StatCard` | `71:2145` | done (G25) |
| `BlogCard` | `276:5724` | done (G25/G37) |
| `ProcessStepCard` | `537:5510` | done (G25) |
| `TestimonialCard` | `546:7528` | built to measured values; not re-verified |
| `ProjectPostCard` | — | done (G37) — missing shadow |
| `StartTogetherCard` | `577:10889` / `577:9134` | extracted so the two copies cannot drift |
| `SocialIcon` | — | done (G41) — missing YouTube, all five wrong colour |
| `ArticleMeta` / `FilterChips` / `ClientLogo` | various | built to measured values; not re-verified |

## Verification status

| Check | Result |
| --- | --- |
| `npm run typecheck` | passes |
| `npm run lint` | passes |
| `npm run build` | passes |
| `npm run test:e2e` (Chromium) | **137 passed, 0 failed, 3 skipped** |
| No horizontal overflow, 9 routes × 320/360/390/414/768/1024 | clean |
| No page errors / failed requests / 4xx, 8 routes | clean |
| `php artisan test` (Pest) | **cannot run — `pdo_sqlite` absent** |

## Known limitations

These need decisions or privileges this work could not supply — they are not
deferred conveniences.

1. **`pdo_sqlite` is not built into this PHP 8.5 install**, and no `php-sqlite`
   package is available, so the Pest suite (which uses an in-memory SQLite DB
   per `phpunit.xml`) cannot execute at all. Needs root. Pointing `phpunit.xml`
   at the MySQL dev database was rejected: `RefreshDatabase` would drop it.
2. **WebKit system libraries are missing**, so `mobile-390` — the suite's only
   WebKit project — cannot launch, and reads as 12 layout failures. Fix with
   `sudo npx playwright install-deps`. All Chromium projects pass.
3. **Peyda and Maneli are unlicensed** (G8). Figma binds every FA text style to
   Peyda and every display accent to Maneli; both are paid fontiran.com products
   with no redistributable cut. Vazirmatn and the Idealist → Doran FaNum stack
   stand in, by human decision 2026-08-21. Every consumer goes through the
   `fontFamily` tokens, so buying a licence is a drop-in.
4. **No mobile or tablet frames exist** (G3). Everything below `lg` is inferred.
   The suite now guarantees no overflow and no errors down to 320px, but
   "matches the design" is not assertable at those widths because there is no
   design to match.
5. **Work show and Insights show are not in the overflow sweep** — both need a
   seeded slug, so the sweep would break on a content change rather than a code
   change. Worth adding once the sweep derives slugs from the listing pages.
6. **`docs/` and `AUDIT/` are stale** (G1) and should be regenerated or archived.
   Left alone here because deleting an audit trail is a call for a human.
