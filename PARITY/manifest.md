# PARITY — Phase 0 Inventory

**File:** `v1l4ANft5Wtb8wPThyP7P9` ("SizdahMarketing")
**Page:** `0:1` "User Interface" — **the only page in the document**
**Read:** 2026-09-04, via `get_metadata` + `get_figma_data` + `get_screenshot`
**Top-level nodes:** 81

Status vocabulary: `MAPPED` (a Vue file cites this node ID) · `MISSING` (no
code cites it) · `N/A` (not a buildable frame).

---

## 0. Tooling caveat — read this before trusting any tree dump

`get_metadata` **silently elides the children of some large frames** in this
file. It returned `268:2962` (the canonical Home, 1440×9919) as a
self-closing leaf:

```
<frame id="268:2962" name="Home" x="5396" y="-47" width="1440" height="9919" />
```

A direct `get_metadata` call on that node returned the same empty element.
The frame is **not** empty — `get_screenshot` renders a complete page (2318
distinct colors) and `get_figma_data` returns a full subtree (header, hero,
KPI row, six-step grid, FAQ band, footer, …).

**Consequence for Phase 3:** `get_metadata` is usable for the top-level
inventory only. Structure must come from `get_figma_data` / `get_design_context`
per node. Frames affected (reported as leaves, actually populated):
`268:2962`, `772:5193`, `504:7399`.

---

## 1. Page frames → route → Vue file

**Canonical set decided 2026-09-04 by canvas geometry** (user: *"the one next
to the rest of the frames; the others were tests"*). The file has a
**production row at y ≈ −47** and a **test row at y ≈ 8433 with negative x**.
Canonical: Home `268:2962`, Work index `222:1989`, error `266:2825`.
Superseded tests: `450:4908`, `13:2766`, `504:7399`, `226:3080`, `261:2545`,
`82:2059`, `88:2490`. See B1.

All frames are **1440 px wide desktop**. All copy is **Persian**. There is no
`en` or `ar` frame anywhere in the file (see Blocker B2).

| # | Frame | Node ID | W×H | Route | Vue file | Status |
|---|---|---|---|---|---|---|
| 1a | Home | `13:2766` | 1440×11273 | `/{locale}` | [Home.vue](resources/js/Pages/Home.vue) | MAPPED — light variant |
| 1b | Home | `268:2962` | 1440×9919 | `/{locale}` | [Home.vue](resources/js/Pages/Home.vue) | MAPPED — dark, cited by [web.php](routes/web.php) |
| 1c | Home | `772:5193` | 1440×9919 | `/{locale}` | — | **MISSING** — twin of `268:2962` (see B1) |
| 1d | Home | `504:7399` | 1440×11273 | `/{locale}` | — | **MISSING** |
| 1e | Home | `450:4908` | 1440×11273 | `/{locale}` | — | **MISSING** — 88 direct children |
| 2a | projects | `222:1989` | 1440×4656 | `/{locale}/work` | [Work/Index.vue](resources/js/Pages/Work/Index.vue) | MAPPED |
| 2b | projects | `261:2545` | 1440×4656 | `/{locale}/work` | [Work/Index.vue](resources/js/Pages/Work/Index.vue) | MAPPED — twin of `222:1989` |
| 2c | projects | `226:3080` | 1440×1762 | — | — | MISSING — stray draft, excluded by GAPS G52 |
| 3 | case study | `336:5374` | 1440×7403 | `/{locale}/work/{slug}` | [Work/Show.vue](resources/js/Pages/Work/Show.vue) | MAPPED |
| 4 | services | `308:4492` | 1440×**5066** | `/{locale}/services` | [Services.vue](resources/js/Pages/Services.vue) | MAPPED — **height changed**, see §4 |
| 5 | about | `336:5623` | 1440×4021 | `/{locale}/about` | [About.vue](resources/js/Pages/About.vue) | MAPPED |
| 6a | blog list | `268:4158` | 1440×**4502** | `/{locale}/insights` | [Insights/Index.vue](resources/js/Pages/Insights/Index.vue) | MAPPED — **height changed**, see §4 |
| 6b | blog list | `285:4590` | 1440×4844 | `/{locale}/insights/{slug}` | [Insights/Show.vue](resources/js/Pages/Insights/Show.vue) | MAPPED |
| 6c | blog list (modal state) | `416:4959` | 1440×1024 | `/{locale}/insights` | [LeadMagnetModal.vue](resources/js/Components/LeadMagnetModal.vue) | MAPPED |
| 6d | blog list (success state) | `416:5834` | 1440×1024 | `/{locale}/insights` | [LeadMagnetModal.vue](resources/js/Components/LeadMagnetModal.vue) | MAPPED |
| 7 | contact us | `279:6325` | 1440×1755 | `/{locale}/contact` | [Contact.vue](resources/js/Pages/Contact.vue) | MAPPED |
| 8 | privacy policy | `279:5924` | 1440×2818 | `/{locale}/privacy-policy` | [Legal.vue](resources/js/Pages/Legal.vue) | MAPPED |
| 9 | term of use | `281:6773` | 1440×2443 | `/{locale}/terms` | [Legal.vue](resources/js/Pages/Legal.vue) | MAPPED |
| 10a | 404 (Persian, dark) | `266:2825` | 1440×1024 | fallback | [Error.vue](resources/js/Pages/Error.vue) | MAPPED |
| 10b | 404 (Latin, light) | `82:2059` | 1440×1024 | fallback | — | MISSING — superseded |
| 10c | "405" = 404 (light) | `88:2490` | 1440×1024 | fallback | — | MISSING — superseded (G20) |

**10 route-bearing Vue pages exist; every route has a frame.** The unmapped
page frames are all duplicate/superseded variants, not gaps in coverage.

## 2. Component sheet & component sets

| Frame | Node ID | Size | → Vue | Status |
|---|---|---|---|---|
| Components (sheet) | `21:2152` | 1583×1094 | — | container; children below |
| ├ header item | `20:2087` | 100×142 | [AppHeader.vue](resources/js/Layouts/AppHeader.vue) | MAPPED |
| ├ CTA | `21:2164` | 221×310 | [CtaButton.vue](resources/js/Components/CtaButton.vue) | MAPPED |
| ├ KPIs | `71:2145` | 334×153 | [StatCard.vue](resources/js/Components/StatCard.vue) | MAPPED |
| ├ Header | `21:2159` | — | [AppHeader.vue](resources/js/Layouts/AppHeader.vue) | MAPPED |
| ├ project cards | `138:2357` | — | [ProjectShowcase.vue](resources/js/Components/ProjectShowcase.vue) | MAPPED |
| ├ Group 3 | `91:2580` | — | [BrandLogo.vue](resources/js/Components/BrandLogo.vue) | MAPPED |
| └ Frame 96290 | `71:2184` | — | — | **MISSING** |
| blog card | `276:5724` | 652×1140 | [BlogCard.vue](resources/js/Components/BlogCard.vue) | MAPPED |
| card (process step) | `537:5510` | 435×570 | [ProcessStepCard.vue](resources/js/Components/ProcessStepCard.vue) | MAPPED |
| testimonial | `546:7528` | 340×728 | [TestimonialCard.vue](resources/js/Components/TestimonialCard.vue) | MAPPED |
| member card | `598:5732` | 269×701 | [About.vue](resources/js/Pages/About.vue) | MAPPED |
| project post | `226:2730` (symbol) | 612×800 | [ProjectPostCard.vue](resources/js/Components/ProjectPostCard.vue)? | **MISSING** — node ID not cited |
| project post | `261:3074` (instance) | 612×800 | — | **MISSING** |
| Share icons | `690:7085` | 108×196 | [ArticleShare.vue](resources/js/Components/ArticleShare.vue)? | **MISSING** — node ID not cited |

## 3. Off-canvas reference & decorative artboards

Not pages. Export targets for Phase 2, or explicitly out of scope.

| Frame | Node ID | Size | Kind | Status |
|---|---|---|---|---|
| Frame 96312 | `149:2691` | 951×798 | icon sprite sheet, 41 hand-drawn icons | MISSING — **primary icon source, see B4** |
| Frame 96420 | `411:7564` | 1174×1837 | mood board | N/A — G28 |
| Frame 96449 | `537:5561` | 509×759 | icon-tint reference | N/A — G28 |
| Frame 96468 | `598:5751` | 738×2703 | 8 member-hover symbols | N/A — G28 |
| Frame 96469 | `690:7265` | 62×339 | icon combo | MISSING |
| Frame 96395 | `299:7730` | 64×48 | icon combo | MISSING |
| Frame 96396 | `299:7811` | 64×48 | icon combo | MISSING |
| Group (bg) | `292:7397` | 1314×3057 | 30 vectors, **hidden** | MISSING |
| Work Collaboration 3 | `589:5925` | 144×144 | illustration | MISSING |
| Group 42 | `615:6309` | 24×24 | icon | MISSING |
| Group 24 | `279:6524` | 105×81 | logo/mark | MISSING |
| image 132 | `336:5873` | 1672×941 | raster, visible | MISSING |
| No Contact… | `602:5831` | 400×400 | illustration, visible | MISSING |
| No Mail | `602:5833` | 400×400 | illustration, visible | MISSING |
| Data Organisation | `602:5835` | 400×400 | illustration, visible | MISSING |
| Text 1 | `31:2051` | 0×0 | empty text node | N/A |

**36 further top-level nodes** are hidden `rounded-rectangle` raster scrap
(`ChatGPT Image …`, `image 1NN`, `Workflow 2`, `Asking Question 4`). They are
AI-generated source plates parked off-canvas, referenced only through
`[Vectorized]` copies inside page frames. **Not build targets.**

---

## 4. Deltas vs `.figma-sync/MANIFEST.md`

`.figma-sync/` is described in [CLAUDE.md](CLAUDE.md) as the *current,
actively-maintained* record. It has drifted. Verified against the live file:

| Claim in `.figma-sync/MANIFEST.md` | Live file |
|---|---|
| pages `1:2` "Style & Component" and `163:1428` "thinking" exist | **Only one page exists:** `0:1`. Both are gone. |
| services `308:4492` is 1440×**5333** | **1440×5066** (−267) |
| blog list `268:4158` is 1440×**4277** | **1440×4502** (+225) |
| `292:7397` Group is 1200×3000 | **1314.5×3057**, and now `hidden` |
| `607:5839` Workflow 2 [Vectorized] | **absent** from the file |
| `312:4797` Group (1153×1047) | **absent** |
| `615:6039` Group 37 | **absent** |
| `615:6178` Frame 96389 | **absent** |
| 4 Home variants | **5** — `772:5193` is new and unrecorded |
| — | New/unrecorded: `690:7085`, `690:7265`, `299:7730`, `226:2730`, `261:3074`, `336:5873`, `602:5831/5833/5835` |

Absence was confirmed on the top-level node list (complete, 81 nodes,
self-closing elements included), with a positive control — the seven
`21:2152` children all resolve — so these zeros are real, not a parsing
artifact.

`FIGMA/state.json` is stale in a way that matters: it asserts *"No frames
exist for About, Work index or Work show."* All three exist
(`336:5623`, `222:1989`, `336:5374`) and are built.

---

## 5. Codebase baseline against the hard bans

Measured, not assumed:

| Ban | Current state |
|---|---|
| Third-party icon sets | **VIOLATED** — `lucide-vue-next@^0.468.0` in [package.json](package.json), imported in 5 files: [MobileMenu.vue](resources/js/Layouts/MobileMenu.vue), [FlashMessages.vue](resources/js/Components/FlashMessages.vue), [AppFooter.vue](resources/js/Layouts/AppFooter.vue), [AppHeader.vue](resources/js/Layouts/AppHeader.vue), [LeadMagnetModal.vue](resources/js/Components/LeadMagnetModal.vue). See B4 — the Figma file itself contains a component named `lucide-icon` (`13:224`). |
| `resources/js/icons/` exists | **No** — directory absent. 1 inline `<svg>` in the whole tree. |
| Hardcoded hex | 37 matches, but **most are inside docblock comments** documenting the token. Real offenders in class attributes: `border-[#fdeac3]` ([ArticleMeta.vue:56](resources/js/Components/ArticleMeta.vue#L56)), `hover:bg-[#f3b435]` ([ProcessStepCard.vue:69](resources/js/Components/ProcessStepCard.vue#L69)), a `linear-gradient(#fff8eb…)` ([InsightsShowcase.vue:51](resources/js/Components/InsightsShowcase.vue#L51)). Phase 1 will produce the exact list. |
| Default Tailwind palette | **2 matches** — effectively clean. |
| Arbitrary `[Npx]` | 179 occurrences. Not automatically a defect (Figma values are exact px); Phase 1 decides which become tokens. |

---

## 6. Phase 0 conclusion

Inventory is complete and every route already has a frame **and** a Vue file.
This is **not** a greenfield rebuild — it is a parity audit of an existing,
heavily annotated implementation. Six items block a clean run into Phase 1;
they are in [blockers.md](PARITY/blockers.md).
