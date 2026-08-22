# Figma Sync — Frame Manifest

Source: file `v1l4ANft5Wtb8wPThyP7P9`, page `0:1` "User Interface" — the
canonical page. The file also carries `1:2` "Style & Component" (token and
type sheets) and `163:1428` "thinking" (scratch, do not build from). See G9.
Every page frame is Persian; there is no en/ar variant of any frame (G6).
Widths are Figma canvas px. Status: `pending` → `in_progress` → `done`.
Canonical picks among duplicates resolved at Phase 5 (see GAPS G2/G5).

## Pages (1440-wide desktop frames)

| # | Frame name | Node ID | Type | Viewport w×h | Route (intended) | Status |
|---|---|---|---|---|---|---|
| 1 | Home | `13:2766` | page (variant) | 1440×11273 | `/{locale}` | pending |
| 1 | Home | `268:2962` | page (variant) | 1440×9919 | `/{locale}` | **done** |
| 1 | Home | `504:7399` | page (variant) | 1440×11273 | `/{locale}` | pending |
| 1 | Home | `450:4908` | page (variant) | 1440×11273 | `/{locale}` | pending |
| 2 | projects (Work index) | `222:1989` | page (variant) | 1440×4656 | `/{locale}/work` | **done** |
| 2 | projects (Work index) | `261:2545` | page (variant) | 1440×4656 | `/{locale}/work` | pending |
| 2 | projects (short/state) | `226:3080` | page? state | 1440×1762 | `/{locale}/work` | pending |
| 3 | case study (Work show) | `336:5374` | page | 1440×7403 | `/{locale}/work/{slug}` | **done** |
| 4 | services | `308:4492` | page | 1440×5333 | `/{locale}/services` | **verified** |
| 5 | about | `336:5623` | page | 1440×4021 | `/{locale}/about` | **done** |
| 6 | blog list (Insights index) | `268:4158` | page (variant) | 1440×4277 | `/{locale}/insights` | **verified** |
| 6 | blog list (Insights show) | `285:4590` | page (variant) | 1440×4844 | `/{locale}/insights/{slug}` | **verified** |
| 6 | blog list (state) | `416:4959` | page? state | 1440×1024 | `/{locale}/insights` | pending |
| 6 | blog list (state) | `416:5834` | page? state | 1440×1024 | `/{locale}/insights` | pending |
| 7 | contact us | `279:6325` | page | 1440×1755 | `/{locale}/contact` | **verified** |
| 8 | privacy policy | `279:5924` | page | 1440×2818 | `/{locale}/privacy-policy` | **done** |
| 9 | term of use | `281:6773` | page | 1440×2443 | `/{locale}/terms` | **done** |
| 10 | 404 (Latin, light) | `82:2059` | error state | 1440×1024 | fallback | superseded |
| 10 | 404 (Persian, dark) | `266:2825` | error state | 1440×1024 | fallback | **verified** |
| 10 | "405" = 404 (Persian, light) | `88:2490` | error state | 1440×1024 | fallback | superseded (G20) |

## Component sheets & card frames

| Frame name | Node ID | Type | Size | Notes | Status |
|---|---|---|---|---|---|
| Components | `21:2152` | component sheet | 1583×1094 | primary DS sheet — mined 2026-08-21 (G25) | **done** |
| header item | `20:2087` | component set | 100×142 | selected / default → `AppHeader` | **done** |
| CTA | `21:2164` | component set | 221×310 | sm/lg × BW/WB → `CtaButton` | **done** |
| KPIs | `71:2145` | component | 334×153 | → `StatCard` | **done** |
| blog card | `276:5724` | component set | 652×1140 | 612 / 400 → `BlogCard` | **done** |
| card | `537:5510` | component set | 435×570 | Default / **hover** → `ProcessStepCard` | **done** |
| Frame 96449 | `537:5561` | component | 509×759 | card variant | pending |
| testimonial | `546:7528` | component set | 340×728 | Default / **hover** → `TestimonialCard` | **done** |
| member card | `598:5732` | component set | 269×701 | Default / Variant2 — inventoried, not diffed | pending |
| Frame 96468 | `598:5751` | component grid | 738×2703 | member/card grid | pending |
| Frame 96420 | `411:7564` | component | 1174×1837 | TBD | pending |
| Frame 96312 | `149:2691` | component | 951×798 | TBD | pending |
| Frame 96396 | `299:7811` | component | 64×48 | small ui | pending |
| Frame 96389 | `615:6178` | component | 741×67 | bar/row | pending |

## Decorative / vector artboards (export as assets, not pages)

| Frame name | Node ID | Size | Kind |
|---|---|---|---|
| Workflow 2 [Vectorized] | `607:5839` | 502×502 | illustration |
| Work Collaboration 3 [Vectorized] | `589:5925` | 144×144 | illustration |
| Group | `312:4797` | 1153×1047 | illustration |
| Group | `292:7397` | 1200×3000 | illustration/bg |
| Group 42 | `615:6309` | 24×24 | icon |
| Group 37 | `615:6039` | 24×24 | icon |
| Group 24 | `279:6524` | 105×81 | logo/mark |

## Status legend (2026-08-21 audit)
- **done** — rebuilt or corrected against the current frame this run.
- **verified** — already built against this exact node ID and re-checked
  *structurally*. **This is not a build status.** G23 found that four pages
  carrying it had the wrong header geometry and the wrong headline size; the
  status only ever meant "renders the right blocks in the right order".
  Re-measure before trusting it.
- **superseded** — a duplicate/twin frame resolved against a canonical one; no
  work is owed.
- **pending** — real work outstanding.

**2026-08-21 (later) — Phase 4 done.** The component sheet had never been read
even though every page had been "verified" against it; see GAPS G25. Every
shared component on the site carried at least one defect, the CTA's 57px
min-height and the footer's inverted colours being the widest-reaching. The file
ships **hover/state variants for most cards** and none had been implemented.

**2026-08-21 status:** no page frame is pending. Work index, case study and Home
were all rebuilt against their current node IDs (the "still DERIVED" note above
was itself stale — both Work pages carry frame-accurate docblocks), and the
error page was already built against `266:2825`. This pass added the three
missing backdrop/wash layers (GAPS G21, G22), corrected the 404 line break, and
resolved the error-frame naming confusion (G20). What remains is the component
sheet and the decorative artboards below, plus the standing gaps in CLAUDE.md
(Services clip-path layers, `arc-rings-*`, no tablet frames).

## Implementation order (simplest → most complex)
Shared layout (header/nav/footer) → DS components (Phase 4) → then pages:
1. Legal (privacy `279:5924`, terms `281:6773`) — mostly type
2. Contact (`279:6325`)
3. Insights index (`268:4158`/`285:4590`) → Insights show
4. About (`336:5623`)
5. Services (`308:4492`)
6. Work index (`222:1989`/`261:2545`) → Work show (`336:5374`)
7. Home (canonical of the 4) — flagship, last
8. Error 404/405 (`82:2059`, `88:2490`)
