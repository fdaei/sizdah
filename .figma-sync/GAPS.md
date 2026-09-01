# Figma Sync — Gaps & Decisions Log

Every inferred, ambiguous, or missing piece a human should review. Node IDs use
`:` form. Add entries; never silently invent a design.

## G1 — `docs/` audit is STALE vs. current Figma file  (severity: high)
`docs/FIGMA-AUDIT.md` and `docs/TRACEABILITY.md` describe a two-page file
(`1:2` "User interface" = canonical, `0:1` "Wireframing" = archived) and cite
canonical node IDs `1419:9192` (Home), `541:1558` (Projects), `908:1576`
(About), `447:790` (Contact), etc. The **live file has one page — `0:1`
"User Interface" — and none of those node IDs are top-level frames there.** The
current frames are a different, lower-ID generation (Home = `13:2766` /
`268:2962` / `504:7399` / `450:4908`, etc.). The existing code was built against
the *old* structure. This rebuild targets the *current* frames only; the old
audit's node IDs are not reused. `docs/` should be regenerated or archived at
end of run.

## G2 — Duplicate page frames  [RESOLVED via FIGMA/state.json + live re-read]  (severity: med)
Home canonical = `268:2962` (DARK; `canonicalTheme:dark`); `13:2766` is the
light twin (superseded). blog: `268:4158` = Insights index, `285:4590` =
Insights show (single article, despite both named "blog list"). Remaining
projects/blog 1024h frames still to confirm at Phase 5. Original note below.

### (original) Duplicate page frames; canonical not yet resolved
The single page carries multiple same-named page frames. Canonical pick is
deferred to Phase 5 (resolved by screenshot at build time, cheapest point):
- Home ×4: `13:2766` (11273h), `268:2962` (9919h), `504:7399` (11273h), `450:4908` (11273h)
- projects (Work index) ×2 full: `222:1989`, `261:2545` (both 4656h) + `226:3080` (1762h — likely a section/empty-state, not full page)
- blog list ×2 full: `268:4158` (4277h), `285:4590` (4844h) + `416:4959`, `416:5834` (both 1024h — likely list states, single-blog, or 404-ish)
Selection heuristic (from stale audit, still reasonable): prefer the LTR/English
frame with the most complete section set and bound variables. Verify per page.

## G3 — No mobile page frames in the file  (severity: med)
Every real page frame is 1440-wide (desktop). There are no 402/390-wide mobile
page frames (the stale audit listed a mobile set — those nodes are gone).
Component frames exist at natural sizes but no responsive page frames.
Consequence: mobile/tablet layouts are **inferred mobile-first**, not designed.
TARGET_BREAKPOINTS (left blank in the brief) will follow the existing Tailwind
config once tokens are read; breakpoints used will be logged here per page.

## G4 — Brief placeholders were blank  (severity: low)
PROJECT_ROOT / STACK / LOCALES / TARGET_BREAKPOINTS were empty in the template.
Filled from detection: root `/home/fdaei/project/my/sizdah`; stack per
PROGRESS.md; locales en(LTR)/fa(RTL)/ar(RTL); breakpoints per G3.

## G5 — Single-blog (Insights/Show) source  [RESOLVED]  (severity: med)
Insights/Show = `285:4590` ("blog list" by name, single-article by content, per
FIGMA/state.json). Single-project (Work/Show) = "case study" `336:5374` — NEW,
see G7.

## G7 — NEW frames for About / Work / case study (previously undesigned)  (severity: HIGH — primary rebuild value)
FIGMA/state.json (last sync) records: *"No frames exist for About, Work index,
Work show — those routes have controllers but no Sizdah design."* The current
live file now DOES contain them:
- about `336:5623` (1440×4021)
- projects / Work index `222:1989` and `261:2545` (1440×4656) + `226:3080` (short)
- case study / Work show `336:5374` (1440×7403)
These pages were previously built *blind* (from controller data, no frame). They
are the highest-value targets of this rebuild — real designs now exist. Verify
which projects frame is canonical at Phase 5.

## G8 — FA/AR font: design specifies "Peyda"  [CLOSED 2026-08-21]  (severity: med)
Figma binds every FA text style to **Peyda** and every eyebrow / display accent
to **Maneli**. Self-hosted faces are poppins, vazirmatn, doran, idealist — neither
Peyda nor Maneli.

**Resolved: the substitutes are the intended faces.** Both Peyda and Maneli are
paid fontiran.com products (Peyda 398–698k تومان desktop + a separate web licence
from 350k; Maneli 3,980,000 ریال) and no free/redistributable cut exists — the
only "free" sources are unlicensed re-uploads the publisher asks people to report.
Human decision 2026-08-21: **keep Vazirmatn for FA body text and
Idealist → Doran FaNum → Vazirmatn (the `font-display` token) for eyebrows and
display accents.** Do not reopen this without a purchased licence; if one is
bought, drop the woff2 files into `public/fonts/peyda/` and `public/fonts/maneli/`
and rewire `fontFamily` in tailwind.config.js — every consumer already goes
through the tokens, so nothing else changes.

Consequence handled the same day: `hero-line` / `hero-accent` are weight **600**
and `public/fonts/vazirmatn` shipped 400/500/700 only, so the hero rendered with a
browser-synthesised bold. `Vazirmatn-SemiBold.woff2` (v33.003, matching the cut
already in the repo by checksum; SIL OFL) is now installed and declared in
`resources/css/fonts.css`.

Line-height: raw variable says 100% but laid-out nodes measure ~1.5 — superseded,
see the 1.25 re-measurement in CLAUDE.md.

**Update 2026-09-01:** first licensed Peyda cut supplied — `Peyda-ExtraBold`
(weight 800) is now installed in `public/fonts/peyda/` and declared in
`resources/css/fonts.css`. `fontFamily.arabic`/`.sans` in tailwind.config.js
are still NOT rewired: only this one weight exists, and the body text tokens
need 400/500/600/700 too. Rewire once the remaining Peyda weights (and, if
wanted, Maneli) arrive — see the plan two paragraphs up.

## G6 — i18n: file is PERSIAN-only  [CORRECTED 2026-08-21]  (severity: med)
The original entry below had this exactly backwards. A full-file text scan
(every TEXT node on all three pages, classified by script) shows **no English
and no Arabic variant of any page frame exists**. Every page frame is Persian:

| frame | arabic-script chars | latin chars | latin content |
|---|---|---|---|
| about `336:5623` | 1257 | 251 | footer only |
| Home `268:2962` | 4191 | 460 | footer + logo |
| services `308:4492` | 969 | 251 | footer only |
| projects `222:1989` | 1286 | 251 | footer only |
| case study `336:5374` | 1860 | 290 | footer only |

The Latin counts are **entirely the un-rebranded Sahra footer** — verified on
336:5623, where all 24 Latin-bearing nodes are "Quick Links" / "Social Links" /
"Info" / "Muscat, Oman" / "Sahramarketing@gmail.com" / "© 2026 Sahra. All rights
reserved." — plus the SAHRA logo. Not a localisation. The `ar-only` letter count
is 0-2 across every frame, i.e. no Arabic frame anywhere. The Style page names
its type sheet `Farsi-Typography` (1:232); its only Latin-only frames are the
gold/black/white colour swatch sheets.

Consequence, and it inverts the rebuild's i18n direction: **fa is the source
copy**, transcribed verbatim from node text. en/ar are derived and must be
reviewed by a native speaker — see G10.

### (original, wrong) i18n: file is English-only
Frames render English copy. fa/ar strings are NOT in the design; they come from
`lang/{fa,ar}/*.php` (existing) or must be authored. Rebuild extracts EN strings
to locale keys; fa/ar translation is out of scope of a pixel rebuild and flagged
where a page needs it.


## G9 — The file has THREE pages, not one  (severity: med)
`CLAUDE.md` and PROGRESS.md both state the file is "a single page `0:1` User
Interface". `GET /v1/files/{key}?depth=1` returns three:

- `0:1` "User Interface" — 98 top-level nodes, all the page frames (canonical)
- `1:2` "Style & Component" — swatch sheets (`1:43` gold, `1:99` black, `1:38`
  white) and `1:232` "Farsi-Typography", the type sheet
- `163:1428` "thinking" — 79 nodes of exploration: alternate "چطور فکر می‌کنیم"
  wordings, package-content drafts, loose frames

`1:2` is a genuine token source and should be read before any further token
work; `163:1428` is scratch and must not be built from. Fix the claim in
CLAUDE.md.

## G10 — About en/ar copy is derived, not designed  (severity: med)
`PageSeeder::about()` now carries the frame's Persian copy verbatim (336:5623
text runs). The `en` and `ar` arrays in that method were rendered from the
Persian and have **no design or human source** — they need a native review
before launch. Affects: page title/SEO, about_hero, story, how_we_think (4
items), team, final_cta. The same will be true of every page rebuilt from this
file — see G6.

## G11 — Idealist / Doran were never wired up  [FIXED 2026-08-21]  (severity: med)
`CLAUDE.md` claims Idealist "is wired up in `resources/css/fonts.css` and
consumed via the `.eyebrow` component class", and `Services.vue` documents its
Maneli numerals as falling back to "the display stack". Neither was true:
`fonts.css` carried only Poppins and Vazirmatn, `tailwind.config.js` had no
`display` family, and `.eyebrow` sets no font-family at all — so every eyebrow
in the site has been rendering in the body font.

Fixed additively: `Idealist-Models`, `DoranFaNum-Regular/Medium/Bold` compressed
to woff2 and given `@font-face` blocks (Idealist carries a Latin `unicode-range`
so fa/ar fall through to Doran), plus a `display` fontFamily token.

STILL OPEN, deliberately not changed: `.eyebrow` and `Services.vue`'s numerals
do not yet use `font-display` — switching them changes the appearance of every
eyebrow on every page, which is a visual decision, not a bug fix. About's CTA
eyebrow (577:11228) uses the token explicitly.

## G12 — Final CTA services line never rendered  [FIXED 2026-08-21]  (severity: med)
`PageSeeder::finalCta()` writes the services list ("برندینگ · تولید محتوا · …")
to the section's `subtitle` column; `FinalCtaCard.vue` read `section.content`,
which nothing fills. The line was silently missing from every page using the
shared card (services, article). The component now reads `subtitle`.

## G13 — Git root claim in CLAUDE.md is wrong  (severity: low)
CLAUDE.md warns "The git repository root is `$HOME`, not this directory. Scope
every git command to paths under this project; a bare `git add -A` stages the
user's home dir." `git rev-parse --show-toplevel` returns
`/home/fdaei/project/my/sizdah` and `git ls-files --full-name` reports
project-relative paths. The warning is stale and should be removed.


## G14 — The type scale was set on the wrong line-height  [FIXED 2026-08-21]  (severity: HIGH)
`tailwind.config.js` documented its scale as "1.5 is what the frames are
actually built on, and the section heights depend on it". Re-measuring every
TEXT node on the canonical Home frame `268:2962` (162 nodes) shows the opposite:

```
 12/15  14/17  14/18  16/20  18/22  20/25  22/28  24/30  28/35  32/40   = 1.25
 36/46  40/51  48/61  64/81                                             = 1.27
 14/21  16/24                                                           = 1.5
```

**146 of 162 nodes (90%) sit at 1.25-1.27.** The 16 at 1.5 are all 14px or 16px
running copy. Cross-checked against `336:5623` (about: 18/22, 20/25, 24/30,
22/28, 40/51, 48/61) and `279:5924` (privacy: 30/38, 20/25) — same result.

Every size in the scale was re-pinned to its measured ratio. This is a
site-wide visual change and it tightens copy on pages not yet rebuilt (Home,
Contact, Insights, Services, Work) — they are all scheduled for rebuild from
these same frames, so they converge rather than diverge. 1.5 survives where the
frames really use it: long-form body copy in `.rich-prose`.

Knock-on: `.rich-prose` now matches `279:5924` exactly with no further change,
so Legal needed only a 612px header column.

## G15 — Site is Persian-only  (user decision 2026-08-21)  (severity: n/a — decided)
The user confirmed "کل سایت فقط فارسی هست". Chosen scope: **content fa-only,
i18n infrastructure retained**.

Applied:
- `config/locales.php` — `supported` holds `fa` alone; the `en` and `ar` blocks
  moved verbatim to a new `inactive` key that nothing reads. Restoring a locale
  is a cut-and-paste back into `supported`.
- `default` / `fallback` → `fa`; `config/app.php` and `.env` / `.env.example`
  follow (`APP_LOCALE`, `APP_FALLBACK_LOCALE`).
- `LanguageSwitcher.vue` renders nothing at one locale, so it collapses
  site-wide instead of drawing a single un-switchable option.

Deliberately NOT touched, so this stays reversible: the `{entity}_translations`
tables, `HasTranslations`, the `{locale}` route prefix, `lang/en` and `lang/ar`,
and the Filament translatable tab machinery. Existing en/ar rows in the database
are inert, not deleted.

Consequence for G10: the en/ar copy in `PageSeeder::about()` is parked rather
than pending review. It stays as the starting point if a locale is ever
reactivated, but nothing renders it and it does not block launch.


## G16 — Lead-magnet copy diverges from the frame  [CORRECTED 2026-08-21]  (severity: low)
This entry first claimed the section had been **cut from the design**. That was
wrong, and the mistake was in the search, not the file: the node is
`391:4795` on Home `268:2962` (1036x194) and it was missed because the search
terms were taken from the seeded copy, which the frame rewords.

| | seeded (`PageSeeder::leadMagnet`) | frame `391:4795` |
| --- | --- | --- |
| title | آیا محتوای شما واقعاً جهت‌مند است یا فقط منظم؟ | آیا محتوای شما مسیر مشخصی دارد یا فقط ادامه پیدا می‌کند؟ |
| description | چک‌لیست جهت‌گیری محتوا را دریافت و سیستم برند خود را ارزیابی کنید. | سیستم محتوای برند خود را بررسی کنید و ببینید چقدر با اهداف شما هماهنگ … |
| CTA | دریافت چک‌لیست | شروع گفتگو |

Same intent, different wording — and the CTA changes meaning (download a
checklist vs. start a conversation). The frame is the Persian authority, but
this is authored marketing copy an editor owns, so it was not overwritten
silently.

**Decision 2026-08-21 (user): adopt the frame wording** — "هرچی توی فیگما هست
شبیه فیگما بشه". `PageSeeder::leadMagnet` now carries 391:4798 / 391:4799 and
the CTA label off 391:4796 verbatim, so the button reads «شروع گفتگو» and points
at contact. The checklist framing is gone from the seed; en/ar were re-derived
and stay parked per G15. Re-seed to pick it up — an existing database row keeps
whatever an editor has since typed.

`LeadMagnetBanner.vue` already cites 391:4795 and reads the section generically,
so it renders whichever copy the seeder holds.

## G17 — Tooling gates were never wired  [FIXED 2026-08-21]  (severity: med)
Three of the four frontend gates did not work:

- `npm run lint` — `eslint.config.js` was never committed and the script still
  passed `--ext`, removed in ESLint 9. Added the flat config; ESLint 10 +
  eslint-plugin-vue 10 + @vue/eslint-config-typescript 14. The three
  Prettier-owned line-break rules (`max-attributes-per-line`,
  `singleline-html-element-content-newline`, `html-self-closing`) are off — they
  were 318 of the 321 initial findings and every one was a disagreement with
  Prettier, not a defect. The 3 real findings were fixed. Lint is now clean.
- `npm run format` — no `.prettierrc`, so Prettier's defaults (semicolons,
  double quotes) fought the codebase's actual style and reported 37 files. Added
  a config matching the tree: no semicolons, single quotes, printWidth 100,
  trailing commas. Tree formatted; clean.
- `./vendor/bin/pint` — 69 files fail the default Laravel preset, stock framework
  config among them, i.e. it has never been run. NOT fixed wholesale: that is a
  formatting sweep, not a bug fix, and it would bury real diffs. Only the genuine
  dead code was removed (`no_unused_imports` in `ServiceSeeder`, `CreatePost`,
  `HasTranslations`). Left as a known state, not a gate.

## G18 — `.eyebrow` did not match its own source node  [FIXED 2026-08-21]  (severity: med)
`Eyebrow.vue` names `268:3034` as its source. That node is **Maneli 400 at 20px
in a 45px line box**; the class implemented `text-label-lg` — 14px, weight 500,
in the body font. Wrong family, size and weight, on every eyebrow site-wide.

Now: `font-display` at 20px/400 with the 45px box preserved (the frames position
the sections below off that height).

`.eyebrow--lg` needed the opposite treatment and would have been broken by the
same change: its source `315:4860` is **Peyda 500 at 20/25**, the body font on
normal leading, not Maneli. It resets `font-family: inherit` and `line-height`.
The Services numerals (`315:4854`, Maneli 400 64px) take `font-display` too, but
stay on leading-1 — the frame's 145px box is a Maneli metric artefact.


## G19 — Integrator review of the three rebuilt pages  (2026-08-21)
Reviewed Work index, Work show and Home after they were rebuilt, against the
frames rather than against the docblocks. Verdict: **faithful**. Spot checks
that passed on `ProjectPostCard` (226:2818) — image 612 square at radius 24 with
the 2px `#a1a1a1` (ink-400) hairline, gap 40 to the detail block, detail gap 24,
caption gap 16, title 36/600 on 1.27, excerpt 16/400 on 1.25 in ink-100, service
dots 4px brand, and `Layer_1` correctly skipped because the file marks it
`visible: false`. Work index vertical rhythm (40 / 24 / 64 / 110 / 96 / 259) and
Home's twelve-section order both match the frames exactly.

Two measurement notes worth keeping:

- The title underline `502:5759` has a **2px path box but a 4.7px render box**
  (4px stroke). Build from `absoluteRenderBounds`, not `absoluteBoundingBox`, for
  anything stroked — the bounding box alone would have shipped it 2px tall.
- Searching a frame for seeded copy finds nothing when the frame rewords it.
  That is what produced the wrong first version of G16. Search by node and
  geometry, then compare copy.

### Defects found and fixed during review

1. **`FilterChips` rendered stadium pills.** Both frames put chips at
   `cornerRadius: 16` (insights 268:5242-268:5250, projects 222:2472-222:2480);
   the component used `rounded-round`. Wrong on two pages, including one marked
   "verified". Now `rounded-lg`.
2. **The footer called two translation keys that do not exist.**
   `$t('footer.rights')` and `$t('footer.privacy')` against a `lang/fa/footer.php`
   that defines `copyright` and `privacy_policy`. The translator falls back to
   the key, so every page rendered the literal strings "footer.rights" and
   "footer.privacy" in the bottom bar. Fixed; a full sweep of all 47 `$t()` calls
   now resolves.
3. **Footer legal links bypassed Ziggy**, building `/${locale}/privacy-policy`
   by hand. Now `route('legal.privacy')` / `route('legal.terms')`, matching every
   other link on the site.

### Open, left for the owners

- `resources/js/Components/ProjectCard.vue` is **orphaned** — nothing imports it
  since `ProjectPostCard` replaced it. It still carries the old "DERIVED — no
  Sizdah frame exists" docblock. Delete it once the Work listing is signed off.
- `ProjectPostCard` uses `rounded-[24px]` where the `rounded-xl` token is exactly
  24px. Cosmetic, no visual difference, but it is the kind of literal the token
  layer exists to prevent.

## G20 — There is no 405 design; the frame named "405" is a Persian 404  [RESOLVED 2026-08-21]  (severity: low)
MANIFEST listed three error frames and carried `88:2490` as a pending **405**
page. It is not one. Exporting all three illustrations settles it:

| frame | ground | illustration | numerals |
|---|---|---|---|
| `82:2059` "404" | `#FFFFFF` | `af541b51…` | Latin **404** |
| `88:2490` "405" | `#FFFFFF` | `c74e80b5…` | Persian **۴۰۴** |
| `266:2825` "404" | `#141414` + wash | `1ca9f92d…` | Persian **۴۰۴** |

All three carry byte-identical copy (heading «مسیر را گم کردید؟», the same
two-clause body, CTA «بازگشت به خانه»). `88:2490` is the *light twin of the
Persian 404*, misnamed in the file — not a Method Not Allowed design. So the
canonical error page is `266:2825` (dark + Persian, matching a Persian-only site
on the ink-1000 ground) and the other two are superseded exactly as the light
Home/projects twins are.

Consequences applied:
- `Error.vue` needed no rebuild — it was already built against `266:2825`.
- `bootstrap/app.php`'s Figma comment cited two dead node IDs (`1027:2061`,
  `1567:13563`) from the stale `docs/` audit; replaced with the three real ones
  plus a note that 405 is deliberately absent from the status allowlist.
- The frame draws the body as **two lines** (a hard break after «پیدا نشد،»).
  `lang/fa/errors.php` had flattened it to one; the newline is restored and the
  paragraph renders `whitespace-pre-line`.
- `Error.vue`'s body carried `leading-[normal]`, overriding the measured
  `text-title-sm` leading (18/22, G14) for no stated reason. Removed.

Still open, and **not** a design question: a real HTTP 405 currently renders
Laravel's default error page, because `bootstrap/app.php` only routes
`[403, 404, 419, 429, 500, 503]` through Inertia. Adding 405 would be inventing
a page the file does not contain, so it is left alone and recorded here instead.

## G21 — The dark-page corner wash was never implemented  [FIXED 2026-08-21]  (severity: med)
Every dark page frame paints a **second fill** over the `#141414` ground: a
brand-yellow linear gradient that is fully transparent for most of its run and
reaches ~10% in one corner. Nothing in the codebase drew it — `AppLayout` was a
flat `bg-ink-1000`, so every page was missing the wash.

Angle and peak opacity drift per frame, which reads as authoring noise rather
than intent:

| frame | gradient |
|---|---|
| projects `222:1989` | `-42deg`, 0% → 10% |
| 404 `266:2825` | `-42deg`, 0% → 10% |
| about `336:5623` | `-11deg`, 0% → 7% |
| Home `268:2962` | `71deg`, 0% → 10% |
| case study `336:5374` | **none** — flat `Black/1000` |

Implemented as one shared `.page-wash` at the dominant `-42deg`/10%, applied as
a `background-image` on the same element that carries `bg-ink-1000` — the file
models it as a second fill on one frame, so no overlay node and no stacking
order to manage. **Deviation:** the case study gets the wash too, though its
frame has none; at 10% peak in a single corner the difference is not worth a
per-page prop.

## G22 — Decorative hairline meshes were missing on two frames  [FIXED 2026-08-21]  (severity: med)
`About.vue` already drew its mesh (`359:9560`) with the shared `.grid-mesh`
background and per-axis cell variables. Two other frames carry the same device
and neither was implemented — G19's review missed them because it compared
content blocks, not backdrops:

| frame | node | size | cells | placement |
|---|---|---|---|---|
| projects `222:1989` | `266:2771` | 1200x1200 | 109.095 x 109.095 | x=120 (centred), y=180 |
| case study `336:5374` | `511:9519` | 1200x872 | 109.095 x 109.095 | x=120 (centred), y=202 |
| about `336:5623` | `359:9560` | 1200x944 | 109 x 118 | behind the story block |

Both new ones were first exported as SVG (~700 bytes gzipped each) and then
**discarded** in favour of `.grid-mesh`: the lines are a plain repeating grid
with white strokes, so a CSS background reproduces them exactly, costs no
request, and matches how About already does it. Drawn from `lg` up only, since
the geometry is canvas px.

Also corrected while here: Home's mesh (`268:2966`) is **884x778**, not square.
`Home.vue` drew it `size-[884px]`, overshooting the frame by 106px.

## G23 — "verified" was not the same as "built to the frame"  [FIXED 2026-08-21]  (severity: HIGH)
The user reported `/fa/insights` and `/fa/contact` as visibly wrong. Both were
marked **verified** in MANIFEST. That status turned out to mean *"already built
against this node ID and structurally re-checked"* — it never included measuring
the header block. Re-diffing every page header against its frame found the same
defect on four pages, plus two more type errors.

**The standard page header, measured off five frames.** Every listing/detail
page uses one device: an outer **670** track on **gap 64**, wrapping a **612**
column on **gap 40** (eyebrow → title block), whose title block is **gap 24**.
Everything is centred.

| page | frame | header node | headline |
|---|---|---|---|
| Work index | 222:1989 | 266:2793 | Display/Large 48/700 |
| Insights index | 268:4158 | 268:5233 | Display/Large 48/700 |
| Contact | 279:6325 | 279:6398 → 279:6399 | Display/Large 48/700 |
| Insights show | 285:4590 | 285:5060 | Display/Medium 40/700 |
| case study | 336:5374 | 411:8560 | Display/Medium 40/700 |

**What was actually wrong:**

1. **Insights index** — `gap-4` (16) everywhere instead of 40/24, no 670 track,
   and the headline was `display-md` (40) where 268:5239 is **48**. The section
   rhythm was also flat: 270:5252 runs **144** between the featured card, the
   2-up row and the 3-up block, and inside the 3-up block (270:5286) the two
   rows sit **96** apart while columns stay 24. The page had 48/96/24.
2. **Contact** — same `gap-4` header; headline `display-md` where 279:6404 is
   **48**; lede `title-sm` (18/400) where 279:6406 is **tite/Medium (20/500)`;
   and the headline was `brand-50` where the frame fills Black/50 (`ink-50`).
3. **Insights show** — headline `display-sm` (36) where 285:5066 is **40**.
4. **case study** — header was left-aligned with an eyebrow. 411:8560 is
   **centred**, both text runs are `CENTER`, and the frame draws **no eyebrow**
   there. Now centred, kicker dropped, `Eyebrow` import removed.

**The mesh again (see G22).** Three more frames carry the 1200x872 / 109px
hairline mesh at y≈202 and none of them drew it: Contact `279:6376`,
Insights show `299:7865`, Services `511:9147`. All three now use `.grid-mesh`.

5. **Services** — header `gap-6` throughout where 309:4754 is **gap 40** to the
   title block (309:4758, gap 24), and the lede was `title-sm` (18/400) where
   309:4760 is **tite/Large (22/500)**.

**Legal was the one that held up.** 279:5998 has a single child, so its gap 40
is inert and 279:6002's gap 24 is the real one — which is what `Legal.vue`
already had. Its header is also `flex-end` (inline start), not centred, unlike
every listing page; that is correct and deliberate.

**Lesson for the ledger:** "verified" is not a build status. A page can cite the
right node ID in its docblock, render the right blocks in the right order, and
still be wrong in every gap and type size — which is exactly what a user sees
first. Re-measure the header and the section gaps, not just the block order.


## G24 — Footer's giant outlined wordmark is not shipped  (severity: low)
`279:6327` carries a 1248x304 outlined wordmark (`1065:2315`) positioned at
x=99 / y=264 so that it bleeds 137px past the footer's own bottom edge and is
clipped. It spells **SAHRA**, and it is drawn as five gradient-filled letterforms
running `#231F20` at 15% -> transparent by 34.7%.

Not implemented, on two independent grounds:
1. It is the previous agency's name, which the footer already refuses to ship
   (see the component's own DEVIATION note).
2. It is invisible. `#231F20` at 15% over the `#141414` ground composites to
   `#171717` — sampled off the rendered frame at exactly that. A 3/255 tonal
   shift is below the threshold of perception on any real display.

Redrawing it with the Sizdah letterforms would be inventing artwork to produce
an effect nobody can see. If it is ever wanted, it is a single absolutely
positioned SVG at `inset-block-start: 61.25%` with `overflow-hidden` on the
footer.

## G25 — Phase 4 was never done: the component sheet had never been read  [FIXED 2026-08-21]  (severity: HIGH)
MANIFEST listed `21:2152` "Components" and every card frame under it as
**pending**, and PROGRESS listed Phase 4 (shared layout & components) unchecked,
while Phase 5 was marked complete. The pages had been measured; the components
they are built from never had been. Reading the sheet turned up defects in every
shared component on the site.

**The sheet's real contents** (it is worth knowing these exist):
`20:2087` header item (selected / default) · `21:2164` CTA (sm-BW / lg-BW /
sm-WB / lg-WB) · `21:2159` Header · `91:2580` logo · `71:2145` KPIs ·
`138:2357` project cards (open / close) · `537:5510` card (Default / **hover**) ·
`546:7528` testimonial (Default / **hover**) · `598:5732` member card ·
`276:5724` blog card (612 / 400).

**The pattern worth remembering: this file ships hover and state variants for
most cards, and none of them had been implemented.** A component that renders
its default state correctly was still only half-built.

### Fixed

1. **`CtaButton` forced every button to 57px.** It carried `min-h-[57px]` and a
   docblock claiming "every instance in the file measures 57px tall". False: the
   set is sm 48 (46 with no arrow) and lg 57. Height falls out of padding plus
   the label's line box with no min-height at all — 12+22+12=46, 16+25+16=57 —
   so the pin is now gone. The header CTA had been rendering 11px too tall.
2. **The header doodle was on the wrong element.** `Layer_1` (`I268:3031;56:2399`)
   sits at x=98.148 / y=12.948; the CTA occupies x=96..223. It decorates the
   CTA's top corner, mirrored on X. It had been pinned beside the wordmark.
3. **Nav underlines were one asset at two opacities.** The two states are
   different nodes: selected `54:2219` is a full-width rule on a 2px layout box
   that renders 4.71px (`preserveAspectRatio="none"`, so it stretches); default
   `27:2357` is a 45.276x9.676 squiggle exported with `opacity="0"` baked in —
   a deliberate spacer, and load-bearing, since it is what makes an unselected
   item 42.676 tall against the selected item's 35. Both assets were already in
   the repo, correctly exported, and wired up wrong.
4. **Nav weight** — default is Regular 400, selected Medium 500. `text-title-md`
   carries 500, so every item rendered selected-weight.
5. **Header bottom border** — the frame paints none; the 15% white fill and the
   15px blur are the whole treatment.
6. **Footer colours were inverted.** Column titles are black/900 `#393637` (a
   deliberately recessive label, *darker* than the links) and links, tagline and
   bottom bar are black/600 `#7B7979`. Titles had been `paper` #F1F1F1. Both
   hairlines are light, not dark: the footer's top rule is black/100 `#E9E9E9`
   and the bottom bar's is black/300 `#BDBCBD`; both had been ink-800 `#434343`.
   All four sampled off the rendered frame, not inferred.
   **-> Flagged for a human:** `#393637` on `#141414` is a contrast ratio of
   about **1.5:1**. It is what the file draws, and it is what now ships, but it
   fails WCAG AA (4.5:1) by a wide margin and the column headings are close to
   unreadable. If accessibility outranks fidelity here, `warm-600` #7B7979 —
   the links' own colour — is the smallest change that clears AA.
7. **Footer geometry** — the frame rounds the footer 16px and casts an upward
   `0 -5px 10px rgba(0,0,0,0.05)` shadow (new `shadow-footer` token). Its type
   runs on **1.5**, not the 1.25 scale default: the Quick Links column is
   24 + 16 + 6x21 + 5x12 = 226, the frame's height exactly.
8. **`BlogCard` used one title size for both variants.** 612 card is 28px
   (`275:5577`), 400 card is 24px (`276:5746`). The image hairline is black/100
   `#E9E9E9` (warm), not ink-100 `#E8E8E8` (cool).
9. **`StatCard` border was half width** — `71:2145` is `border-4` at
   Yellow/1000 40%, not 2px; and its value row sits on space8, not space16.
10. **`TestimonialCard` had been built without the frame.** Its own docblock
    admitted this and asked for a re-check. Every guess was wrong: border
    ink-800 -> Black/300 `#B9B9B9`; quote Black/200 -> Black/50; name 16 Medium
    White -> **14** Medium Black/100; role 14 Regular Black/300 -> **12** Medium
    Black/200; rhythm 24/40 -> **40/48**; avatar gap 12 -> 8; name/role gap 0 -> 4.
    Its ground was `.surface-glow`, which lays a 20% black wash and an 80%
    ink-1000 base *under* the raking gradient and so sinks the card below the
    page ground — the frame lifts it above (`#28251e` against a `#1e1e1e` sheet).
    Now `.testimonial-wash`: the single -45.9deg wash plus the vertical hairline
    texture the frame carries as a 442px image whose lines repeat every 54.4px,
    drawn in CSS on the G22 precedent rather than shipped as a bitmap.
11. **Two hover variants implemented** — testimonial `546:7529` (solid
    Yellow/1000, copy inverted to the dark ramp) and process card `537:5511`
    (flat `#f3b435`, the file's only use of that yellow). The process numeral is
    also Maneli **Regular**, not Medium.

### Deviations taken, deliberately
- The process hover variant re-aligns its numeral row from `center` to `start`,
  which would jog the numeral ~12px on pointer-enter. Held steady; only the
  colour inverts.
- Both hover variants swap in separately-inked dark glyphs. The marks are
  single-colour, so they are driven to black with `brightness-0` rather than
  shipping a second pair of near-identical SVGs.
- The header's logo is not flush to the 96 gutter in the frame — a fixed 262px
  gap leaves it 36px short on the inline-end side, against a 96px gutter on the
  other. `justify-between` is kept: a hardcoded 262 would not survive a resize,
  and the asymmetry reads as authoring slack.

### Still pending on the sheet
`598:5732` member card (Default / Variant2) and `138:2357` project cards
(open / close) were inventoried but not yet diffed against their implementations
(`Pages/About.vue` team block, `ProjectPostCard` / `ProjectShowcase`). The
`21:2159` Header and `91:2580` logo masters are covered by the instance read.

## G26 — Post-G25 re-walk of the pages that embed the fixed components  (2026-08-21)  (severity: n/a — verified clean)
PROGRESS flagged a risk after G25: several page layouts might have been sized
around the *old*, wrong component metrics — "notably any layout that assumed a
57px CTA". Walked every consumer. **Nothing was compensating; the G25 fixes were
self-contained inside the components.** Recorded so it is not re-investigated.

| component fixed in G25 | consumers | verdict |
|---|---|---|
| `CtaButton` (57px pin removed) | Home, Contact, Services, Error, `LeadMagnetBanner`, `FinalCtaCard`, `StartTogetherCard`, `AppHeader` | no `h-[57px]` / `min-h-[57px]` anywhere outside CtaButton's own docblock; `AppHeader` sets `py-6` and lets height fall out of content, which is what the removal requires |
| `BlogCard` (per-variant title size) | Insights index, `InsightsShowcase` | Insights index passes `variant="wide"` for the first two cards and the default for the rest, so 28px/24px land on the right rows |
| `StatCard` (border 2→4, row 16→8) | Home KPI row | passes props only; grid `gap-4` is track spacing, not compensation |
| `TestimonialCard` (rebuilt end-to-end) | Home reviews row | passes props only |

One thing deliberately **not** changed: `FeaturedPostCard` still uses
`.surface-glow`. G25 removed that treatment from `TestimonialCard` because it
sinks a card below the page ground — but the class was *sampled from* the
featured-post node `270:5253` in the first place, alongside the two contact
cards (`279:6409`, `279:6439`). It is correct there. Removing it by analogy with
the testimonial fix would be a regression.

## G27 — G25's last two sheet diffs, closed  (2026-08-23)  (severity: low)
Figma MCP reconnected. Both diffs PROGRESS left BLOCKED on 2026-08-21 are done.

**`598:5732` member card vs `About.vue` team block.** Close to right already —
card fill/border (Yellow/200 default, Yellow/1000 hover), radius 16, border 4,
and both text colours/sizes (name 24 Medium warm-900→ink-1000 on hover, role 20
Medium ink-700→ink-800 on hover) all matched. One real miss: the photo
(`598:5761`/`598:5770`, 229.4x229.4) is `object-bottom` in the frame — the crop
anchors to the chin/shoulders, not centre. `About.vue`'s `<img>` had plain
`object-cover`; added `object-bottom`. The card's fixed 229.4 width is not
carried over — the grid is responsive (`grid-cols-2 sm:3 lg:5`, no tablet frame
exists to measure against) and that adaptation predates this pass.

**`138:2357` project cards (open/close) vs `ProjectShowcase.vue`.** Typography,
the CTA-link colour override (frame sets it Black/1000 on Black/1000, already
flagged as invisible-in-file and overridden to brand — correct, left alone),
and the gold title rule all matched. One real miss: the row divider
(`143:2403` open / `143:2484` close) is not one weight — the open (active)
rule renders from a 2px image (`inset-[-2px...]`), the collapsed one from a 1px
image (`inset-[-1px...]`). The component had both at a flat `h-px`. Now `h-0.5`
on the active row, `h-px` on the rest.

**Considered, not changed:**

- The close state's CTA row is a real node (`138:2360`) drawn at `opacity-0`,
  not omitted — in the nav-item case (G25 #3) that kind of spacer was
  load-bearing for row height. Here it isn't: the row's height is set by the
  title line-box (40px active / 32px collapsed), which is taller than the CTA
  link in every state, so `v-show`ing it away costs nothing. Left as `v-show`.
- The dot-and-line colour on the collapsed divider samples close to ink-600
  border / ink-1000 fill in the rendered frame, which is what the component
  already had; the sheet's own style list also carries Yellow/600 `#FBD587`
  and Yellow/800 `#F9C75F` for this node pair, but neither the JSX (both dots
  are opaque image assets, not inline fills) nor the screenshot resolve which
  state they belong to. Not confident enough to change; flagged here rather
  than guessed.

**Phase 4 is now fully closed** — every row the component sheet (`21:2152`)
carries has been mined and diffed. What remained was the five MANIFEST rows
below; see G28 for why all five turned out to need no code.

## G28 — The five remaining MANIFEST rows are off-canvas scratch, not unbuilt work  (2026-08-23)  (severity: n/a — verified, no code change)
Every one of `598:5751`, `411:7564`, `149:2691`, `299:7811` and `615:6178`
read as ordinary "component" rows in MANIFEST with vague notes ("TBD", "small
ui", "bar/row"). `get_metadata` on each (not `get_design_context` — this was
an orientation check, per the design-to-code skill's own carve-out) shows why
none of them were ever finished: **all five sit at absurd canvas coordinates**
— x = `-792`, `13700`, `13384`, `-4267`, alongside one (`598:5751`) whose
frame itself is at x=`-4267`. Real page frames in this file sit in the
low-to-mid thousands at most (Home tops out under x≈2000 across its four
variants). This is the same authoring habit MANIFEST already flagged for
`163:1428` "thinking" (G9) — parking non-shipping content far off the visible
canvas — just not previously checked for these five.

Confirmed per node, so nothing here is a guess:

- **`598:5751`** (member/card grid, x=-4267) — eight symbols, one per team
  member (`razieh hover` … `amin hover`), each the person's photo at 294×294
  / `radius-sm` (8px). This is the **source crop** each member card's photo
  was pulled from, not a second component — the live card (`598:5732`, fixed
  in G27) uses the same photos at 229.4×294 / `radius-lg` (16px) via the
  `object-bottom` crop already applied. No separate frame to build.
- **`411:7564`** (x=-792) — two copies of the same "ChatGPT Image Jul 28…"
  generated photo plus a "branding" wordmark, one child already marked
  `hidden` in the file itself. A mood-board, not a section.
- **`149:2691`** (x=-792) — forty-plus Streamline Freehand icon exports in a
  grid (thought bubbles, arrows, quote marks, an Instagram glyph, a calendar).
  It is the **icon library** the file's actual glyphs were pulled from
  (several are already in the repo under individually-exported SVGs), not a
  page component.
- **`299:7811`** (x=13384) — a chevron plus a "paper-and-pencil" glyph
  combined at 64×48. A single icon composite with no page reference found;
  left unbuilt as it has no known destination, not because it was skipped.
- **`615:6178`** (x=13700, "bar/row") — four pill cards (services / Instagram
  / collaboration year / industry) with a gold hairline border and gradient
  fill. This looked like a real hit at first — its four fields exactly match
  the case-study meta row — until position and `Work/Show.vue`'s own docblock
  (`grep` for `meta-industry` etc.) showed the *actual* shipped meta row cites
  a different node, **`411:8568`** (label-over-value, no card chrome), inside
  the real page frame. `615:6178` is a discarded pill-card draft of the same
  content, parked off-canvas rather than deleted. Building it would have
  replaced a correct, already-shipped treatment with an abandoned one.

A sixth row turned out to be the same thing: **`537:5561`** "Frame 96449"
(x=-1842) is six icon-tint pairs (white/black, Default/Variant2, yellow/black,
64×64 each) — the asset reference for exactly the icon-recolour trick G25
already implemented in `ProcessStepCard`'s hover state (`brightness-0` on the
default glyph rather than a second shipped SVG). Not a seventh component.

**Lesson for the ledger:** a MANIFEST row with a vague note ("TBD", "card
variant") is not evidence of unfinished work — `get_metadata`'s x/y is a
two-second check that would have closed all six of these without ever calling
`get_design_context` in earnest. Worth running on any future "pending" row
before diffing it.

No page frame, component, or asset is outstanding anywhere in the file as of
this pass.

## G29 — Services was missing its hand-drawn flourishes; the "arc-rings" note explained  (2026-08-23)  (severity: medium — visible, brand-coloured, previously undocumented as a real gap)
The user asked for a full re-comparison against the live Figma file, not just
the MANIFEST tail. `get_metadata` on the full `services` frame (`308:4492`)
surfaced two families of visible, non-hidden decorative marks that
`Services.vue` never rendered — the same ones PROGRESS's "standing gaps" note
called "Services clip-path layers, arc-rings-*" without pinning down node IDs.
(The note's "clip-path layers" turned out to be four *hidden* mesh groups —
`322:5248/5270/5293/5315`, one per service block, same internal structure as
the already-shipped `.grid-mesh` — correctly never built, since Figma itself
marks them `hidden="true"`.)

**The rings** — `511:9188`, `511:9193`, `511:9518`, `607:5917`, one per
service block (163×109, hand-drawn, Yellow/1000 `#F8B937`) — sit behind each
big numeral. Confirmed by position, not guessed: each ring's y-range falls
inside its block's numeral text-frame, and its x-range falls inside whichever
side (left/right) that block's text column occupies — the same alternation
`Services.vue` already drives off `index % 2` for the image. First downloaded
all four raw exports, which hashed differently — but a byte diff showed the
*only* difference across all 134,861 bytes was the embedded `id="Vector NN"`
label; the path data itself is identical. One master ring, instanced four
times, not four hand-drawn strokes — shipped as one shared asset
(`services/ring.svg`, `svgo --multipass`'d from 132 kB to 62 kB).

**The sparks** — `511:9195`, `511:9200`, `322:5242` (~20×25, same yellow) —
sit in the gaps *between* consecutive blocks, horizontally centred (x≈699-741
against a 1440 canvas). Three gaps for four blocks, one spark each, and these
three exports were byte-identical from the first `md5sum` (no id-attribute
noise to explain it away). One shared asset, `services/spark.svg` (2.6 kB
raw, 1.5 kB optimized).

**Lesson for the ledger, again:** a raw-export file hash is not proof of
distinct artwork — Figma bakes the node's own `id` into the exported SVG, so
four instances of one master vector hash as four different files until the
id is stripped. `svgo` (or a plain byte-diff) settles it; don't conclude
"four unique hand-drawn strokes" from `md5sum` alone.

**Not placed**, both deliberately:
- The `315:4998` "Group 21" badge ("جایی که هویت مسیر می‌سازد") — this predates
  this pass (already noted in `Services.vue`'s DEVIATIONS and
  `FIGMA/state.json`) and was out of scope here; still open.
- A fifth small mark, `322:5230` (~17×25) — its y is `-18.6`, meaning ~19 of
  its 25px sit above the frame's own top edge (y=0). Figma frames clip their
  contents by default, so at most a 6px sliver would ever be visible. Treated
  as not-meant-to-render, the same call made on the footer's SAHRA wordmark
  (G24) for the same reason: authored past a clipping edge, not a design
  intent to reproduce.

**Approximation taken, and why:** the rings' Figma coordinates are expressed
against a 505×145 *invisible text frame* around the numeral — a leading-2.27
Maneli metric artefact the project already treats as non-load-bearing (the
numeral itself renders at `leading-1`). Copying those coordinates literally
would position each ring against empty space the live layout doesn't have,
since the live numeral has no fixed-width box to anchor against and the grid
column itself is fluid, not 1248px fixed. Centred each ring behind its
numeral's actual rendered box instead, sized at `w-40` (≈160px, close to the
163px source) — reproducing the "hand-drawn circle around the number" effect
the frame clearly intends, without inheriting a text-frame artefact or a
fixed-canvas coordinate that has no live equivalent. Flagged here rather than
silently approximated, per this ledger's own standing rule.

**Services' MANIFEST status moves from "verified" to "done"** — this is a
real content difference fixed, not a re-confirmation.

## G30 — Same pattern on Insights index; a site-wide category emerging  (2026-08-23)  (severity: medium)
Continuing the re-comparison pass (per G29) onto the other "verified" pages.
`get_metadata` on `268:4158` (Insights index) turned up two more non-hidden
flourishes `Insights/Index.vue` never rendered: a book-and-mark accent
(`298:7715` book icon + `299:7724` a small white/brand squiggle) at the
header column's corner, and a hand-drawn paperclip (`456:6721`, 64×64,
brand yellow) in the gap between the header and the card grid — the exact
same "flourish in the transition zone" placement as Services' sparks (G29).
Both added (`resources/images/sizdah/insights/{book,book-arrow,paperclip}.svg`).

**Deviation taken:** the book+mark's Figma position is the literal top of the
headline's own text box (y=0 of both). Copying that would overlap real copy —
this headline's length varies per page (CMS-driven), unlike Services' fixed
two-digit numeral where sitting behind the text was safe. Pinned the accent
just above the eyebrow pill instead (outside the text flow, `hidden lg:flex`
like the rest of this class of accent), not at the frame's literal y=0.

**Contact (`279:6325`) re-checked against this same pattern and mostly holds
up** — `Contact.vue` already renders the frame's `up-right-arrow` corner
accent (`279:6519`) next to the follow line, which is exactly this class of
element done right. Two loose threads left open, not chased further this
pass:
- `279:6521`, a 40×50 "Group" sitting just above the seam between the details
  and form cards — same footprint as the other small accent marks, not
  identified precisely enough to place with confidence.
- `image 127` / `image 127 [Vectorized]` (24×24) recurs at the identical
  absolute canvas position (x=944, y=1446) on Contact, both Insights frames
  and (hidden-only) Services — a fixed offset regardless of each frame's very
  different total height, which is the signature of a leftover pasted
  reference rather than a per-page design element. Its own screenshot renders
  as a plain dark square with no discernible shape. Treated the same as the
  footer's invisible SAHRA wordmark (G24): not chased as a real gap without
  stronger evidence it is meant to render.

**The pattern worth naming:** this file scatters small hand-drawn accent
marks (rings, sparks, a paperclip, a book+squiggle, an up-right arrow) at
section seams and corners throughout, on top of its structural content. The
original page-by-page build clearly focused on structural fidelity — layout,
type, colour — and passed over these consistently. Two pages checked, two
pages had misses; the other seven page frames have not yet had this specific
check run against them. Flagging as a real, site-wide, still-open category
rather than closing it here — see PROGRESS for what's left to check.

## G31 — Remaining pages swept; category closed except Home  (2026-08-23)  (severity: low — nothing new found)
Finished the sweep G30 left open. `get_metadata` on every remaining page frame:

- **Insights show (`285:4590`)** — the lead-magnet's own small icon (`303:4482`)
  is already shipped (`LeadMagnetBanner.vue`'s `checklist-doodle.svg`). One
  unconfirmed loose end: `294:7700` "Vector 4", a 188×2 hairline right under
  the "مقالات مرتبط" (related articles) heading — position lines up with an
  underline rule, but `get_design_context` was not run on it to confirm colour
  or intent, so it is logged, not built.
- **About (`336:5623`)** — every non-hidden node cross-checked against
  `ASSETS.md`/the page's own docblock (hero photo `343:9292`, hero-script
  `354:9302`, story mark `583:5905`, all four principle icons, all five member
  cards). Nothing unaccounted for.
- **Legal — privacy (`279:5924`) and terms (`281:6773`)** — both are a flat
  repeating heading+paragraph structure with no icons or decorative nodes at
  all beyond the recurring `image 127` (see below). Genuinely simple; no
  flourish category to miss here.
- **Work index (`222:1989`)** — clean. One odd node, a bare `header item`
  component instance (`261:2935`) floating directly on the page outside the
  actual `Header` instance — read as the file's own way of marking "this nav
  item is the active one" for reference, not a second element to render
  (`AppHeader` already drives its active state from the real route).
- **Work show / case study (`336:5374`)** — metadata suggested a much bigger
  page than `Work/Show.vue` looked like it covered (a 4-pillar strategy
  collage, an Instagram gallery, a 5-tile KPI row, a before/after block, a
  next-project link) — but reading the actual file end to end showed every
  one of those is already implemented (`challenge`, `goals`/`deliverables`,
  `strategy`, `showcase`, `results`, `beforeAfter`, `next`), with its own
  honestly-documented deviations (no per-result icons — schema has no icon
  field; the stray Services-page copy at `428:5044` correctly excluded, see
  G19). This page is more complete than the metadata skim alone suggested;
  worth remembering that a rich metadata tree is not evidence of a gap by
  itself.
- **404 (`266:2825`)** — small, simple frame (illustration + title + subtitle
  + CTA), no stray non-hidden nodes beyond the illustration/copy/CTA
  `Error.vue` already renders.
- **Home (`268:2962`)** — `get_metadata` returned the frame with **no
  children** twice in a row; the page is large enough (9919px, twelve
  sections) that the tool could not enumerate its tree at this scope. Not
  swept the same way as the rest. This is also the page with the deepest
  prior fix history in this ledger (G14 type scale, G16 lead-magnet copy
  pulled verbatim from the live frame, G21/G22 mesh fixes) and already reuses
  the `up-right-arrow` accent seen correctly done on Contact — some signal
  that this specific "scattered flourish" class was not universally missed.
  Left as a genuine unknown rather than claimed either "clean" or "checked."

**Net for this category:** two real misses found and fixed (Services G29,
Insights index G30) out of nine pages actually inspectable; About, Legal x2,
Work index, Work show, 404 held up clean; Home could not be swept by this
method and remains unverified against this specific gap class.

## G32 — Home swept section-by-section; one more real miss, one confirmed non-issue  (2026-08-23)  (severity: low)
`get_metadata` on the whole Home frame (`268:2962`) returns no children —
confirmed a third time, so this is a real tool limit at this page's size
(9919px / 12 sections), not a fluke. Worked around it by pulling each
section's own node ID straight from `Home.vue`'s docblock and querying those
individually — every one of the twelve resolved cleanly:

- **Hero, KPI, trust proof, lead magnet, why-us, insights, FAQ, final CTA** —
  all clean against their own component's docblock and node citations.
  `WhyUsGrid.vue` in particular is worth noting as thorough (4 claims + both
  hand-drawn quartering rules + the icon-matching logic, all cited correctly).
  The FAQ frame's boxes 2-4 carry hidden text nodes with stale English
  "Sahra" placeholder copy — leftover template noise, not a rendering gap
  (real answers are CMS `props.faqs`, and the accordion only shows one answer
  open at a time, which is exactly what those `hidden` nodes represent).
- **Services band (`268:3032`)** — a huge, vector-dense "Lost Level" orbit
  illustration. Did not pull full `get_design_context` on it (would have been
  very expensive); `ServiceOrbit.vue` was read directly instead and is
  thorough — every doodle, connector line and the responsive mobile fallback
  is accounted for with its own node citation. No gap.
- **Testimonials (`268:3720`)** — one real miss, same family as Insights
  show's G30 hairline: a hand-drawn brand-yellow rule (`268:3728`, 81×4) tucked
  under the subtitle, not rendered. Added to `Home.vue` directly (not
  `SectionHeading.vue`, since none of the other six sections using that shared
  component carry this rule — it is unique to testimonials, confirmed by
  checking KPI/trust-proof/why-us/insights headings for the same sibling node
  and finding none).

**Insights show's G30 loose end resolved as the same thing.** `294:7700`
"Vector 4" turned out to be the identical hand-drawn-rule pattern (also
Yellow/1000, also tucked under a heading) — fetched its `get_design_context`
to confirm rather than leave it unconfirmed, and added it under the "related
articles" heading in `Insights/Show.vue`. Both rules downloaded as
`home/testimonial-rule.svg` and `insights/related-rule.svg` — genuinely
different hand-drawn paths (not the ring/spark situation from G29), so kept
as two separate files.

**Answering "is everything identical to Figma now":** no page frame or
component in this file has unread children left — every node this session
could reach has been checked at least at the metadata level, and every real
mismatch found was fixed (G27-G32: 6 concrete fixes across Services, Insights
index x2, Home, About's member card, ProjectShowcase). What is **not**
claimed: pixel-for-pixel re-measurement of every spacing/colour/type value on
every page this session — that level of rigor was applied fresh to Services
and the two heading-rule fixes; the rest rely on prior sessions' G14/G20-G26
measurement passes, which this session spot-checked structurally rather than
re-measuring from scratch. See PROGRESS for the itemised confidence level per
page.

## G33 — Home's two hero CTAs were rendering at the wrong size  (2026-08-23)  (severity: medium)
Started the pixel-level pass proper (per-node `get_design_context`, not just
`get_metadata`) on Home's hero. Both hero buttons — `268:2990` (primary,
"شروع گفتگو", brand fill) and `268:2989` (secondary, "دیدن پروژه‌ها", white
fill + brand border) — are 57px-tall **`lg`** instances in the frame
(32/16 padding, 20px Medium label; `CtaButton`'s own docblock already
documents this exact 16+25+16=57 math). `Home.vue` passed no `size` prop to
either, so both defaulted to `md` (24/12 padding, 18px label) — visibly
smaller and less padded than the frame. Fixed: both now pass `size="lg"`.

Every other `CtaButton` call site was checked for the same miss:
`AppHeader.vue` and `MobileMenu.vue` correctly omit `size` (the header CTA is
`CtaButton`'s own cited example of the default/`md` size); `StartTogetherCard.vue`
already passes `size="lg"`; `Error.vue` already passes `size="lg"`.
`FinalCtaCard.vue` passes no size — not yet checked against its frame
(294:7672); flagged, not fixed, pending that check.

## G34 — `FinalCtaCard` was dead: its Figma node no longer exists, and both its call sites drew the wrong card  (2026-08-24)  (severity: HIGH — wrong component on two full pages)
Chasing G33's flagged follow-up (check `FinalCtaCard`'s frame, `294:7672`)
turned up something bigger: `get_metadata` on `294:7672` returns "node not
found" — **the node this component has cited since it was written no longer
exists in the file.** `Work/Show.vue`'s own docblock already records that
this exact class of error happened once before: that page used
`FinalCtaCard` until 2026-08-21, "which was simply the wrong card for this
frame," and was switched to `StartTogetherCard`. That fix was never checked
against `FinalCtaCard`'s two remaining call sites — `Services.vue` and
`Insights/Show.vue` — which is exactly the gap this pass closed.

Screenshotted both pages' actual closing-card nodes — `577:10187` (Services)
and `577:9836` (Insights show) — against `577:10889` (About's confirmed
`StartTogetherCard` instance). All three are pixel-identical: the cream
"آماده‌اید برندتان را به مسیر درست هدایت کنید؟" card with the three-person
illustration and the brand CTA. Not the dark, English-copy, invisible-button
Sahra card `FinalCtaCard` renders. Both `FinalCtaCard.vue`'s docblock and
`StartTogetherCard.vue`'s own comment describing it (*"the older dark card...
still used by services and the article page"*) were both stale, sourced from
whenever `294:7672` was last real — presumably before this file's dark final
CTA was redesigned into the same cream card used everywhere else.

**Fixed:** `Services.vue` and `Insights/Show.vue` now both use
`StartTogetherCard`, matching every other page in the file. `FinalCtaCard.vue`
had zero remaining call sites after the swap (`grep` confirmed), so it and its
only consumer, `shared/final-cta-bg.png` (1.8 MB — the single largest image
asset in the repo, dead weight until now), are both **deleted**. Updated the
docblocks in `Home.vue`, `About.vue`, `Work/Show.vue` and
`StartTogetherCard.vue` that referenced `FinalCtaCard` by name so none of them
point at a component that no longer exists.

**Lesson for the ledger:** a component's own docblock citing a node ID is not
evidence that node still exists — `Work/Show.vue` had already hit this exact
failure mode once (G-unlabelled, 2026-08-21) and the fix was not propagated to
the sibling call sites of the same component, because nothing re-checked
`FinalCtaCard` itself once Work/Show stopped using it. A dead component with
live call sites elsewhere is invisible to any page-by-page audit that trusts
each page's own "done" status; it only surfaces by checking the *component's*
source node directly.

## G35 — `warm-*` vs `ink-*` ramp confusion: two more components, six colour fixes  (2026-08-24)  (severity: medium)
Continuing the per-value pass through Home. `StatCard` (KPI) and
`ProcessStepCard` both re-checked value-by-value against `71:2145` and
`537:5510` — clean, including the exact `.process-wash` gradient angle/stops
in `app.css`. Two more components were not clean, and both failures are the
**same root cause already seen once in G25** (the footer's inverted colours)
and once in Services (G34's sibling checks) — the file has two visually
similar but distinct dark-neutral ramps, cool `Black/*` (→ `ink-*`) and warm
`black/*`/`primary-black` (→ `warm-*`), and code keeps reaching for `ink-*` on
surfaces the frame actually draws in the warm ramp.

**`FaqAccordion.vue`** (`434:5548`, on the warm gold/100 card): question was
`text-ink-1000` (#141414) where the frame draws `#393637` — `warm-900`.
Answer was `text-ink-700` (#5B5B5B) where the frame draws `#656363` —
`warm-700`. Both fixed. The gold card ground made this predictable in
hindsight: cool greys read visibly off against a warm cream surface.

**`InsightsShowcase.vue`** (`430:5247`, the lead "Big insight" card — also a
warm cream surface) had five separate misses, all found by reading
`430:5311`/`430:5315`/`430:5332` in full rather than spot-checking one value:
- CTA circle: `bg-ink-1000` → `bg-warm-1000` (#231f20, "primary-black" in the
  frame, not `ink-1000` #141414); was also missing its 1px `warm-800` border
  entirely; hover moved to `warm-900` to match (no explicit hover node in the
  frame, kept the existing lighter-on-hover direction, now on the right ramp).
- CTA arrow icon: 24px → **32px** (`430:5333` is a 32px icon in a 48px circle,
  8px inset each side; the code had a 24px icon, 12px inset).
- Lead-post date: `text-ink-700` → `text-ink-600` (#727272, one step off).
- Lead-post excerpt: `text-ink-600` → `text-warm-700` (#656363) — same
  ramp-confusion pattern as the FAQ card.
- Compact-row date: `text-ink-300` → `text-ink-200` (#D0D0D0, one step off).
- Compact-row title: `text-paper` (#F1F1F1) → `text-ink-200` (#D0D0D0) — the
  frame deliberately mutes the two secondary rows against the bold gold
  lead-post title; the code had all three post titles at full brightness.
- Lead-card background-image gradient: angle `-44deg` → `-32.7deg`, start
  stop `0%` → `2.3248%`, matching the frame exactly and the same stop value
  used elsewhere in the file for this raking-wash pattern. The gradient's own
  base colour (`#fff8eb`) was already exactly right — checked before assuming
  it should be `gold-100` (#F9F5EC, a different, nearby-but-not-equal cream).

**Lesson for the ledger:** `ink-*`/`warm-*` confusion is not a one-off — this
is the third time it has surfaced (footer G25, here twice). Worth a deliberate
check on every remaining warm-surface component (any card on `gold-*` or
cream ground) rather than waiting to trip over it again.

## G36 — Contact's details card checked value-by-value; clean  (2026-08-24)  (severity: n/a)
`279:6409` fully re-read against `Contact.vue`'s details card: border (3px
`ink-300`), radius (24px), padding (32px), row/list gaps (40px/24px), icon
circle size (48px/24px), label (`brand-50`, 14 Medium) and value (`ink-200`,
14 Regular) colours all matched exactly. Two things checked and deliberately
not touched:
- The flat black overlay in the card's background is literal `rgba(0,0,0,0.2)`
  in the frame; the code uses `bg-ink-1000/20` (`#141414` at 20%) — a ~4/255
  difference, imperceptible, and using the token over a hardcoded black is the
  right call anyway.
- Row dividers are a hand-drawn hairline asset (`Vector4`) in the frame; the
  code draws a flat `border-t-2 border-ink-800` instead. Consistent with the
  site's own established precedent for this exact tradeoff (the testimonial
  hairline texture, G25 #10: "drawn in CSS on the G22 precedent rather than
  shipped as a bitmap") — not a fresh miss, a repeated deliberate choice.
- The "Working With" row's Figma text is literal English Sahra-era copy
  ("Working With" / "Brands in Oman and Beyond"); the code correctly pulls the
  CMS/locale value instead, same as every other stale-placeholder case in this
  file (G24 footer wordmark, G31 FAQ boxes).

## G37 — About + BlogCard/PostMeta confirmed clean; one missing shadow on ProjectPostCard  (2026-08-24)  (severity: low)
`About.vue` checked value-by-value end to end: hero (`336:5704`, the `font-bold`
override on `text-hero-line` correctly gets this specific headline to the
frame's Bold/700 rather than the token's default SemiBold/600 — same pattern
already relied on elsewhere), story (`357:9335`), and the four principle
cards (`406:7225` and siblings) — border/radius/padding/gap/type/colour all
matched, including a `py-14` (Tailwind's un-overridden default `3.5rem`
utility, not one of the Figma-named `space*` tokens) landing on exactly the
frame's 56px padding. Team block already fixed in G27.

`BlogCard.vue` + `PostMeta.vue` (`276:5724`) checked against both size
variants (612/28px and 400/24px title) — clean, including the `border-warm-100`
fix from a prior session holding correctly.

`ProjectPostCard.vue` (`226:2818`, Work index) — everything matched except
one real miss: the project image is missing its `shadow-[4px_4px_12px_0px_rgba(0,0,0,0.05)]`
(`shadow-card`) that the frame draws on every other card sharing this same
shadow token. Fixed.

## G38 — `Eyebrow`'s "square" marker doesn't exist in the file; four page headers had it  (2026-08-24)  (severity: medium — visible on 4 page headers)
`TestimonialCard` re-checked against `546:7528` (both `Default` and `hover`
variants) — clean, confirms the G25 rebuild held. `ArticleMeta` (`292:7522`)
had one miss: border was `border-brand-200` (#FEF1D7) where the frame draws
Yellow/300 `#FDEAC3` — a colour with no slot in the `brand` token scale
(50/200/600/800/900/1000, no 300). Fixed as an arbitrary value. `FilterChips`,
`ClientLogo`, Legal (`279:5998`, plus the already-measured `.rich-prose`
rhythm), and the 404 page (`266:2830`) all checked clean.

The real find: `Eyebrow.vue`'s own docblock claims the file ships two marker
styles — a plain `square` diamond for `sm` page-header kickers (citing
`268:3034` as the example) and a glowing `dot` for `lg` feature bullets. While
checking Services' own header eyebrow (`309:4757`) it rendered as a glowing
dot, not the claimed diamond — so the *cited proof node itself* was
re-fetched (`268:3034`/`268:3036`) to settle whether Services was the
exception or the docblock was wrong. It was the docblock: `268:3034`, the
component's own example of "square," is the same glowing round dot (fill
brand, bordered, dual bloom shadow) as everywhere else. There is no live
square marker in the file.

Four call sites were relying on `Eyebrow`'s default (`marker: 'square'`)
because nothing had caught this: `Services.vue` (page header), `Insights/
Index.vue`, `Contact.vue`, `Work/Index.vue` — all four page-header eyebrows
were rendering a flat, unbordered diamond instead of the glowing dot every
other eyebrow in the file (all seven `SectionHeading` consumers, which
already pass `marker="dot"`, plus Services' own feature-bullet eyebrows)
correctly shows. Fixed all four call sites; corrected `Eyebrow.vue`'s
docblock rather than leave the false "two intentional styles" claim standing.

**Lesson for the ledger:** a component's own docblock asserting "the frame
does X" is a claim like any other in this codebase and can go stale — this
one was caught only by re-fetching the exact node the docblock cited as
proof, not by trusting the citation. Worth doing that whenever a docblock's
claim and a fresh fetch disagree, rather than assuming the fresh fetch is the
one-off exception.

## G39 — Work/Show.vue's three remaining card treatments; three real misses  (2026-08-24)  (severity: medium)
Continuing the per-value pass onto Work show's case-study page — the three
pieces its own docblock had described but this pass had not yet re-measured.

**Goals/deliverables cards (`423:4973`)** — three misses at once: padding was
uniform `p-6` (24px all sides) where the frame draws `px-24/py-56` (asymmetric,
56px top/bottom); the gap between the numeral and the title/body block was
`gap-6` (24px) where the frame draws `gap-40`; the title/body gap itself was
`gap-2` (8px) where the frame draws `gap-24`. The background was also flat
`bg-black` where the frame draws the same "20% black wash + raking brand glow"
treatment used elsewhere in the file (here `-42.92deg`, 0→8%) — not `.surface-
glow` (that class also bakes in an 80% ink-1000 base this card doesn't have),
so it's inline styles on top of `bg-black`. All four fixed. Caught one own
mistake making this edit: first pass swapped `items-start` for `items-end` to
chase the frame's raw (RTL-unaware) `items-end` label, which would have
flipped the card's alignment — this codebase's convention is exactly the
opposite (logical `items-start` = inline-start = right under RTL), confirmed
by the identical pattern in the sibling strategy-quadrant `<li>` two sections
down. Reverted before committing.

**Strategy quadrant (`428:5058` etc.)** — checked clean: title 24/500 `ink-50`,
body 18/400 `ink-200`, internal gap-8, all matched. (`428:5044`, the "چرا
سیزده" node near it, is confirmed stray Services-page content per the
existing G19 note — not part of this section, re-verified not to be a
component in disguise.)

**Results tiles (`615:6133` etc.)** — two misses: padding was `p-6` (24px)
where the frame draws `px-32/py-16`; the label/value gap was `gap-3` (12px)
where the frame draws `gap-8`. The label colour was also `ink-900` (#2C2C2C)
where the frame draws `#393637` — `warm-900`, the same cool/warm ramp
confusion as G35, now a fourth occurrence, on a fourth different gold/cream
surface. All three fixed.

**Next-project link (`430:5212`)** — one miss: the gap between the "پروژه
بعدی" label and the arrow+title row was `gap-3` (12px) where the frame draws
`gap-32`. Fixed. Arrow size, inter-icon gap, and both text styles already
matched.

Three real fixes now found in every Work-show sub-block checked this pass
(goals/deliverables, results, next-project) against one clean one (strategy).
`warm-*`/`ink-*` confusion is now 4 for 4 on gold/cream surfaces across the
whole file — worth treating as close to certain on any remaining unchecked
card with a cream/gold ground, not just a pattern to watch for.


## G40 — Contact's whole grid layout was mirrored left-right vs the frame  (2026-08-24)  (severity: high)
User reported `/fa/contact` as visibly wrong. Screenshot diff (live Playwright
render vs. a fresh `get_screenshot`/`get_metadata` pull of `279:6325`) found a
single root cause hitting four separate rows on the page: this page's
`dir="rtl"` puts CSS Grid/flex track 1 at the physical **right**, but every
two-up row on this page had its first DOM child authored as whatever sits on
the frame's physical **left** — the opposite assumption. Confirmed empirically
(cropped screenshots of the live render vs. the frame, field-label by
field-label) before touching code, not just reasoned from the CSS spec.

Four spots fixed, all the same pattern — reorder DOM children so the one the
frame places physically right renders first:
- Cards row (`279:6409` details / `279:6439` form): frame is LEFT=details(423)/
  RIGHT=form(801); form now comes first in the DOM, `grid-cols-[423fr_801fr]`
  flipped to `[801fr_423fr]`.
- Name/brand field pair (`279:6404`): frame is RIGHT=name/LEFT=brand; name now
  first.
- Phone/service field pair (`279:6406`): frame is RIGHT=phone/LEFT=service;
  phone now first.
- Social row (`279:6485`): frame is LEFT=icons/RIGHT=follow-text; the
  follow-text `<p>` now first.

Not touched: field/card *content* (icon set, i18n copy) differs from the raw
Figma placeholder text in places — that's seeded/translated content via
`HasTranslations`, not a layout defect, and out of scope here.

Worth a pass on other pages for the same pattern: any `grid-cols-[Xfr_Yfr]` or
plain `flex-row` two-up layout authored by transcribing the frame's left-to-
right layer order verbatim is a candidate, since that's exactly what produced
all four misses here.

## G41 — SocialIcon: missing YouTube glyph, and all five glyphs were the wrong colour  (2026-08-24)  (severity: medium)
Follow-up on G40 — user flagged the social icon row specifically after the
layout fix. Two real defects in `SocialIcon.vue` (`279:6487`–`279:6504`),
never previously audited (no prior GAPS/MANIFEST entry references it):

1. `youtube` was never a key in the component's `glyphs` map, so it fell
   through to the "unmapped platform" fallback and rendered a bare letter
   "Y" instead of an icon — visible in the live screenshot as a plain glyph
   where a play-button icon belongs. Pulled the actual glyph via
   `download_assets` on `279:6504`, stripped the baked-in chip-border path
   (the component redraws its own 56px/border-ink-400 chip in CSS — keeping
   the export's border path would have doubled it), kept just the two
   `Vector`/`Vector_2` paths, and added `resources/images/sizdah/social/
   youtube.svg` + the `youtube` map entry.
2. All five glyphs (`instagram`, `linkedin`, `whatsapp`, `x`, `telegram`)
   were `fill="#D0D0D0"` (`ink-200`) where the frame draws every glyph in
   `brand`/`#F8B937` — confirmed by cropping the same pixel region from both
   the Figma screenshot and the live render and comparing (live crop came
   back pure grayscale via `identify`, Figma crop didn't). Recoloured all
   five existing SVGs plus the new youtube one to `#F8B937`. Chip border
   stays `ink-400` — only the glyph fill was wrong.

`SocialIcon` is only consumed by `Contact.vue`, so no other page was
affected.

## G42 — `inset-block-start/end` and `inset-inline-start/end` were never real classes; sitewide  (2026-08-24)  (severity: high)
Follow-up on G40/G41 — user pointed at the phone field's flag prefix on
Contact specifically ("پرچم‌ها... این input"). Inspecting computed styles
with Playwright (not just screenshots) showed the flag/+968 prefix had
`top: 0px` plus a `-translate-y(-10px)` — i.e. it was floating 10px *above*
its own container, overlapping the label above, not merely off-center.

Root cause: `tailwindcss-logical` (`node_modules/tailwindcss-logical/
plugins/inset.js`) registers `block-start-*` / `block-end-*` / `inline-
start-*` / `inline-end-*` (and the shorthands `inset-block`/`inset-inline`).
It does **not** register `inset-block-start-*` or `inset-inline-start/end-*`
— those read like the literal CSS logical property names but aren't classes
this plugin (or core Tailwind) generates. Every usage silently compiled to
no rule at all, so the browser fell back to each element's static position.
For a 24px field icon in a 47.5px input this reads as "a bit high but still
inside the pill," which is why it survived every earlier per-value GAPS
pass; for the phone field's 20px-tall flag+code span the same math pushes
it far enough to visibly clear the input's top edge.

Grepped the whole `resources/js` tree — this typo was in **every** instance
across the codebase (13 for `inset-inline-*`, 12 for `inset-block-*`, 0
correct usages found anywhere), not just Contact:
`About.vue`, `Contact.vue`, `Home.vue`, `Services.vue`, `Work/Index.vue`,
`Work/Show.vue`, `Insights/Index.vue`, `Insights/Show.vue`, and
`Components/ProjectShowcase.vue`. Fixed by dropping the erroneous `inset-`
prefix everywhere (`inset-block-start-1/2` → `block-start-1/2`, etc.) —
a pure rename to the plugin's actual utility names, no geometry changed.
Confirmed via Playwright computed-style check that Contact's phone-flag and
field-icon spans now center correctly; spot-screenshotted Home, About and
Services (the grid-mesh background usages) and saw no regressions — those
meshes are subtle/dark-on-dark so the fix mostly restores an intended small
offset rather than changing anything dramatic.

Legitimate, unaffected: `inset-inline-0` (Contact's services dropdown) and
plain `top-full`/`top-1/2` pairings — those aren't this typo.

## G43 — Phone field: real searchable country picker (feature, not a fidelity fix)  (2026-08-24)
User asked for the phone field's flag to become a working country picker with
search, not just a static Oman flag. `279:6406` only ever depicts the Oman
default with a chevron affordance — the frame doesn't specify a dropdown's
contents, so this is this project's own addition layered on that affordance,
not a gap the frame resolves. Noted here so it isn't later mistaken for
something the frame specified and the rebuild missed.

Built:
- `resources/js/lib/countries.ts` — 248 rows (iso2, E.164 dial code, Persian
  + English name), reduced from the mledoze/countries dataset (MIT). Shared
  NANP codes (+1 for US/CA) keep just the root; countries with a distinct
  single suffix (e.g. +1268 Antigua) get the full code — matches how people
  actually search/dial. Flags are Unicode regional-indicator emoji derived
  from `iso2`, not per-country assets (~250 SVGs isn't practical).
- `Components/CountrySelect.vue` — flag+chevron trigger, `v-model`d country;
  opens a search box + scrollable list, filters on Persian name, English
  name, or dial-code digits.
- `Contact.vue`'s phone field rebuilt as a flex pill (dial code + digits on
  the growing side, `CountrySelect` on the fixed side) instead of the old
  absolute-overlay-on-a-fixed-`ps-[92px]` hack — that hack would have broken
  the instant a 4-digit dial code got selected. `form.phone` is assembled as
  `"{dialCode} {digits}"` only at submit time; `ContactSubmissionRequest`
  already validates phone as one loose string for exactly this reason (see
  its docblock — "the design ships a country picker") so no backend change
  was needed.

Two bugs caught and fixed before landing, both via Playwright (computed
styles / bounding boxes), not just screenshots:
1. The pill wrapper had `overflow-hidden` (leftover defensive styling,
   nothing inside actually needed it) — clipped the dropdown panel, which is
   a positioned descendant. Removed.
2. The panel anchored `inline-end-0` rendered 74px past the viewport's right
   edge, because this field sits near the page's own right edge under RTL.
   Anchored `inline-start-0` instead so it opens toward the field's larger
   side.

`en`/`ar` lang files got the two new `forms.contact.country_search` /
`country_not_found` keys too, kept parallel per the fa-only note in
CLAUDE.md even though those locales aren't routed.

## G44 — Services rebuilt against measured boxes; nine real misses on one page  (2026-08-27)  (severity: HIGH — the image column was on the wrong side of all four blocks)

First per-value pass over Services (308:4492) — PROGRESS had it listed as
"G29's flourish work only", i.e. never checked below the structural tier.
Measured every node with `get_metadata` (real bounding boxes, not the
`get_figma_data` layout summary) and then re-measured the built page with
Playwright bounding boxes at 1440x1000, comparing absolute coordinates.

Nine defects, in rough order of how much they showed:

1. **The image was on the wrong side of every block.** The frame alternates
   img-left / img-right / img-left / img-right. The page keyed the image to
   `index % 2 === 0 ? 'lg:order-first' : 'lg:order-last'` — but the document
   is RTL, where the *first* flex item lands at the **right**. So every
   block was mirrored. Now `order-last` on even indices. This is the same
   category as G40 (Contact's mirrored grid): an `order`/`start` decision
   reasoned about in LTR terms on an RTL page.
2. **Block rhythm was 96px everywhere; the frame is 200.** The page put
   header, all four blocks, the sparks and the CTA card in one
   `flex flex-col gap-24`. Frame: header→blocks 140, block↔block 200,
   blocks→CTA 224, CTA→footer 224 (315:4945 children sit at y 0/804/1608/
   2412; 321:5085's are at 0/3240/3976.86).
3. **Column widths were a 50/50 grid.** The frame is img 604 | gap 135 |
   copy 505.313, `justify-center` inside the 1248 track (1244.313 wide, so
   1.84 slack each side). Was `lg:grid-cols-2 lg:gap-16` — i.e. both columns
   ~592 and the gap 71px short.
4. **The numeral's 145px line box was being collapsed to leading-1.** The
   previous docblock called the 2.27 leading "a Maneli metric artefact
   rather than a layout intent" and set the numerals on leading-1. It is a
   layout intent: the copy column is vertically centred against the 604
   image (y=34 in block 1, y=54.5 in the rest), so dropping 81px off the
   numeral box lifts the whole title/desc/features stack ~40px against the
   photograph. `leading-[145px]` restores it and is font-independent, so the
   Maneli substitution no longer leaks into the layout.
5. **Image corner radius was 16px, frame says 8.** `rounded-lg` is 16 in
   this config; the img nodes are `borderRadius=8px` → `rounded-sm`.
6. **The title rule was a straight 2px CSS border.** 315:4856 is a vector,
   and it is the *same* node template (EL-0d4ea584) as the header's active
   nav underline — i.e. `shared/nav-underline.svg`, already in the repo and
   already used by `AppHeader`. Now reuses it with the header's own
   `h-[2px]` span + `-top-[1.7px] h-[4.71px]` treatment.
7. **The sparks were in flow.** 511:9195/9200 and 322:5242 are page-level
   absolute decorations that sit ~20px *above* the top edge of blocks 2-4
   and overlap them (spark y 1372 vs block y 1391). Rendering them as flow
   items between blocks both mis-placed them and perturbed the 200px
   rhythm. Now absolutely positioned, centred on the track.
8. **The mesh was the wrong size and inset.** 511:9147 is 1200x872 at
   (120, 202) — inset 24 from both edges of the 1248 content track, not
   flush to it, and it was rendering 1248 wide. Note `.container-sizdah` is
   the full 1440 box with the gutter as *padding*, so an absolute child
   resolves against the padding box: the inset is gutter + 24, not 24.
9. **The ring was `w-40` (160px) and centred on the glyph.** Frame is
   163x109.12. Centring on the glyph is wrong here for a font reason:
   Idealist sets "01" at 37px where Maneli sets it at ~80, so centring on
   the substitute dragged the ring ~20px toward the column edge. Anchored
   instead off the copy column's start edge, which the frame overhangs by
   35.85/40.85/38.85/43.85 across the four blocks → 40.

Copy was also stale — the seeded Persian predates this generation of the
frame. Reseeded verbatim from the text runs: the page headline is now
"ما برای برند شما سیستم می‌سازیم فرا تر از یک خدمت" (was "ما سیستم
می‌سازیم، نه صرفاً خدمات"), service 03 is "مارکتینگ" (was "طراحی
بازاریابی"), and all four descriptions + feature lists are replaced. Two
frame typos kept verbatim per the project's usual practice: "فرا تر" as two
words in the headline, and "شبکه های" without ZWNJ in service 04's title.
`en`/`ar` are untouched and still the older derived copy (G10).

Verified after the fixes, absolute coordinates, rendered vs frame:

| node | frame | rendered |
| --- | --- | --- |
| header block | y=188 h=259 | y=188 h=258.8 |
| h1 / lede | y=273 h=122 / y=419 h=28 | y=273 h=121.9 / y=419 h=27.9 |
| blocks | y=587/1391/2195/2999, 604 tall | identical |
| img x (per block) | 97.84 / 738.16 / 97.84 / 738.16 | 98 / 738 / 98 / 738 |
| copy col b1 | x=836.84 y=621 505.31x536 | x=837 y=621 505.3x535.7 |
| numeral box | y=621 h=145 | y=621 h=145 |
| title / desc / feat0 | y=774 / 862 / 968 | y=774 / 862 / 968 |
| mesh | (120, 202) 1200x872 | (120, 202) 1200x872 |
| CTA card | y=3827 1248x512.86 | y=3827 1248x513 |
| rings | (1215,641) (481,1458) (1218,2265) (484,3065) | (1219,639) (480,1463) (1219,2267) (480,3071) |
| sparks | y 1372 / 2176 / 2978 | y 1371 / 2175 / 2979 |

The rings land within 6px on both axes; the frame's own four rings scatter
by ±3 against each other, so that is inside the design's hand-placed noise.
No horizontal overflow at 390 / 768 / 1024 / 1280 / 1440.

### Deviations kept, and two open questions this raised

- The header block hugs at 676 rather than the frame's 638. 638 is nothing
  but the lede's single-line hug in Peyda; Vazirmatn needs 676 for the same
  run, so pinning 638 wrapped a line the frame sets on one. The headline is
  capped at 638 so it keeps the frame's two-line break, and the block hugs
  the lede. Block centre is still 720 = frame centre.
- **OPEN: `.section-first` may be 8px short site-wide.** This frame puts the
  page-header eyebrow's top edge at y=188; `.section-first` clears the 108px
  header by 72 and lands at 180. Overridden locally (`md:pt-[188px]`) rather
  than in the shared utility, because only this frame has been measured at
  this tier — an 8px shift on every page off one sample is not worth the
  regression risk. Worth checking About/Insights/Work/Contact headers and,
  if 188 is universal, moving it into `.section-first` and dropping the
  override here.
- **OPEN: the eyebrow marker may sit ~6px too high, site-wide.** In all ~18
  "small title" instances on this frame the marker rect's *top* is at
  exactly 50% of the row height (12.5 in a 25px row, 22.5 in a 45px row),
  not optically centred as `.eyebrow`'s `items-center` renders it. Also,
  the service feature dots stroke `White` (#F1F1F1) where the page-header
  eyebrow strokes `Black/800` — `.eyebrow--dot` hardcodes the ink rim for
  both. Left alone deliberately: `.eyebrow`/`.eyebrow--lg` are used on
  Home, Contact, Insights, Work and `StartTogetherCard`, all signed off in
  earlier passes, and a low-confidence read off a 1:1 render is not grounds
  for moving every eyebrow dot on the site. Needs a second frame sampled
  before anyone acts on it.
- Still not placed, unchanged from G29: the "Group 21" badge (315:4998) and
  the ~20x25 mark at 322:5230, which sits mostly above the frame's own top
  edge (y=-18.6 of 25 tall) and is treated as clipped.

## G45 — The eyebrow marker: one false alarm, one real system, one outlier  (2026-08-30)  (severity: medium)

G44 left two site-wide questions open. Both are now closed, by reading all
seven "small title" marker instances in the file rather than one.

### (a) `.section-first` is NOT 8px short  [CLOSED — no change]
G44 saw the Services page header eyebrow at frame y=188 where the utility puts
it at 180, and asked whether 180 was wrong everywhere. It is not. Contact
(`279:6398`) puts its header block at y=180 exactly, and Work index
(`222:2461`) and Insights index (`268:5234`) both sit in blocks that start at
180. Services is the single frame at 188 and already carries a local
`md:pt-[188px]` override. `.section-first` stays at 132/180.

### (b) The marker does NOT sit 6px high  [CLOSED — no change, and a trap recorded]
G44 suspected the marker was ~6px too high because Figma reports the `obj`
node at `y = boxHeight / 2` (22.5 in a 45px box, 12.5 in a 25px box) while we
centre it. Reading that as "AABB top" makes the marker look 5.66px low in the
frame — 5.66 being half of 11.313, the AABB of an 8px square rotated 45°.

**Figma reports a rotated node's x/y as its CENTRE, not its bounding-box top
left.** Confirmed by pixel measurement: rendering `315:4859` and locating the
gold fill puts the marker's centre at node y=12.5 in a 25px box — dead centre.
`items-center` is correct. Do not "fix" this.

### (c) The rim: a real four-instance system, and one dissenter  [FIXED]
Every marker is the same 8px disc — `bg #F8B937`, `rounded-round`, the
symmetric `±2px 12px rgba(245,185,64,.7)` bloom. Only the rim varies:

| Node | Where | Rim |
|---|---|---|
| `450:6716` | Contact page header | 1px `#FEFBF5` |
| `450:6712` | Work index page header | 1px `#FEFBF5` |
| `462:8519` | Insights index page header | 1px `#FEFBF5` |
| `450:6700` | Home testimonials heading | 1px `#FEFBF5` |
| `268:3036` | Home services band (cream ground) | 1px `#434343` |
| `315:4861` | Services feature bullets (`--lg`) | 2px `#F1F1F1` |
| `309:4757` | **Services page header** | **2px `#434343`** |

The code shipped `border-2 border-ink-800` as the default — i.e. it had
generalised `309:4757`, the one node that agrees with nothing else, across the
whole site. Now: `.eyebrow--dot` is 1px Yellow/50 (the four-instance value),
`.eyebrow--dot-ink` is the cream band's 1px Black/800, and
`.eyebrow--lg.eyebrow--dot` is the feature bullets' 2px White.

**DECISION — Services page header normalised, not reproduced.** `309:4757` is
a detached node; the other three page headers are instances of the shared
`450:66xx` component. A 2px dark-grey rim on a dark ground, on one page only,
reads as art that was never refreshed rather than intent. It now takes the
system default. Reverse by giving that one call site its own variant if a
human disagrees.

### (d) Dead `square` marker removed
`Eyebrow` still defaulted to `marker="square"`, a diamond no frame draws (G38
found this; the default was never changed, only the call sites). Every call
site passed `marker="dot"`. The prop is gone and the disc is unconditional.

### (e) Section-heading eyebrow gap: 24 vs 48  [FIXED]
The gap under the eyebrow is 24 on projects (`268:3461`), the services band
(`502:5424`) and why-us (`268:3475`), and **48** on testimonials (`268:3721`)
and insights (`268:3797`) — the latter two being two copies of one block
("Frame 96355"). The code used 24 everywhere. `SectionHeading` now takes a
`gap` prop; those two call sites pass `gap="lg"`. Page headers were already
right (40 on Contact/Work/Insights, per `279:6399` / `222:2461` / `268:5234`).

## G46 — The responsive suite was measuring an unmounted page  (2026-08-30)  (severity: HIGH — every layout guarantee it made was vacuous)

`tests/Browser/responsive.spec.ts` asserted "no horizontal overflow" on six
routes across seven breakpoints, and had passed for as long as it existed.

It was measuring nothing. `page.goto` resolves on `load`; in dev, Vite injects
the Vue bundle and its stylesheet *after* that. Measured at `load` the document
is a bare `#app` shell — one stylesheet, no page markup, and
`document.documentElement.scrollWidth === clientWidth` trivially. Instrumenting
the same navigation shows it directly:

```
at load        : overflow=0px   styleSheets=1  whyUsGrid=null
at networkidle : overflow=147px styleSheets=2  whyUsGrid=680px
```

All navigations now go through a `visit()` helper that waits for `networkidle`
and for `main` + `footer` to attach. Three real defects surfaced the moment it
did:

1. **`WhyUsGrid` overflowed 147px from 1024–1279px.** The band is a 401px
   heading column beside a fixed 680px artwork — 1081px of content box — gated
   at `lg`, where `container-sizdah` only offers 896. Moved to `xl` (1280 gives
   1088). Between 1024 and 1279 the existing `sm:grid-cols-2` fallback carries
   it; the decorative rules, which only mean anything inside the absolute
   composition, drop with it. Desktop geometry is untouched — the change is
   purely which breakpoint the same classes fire at.
2. **Contact overflowed below ~400px.** Four `min-width: auto` floors: a bare
   `<input>`'s ~245px intrinsic width, the services trigger's 276px min-content
   (its `truncate` span could not shrink without `min-w-0`), and both cards as
   grid items. The narrowest column the card could form was 346px.
3. **Home overflowed at 320px.** `InsightsShowcase`'s list thumbnail is a fixed
   `size-[190px] shrink-0`; 190 + 24 gap + copy left a 291px floor in a 280px
   track. It now scales to 120px below `sm`.

Also fixed while here:

- **The suite tested `/en/*`** — routes that stopped resolving with the fa-only
  decision (G15). Ten of its assertions had been failing on missing routes;
  they now run against `/fa/*`, plus the two legal pages. The language-switcher
  test is gone (the switcher collapses under fa-only by design) and is replaced
  by two tests for what the contract actually is now: a bare path redirects
  into `fa` and renders RTL, and a deactivated prefix 404s.
- **`mobile-320` added.** Below anything the file designs for, so it exists
  purely to catch min-width floors.
- **`webServer` added to the config.** `artisan serve` is single-process unless
  `PHP_CLI_SERVER_WORKERS` is set, so `fullyParallel` starved it and three
  tests failed with navigation timeouts that read as layout defects. The suite
  now boots its own server with 8 workers and reuses a running one.
- **A page-error/failed-request test per route**, which is where
  `.scratch-navbar.mjs` went. Both `.scratch-*.mjs` files were committed
  debug one-offs: they imported `playwright` (not a dependency of this repo),
  hardcoded `/usr/bin/chromium`, drove `/en/*`, and nothing referenced them.
  Deleted.

**mobile-390 cannot run on this machine.** It is the only WebKit project and
the host is missing WebKit's system libraries; it fails to launch, which
presents as 12 layout failures. `sudo npx playwright install-deps` fixes it.
Every other project passes: **137 passed, 0 failed, 3 skipped.**

## G47 — Home services/orbit and testimonials: pixel-level re-check; one real miss  (2026-09-01)  (severity: medium)

First item of the Phase 7 value-check tier (per-node `get_design_context`, not
just structural/`get_metadata` sweeps like G32). Two areas re-measured in full:

**Services/orbit band (`268:3032`).** Every icon slot position, connector-line
bounding box, and decorative doodle (grid hatch, both crosses, dot cluster,
scribble, the "lost level 1" wordmark overlap) recomputed from the frame's raw
px/inset values against `ServiceOrbit.vue`'s percentage offsets. All matched
to sub-pixel precision (largest delta ~0.4px, from the frame's own rotated-line
`hypot()` math). **No gap** — G32's "did not pull full `get_design_context`,
trusted the component" is now upgraded to verified.

**Testimonials heading + `TestimonialCard` (`268:3720`, `546:7528`).** Checked
the eyebrow rim, heading typography/widths, card root (border/radius/padding/
gap), and the `.testimonial-wash` ground including its hairline pitch — this
last one looked like a bug at first (`GAPS` G25 documents the source texture's
native pitch as 54.4px on a 442px image, but the shipped CSS uses 40.6px).
Downloaded the actual raw texture PNG (confirmed 442×442, lines repeating
every ~54.4px by direct pixel sampling) and recomputed the `object-cover`
scale: the card is taller than it is wide (300×~336), so height — not width —
is the binding dimension, giving `54.4 × (336/442) ≈ 41.3px`, which rounds to
the shipped 40.6. **Not a bug** — confirmed correct, no change.

**One real miss, fixed: `quote-mark.svg` was vertically mirrored.** Downloaded
Figma's own SVG export of node `546:7500` ("Group 23", the quote glyph) and
diffed it pixel-for-pixel against `resources/images/sizdah/home/quote-mark.svg`.
The shipped asset has the round blob at the *bottom* of each comma with the
tail curling up-left; Figma's actual glyph has the blob at the *top* with the
tail hanging down — a top/bottom mirror of the same shape, confirmed by
rendering both at 4x and comparing side by side. The design-context React
reference hints at this: it wraps the glyph in a `-scale-y-100` transform,
which only makes sense if the underlying asset needs a flip to reach the
frame's true orientation. Fixed by replacing the SVG's path data with Figma's
exported paths (same viewBox, same fill) rather than adding a CSS transform —
the file itself was simply wrong, so this is a source-of-truth correction, not
a runtime compensation. Single call site (`TestimonialCard.vue`); the hover
state's `brightness-0` filter is shape-agnostic so it needed no change.
Verified live via a `composer run dev` + Playwright screenshot of `/fa` — the
rendered glyph now matches the Figma reference exactly.

The `quote-scribble.svg` sibling asset (the pencil doodle above the avatar
row) was cross-checked the same way and matches Figma exactly — not every
small SVG in this card was wrong, just the one.

## G48 — 404 page rendered a blank screen for its one real trigger: no matching route at all  (2026-09-01)  (severity: CRITICAL)

Second value-check-tier item: the 404 page (`266:2825`). Static comparison
against `Error.vue` confirmed the frame faithfully — illustration at
725×544 with the documented -40px overlap, 506px text column, gap-32/gap-16
rhythm, `heading-xl`/`title-sm` type tokens and `brand-50`/`ink-200` colours
all matched exactly. But per this ledger's own rule (no page is done without
running it), loaded it in an actual browser rather than stopping at the
frame diff — and it rendered as a **fully blank dark screen**. No header, no
illustration, no copy, no CTA.

**Root cause: this is the one page whose defining trait — a request that
matches no route — means `SetLocale` (a route middleware) never runs.**
`SetLocale::handle()` is what calls `URL::defaults(['locale' => $locale])`,
which is what seeds Ziggy's client-side `defaults` object. A real 404 (as
opposed to a 403/419/429/500/503, which all occur on an *already-matched*
route where `SetLocale` already ran before the failure) matches nothing at
all, so the middleware pipeline for that route never executes. Confirmed by
diffing the Ziggy blob between two live responses:

```
/fa                              -> "defaults":{"locale":"fa"}
/fa/<nonexistent path>            -> "defaults":{}
```

`CtaButton`'s "back to home" button calls `route('home')`, a locale-scoped
named route. With no default, Ziggy's client-side resolver throws
synchronously (`Ziggy error: 'locale' parameter is required for route
'home'.`) while evaluating the template, which is an unrecoverable Vue
render error for the whole `Error` component — hence the blank page. The
`<html lang dir>` attributes still came out correct by coincidence, not
correctness: Blade's root view resolves those independently of
`URL::defaults()`, and the site currently supports exactly one locale, so
its independent fallback happens to land on the same value.

**Fix:** `SetLocale::resolve()` (locale-lookup logic: route segment ->
session -> `Accept-Language` -> configured default) made `public`; the
exception responder in `bootstrap/app.php` now calls it directly and sets
`App::setLocale()` / `URL::defaults()` itself, immediately before rendering
`Error`, for every status in its list (idempotent when `SetLocale` already
ran on a matched route — same value, set twice). Verified: Ziggy's
`defaults` blob now carries `{"locale":"fa"}` on the 404 response, and a
full Playwright pass shows the page rendering completely — header,
illustration, heading, CTA, footer — with zero console/page errors beyond
the browser's own (expected) "404 Not Found" resource-load log for the
bad URL itself. `/fa`, `/fa/about`, `/fa/contact` re-checked after the
change to confirm no regression on matched routes.

**Why prior passes missed this:** G20/G31 both verified this page by
reading `Error.vue` against the frame's node tree — correct as far as static
comparison goes, but this bug is invisible to any method that doesn't
actually load the page in a browser as its real, unmatched-route trigger.
Neither Pest (blocked, no `pdo_sqlite` on this host) nor the Playwright
`responsive.spec.ts` suite (checks known routes, not a deliberately-bad one)
exercised this path — it does now: added "a genuine 404 renders the Error
page, not a blank screen" next to the existing locale-routing tests,
asserting the heading renders, the back-home link resolves to `/fa`, and
zero `pageerror`s fire. Full `desktop-1440` project re-run after the fix:
20 passed, 1 skipped (the mobile-menu test, correctly inapplicable at this
viewport), 0 failed.

## G49 — Legal (both frames): 188px content-top confirmed as a real second data point, plus one missing decorative seal  (2026-09-01)  (severity: low)

Third value-check-tier item: Legal privacy (`279:5924`) and terms (`281:6773`),
both re-measured with `get_design_context` (they share one Vue component,
`Legal.vue`, so both frames were pulled to be sure they agree).

**Type scale, rhythm, column widths — all already exact.** `heading-lg`
(30/1.27/500), `title-md` (20/1.25/500), `display-lg` (48/1.27/700),
`max-w-measure` (612px), the 96/64/24/32 spacing rhythm in `.rich-prose` —
every one matched the frame with no drift. This confirms G14's original
type-scale fix rather than finding anything new.

**Both Legal frames put the title block's top edge at y=188, not 180.** G44
found Services at 188 and flagged it as maybe-universal; G45 checked three
more frames (Contact, Work index, Insights index), found them all at 180, and
closed it as "Services is a genuine outlier." That verdict was reached without
checking Legal. With both Legal frames now independently confirmed at 188,
the site is a 3-3 split (Services + privacy + terms at 188, vs.
Contact/Work index/Insights index at 180) — not clearly one outlier anymore,
but also not clearly universal either. Kept `.section-first` at 180 and added
the same kind of local override Services already carries
(`md:pt-[188px]` on `Legal.vue`'s root), rather than reopening the shared
token on a coin-flip. If a future pass finds a fourth data point either way,
this should be revisited as a real pattern rather than per-page overrides.

**One missing decorative element: a hand-drawn seal/badge (279:5927 privacy,
281:6776 terms — "image 127 [Vectorized]"), identical on both frames.**
Downloaded it directly: a detailed hand-drawn circular badge with a
checkmark-heart centre, filled `#202024`/`#222326` against the page's
`#141414` ground — full opacity, but only ~4% brightness contrast, so it
reads as a barely-visible watermark rather than a focal decoration (confirmed
by rendering it recoloured at 12x to see the actual linework, then checking
the real near-invisible contrast against the page background). Saved as
`resources/images/sizdah/legal/trust-badge.svg` and added to `Legal.vue`.

Positioning required adapting, not copying, Figma's absolute coordinates:
both frames place it at the exact same page-pixel (physical left
`calc(66.67%-16px)` of the 1440 frame, y=1446) despite having different total
heights (2818 vs 2443) — i.e. it's an ambient accent that was never
re-positioned per page, not something tied to a specific heading in the
admin-authored rich text. Reproduced as `calc(66.67%-16px)` inside a
`max-w-frame`-width wrapper (the same "don't nest a frame-relative offset in
`container-sizdah`" pattern `ServiceOrbit.vue` already documents) and a
content-relative `top-[1258px]` (1446 minus the frame's y=188), hidden below
`xl` where the container's padding stops matching the frame's 96px gutter.
Verified live via Playwright at 1440px (visible, correctly faint) and 390px
(hidden, no overflow, no console errors).

**Not a frontend gap, but worth recording: the live CMS copy has drifted from
the Figma mock's placeholder text.** The frame's title, address and email
("سیاست حفظ حریم خصوصی", "کرمان، ایران", "Sizdahmarketing@gmail.com") differ
from what the running site actually renders ("حریم خصوصی", "مسقط، عمان",
"Sahramarketing@gmail.com" — the last two are literal Sahra-era leftovers).
`Legal.vue` correctly renders whatever `LegalController` hands it; this is
Filament-authored content, not a template bug, and per the "don't hardcode
API responses into the UI" rule it is not something to patch here. Flagged
for whoever owns Filament content — same class of issue as G10's en/ar
review, not a rebuild-scope item.

## G50 — FilterChips: the two frames genuinely disagree on the active-chip colour, not just "an old note being wrong"  (2026-09-01)  (severity: medium)

Fourth value-check-tier item: Work index (`222:1989`), which shares
`FilterChips.vue` with Insights index. `FilterChips`'s own docblock asserted
"both frames agree" on every property including the active state. Re-pulling
both frames directly disproves that for one specific property:

| | insights (`268:5250`) | projects (`222:2472`) |
|---|---|---|
| fill | `#2C2C2C` (ink-900) | `#F8B937` (brand, solid) |
| border | 3px `#F8B937` (brand) | 3px `#F8B937` (brand) — agrees |
| label colour | `#F8B937` (brand) | `#141414` (ink-1000) |
| radius / inactive state / everything else | identical | identical |

This isn't a case of the earlier note being stale — both frames were pulled
fresh in this same session and simply draw the active pill differently:
insights inverts to a dark chip with a glowing brand label, projects inverts
to a solid brand chip with dark label. The component had only ever
implemented the insights version, so Work index's active filter chip was
rendering with the wrong colours (dark fill instead of the frame's solid
yellow).

**Fix:** added a `variant: 'outline' | 'solid'` prop to `FilterChips.vue`
(default `outline`, preserving the existing Insights call site unchanged);
`Work/Index.vue` now passes `variant="solid"`. Verified both consumers live —
screenshotted the actual chip row on `/fa/work` (now solid brand fill, dark
label, matching `222:2472`) and `/fa/insights` (unchanged dark fill, brand
label, matching `268:5250`) side by side, zero console errors on either.

This is also the shared-component regression check the master brief asks
for: `FilterChips` has exactly two consumers (Work index, Insights index),
both were re-verified after the change, and neither shows a defect.
