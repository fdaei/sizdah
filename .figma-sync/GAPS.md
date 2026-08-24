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

