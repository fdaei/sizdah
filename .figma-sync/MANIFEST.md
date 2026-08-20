# Figma Sync — Frame Manifest

Source: file `v1l4ANft5Wtb8wPThyP7P9`, page `0:1` "User Interface".
Widths are Figma canvas px. Status: `pending` → `in_progress` → `done`.
Canonical picks among duplicates resolved at Phase 5 (see GAPS G2/G5).

## Pages (1440-wide desktop frames)

| # | Frame name | Node ID | Type | Viewport w×h | Route (intended) | Status |
|---|---|---|---|---|---|---|
| 1 | Home | `13:2766` | page (variant) | 1440×11273 | `/{locale}` | pending |
| 1 | Home | `268:2962` | page (variant) | 1440×9919 | `/{locale}` | pending |
| 1 | Home | `504:7399` | page (variant) | 1440×11273 | `/{locale}` | pending |
| 1 | Home | `450:4908` | page (variant) | 1440×11273 | `/{locale}` | pending |
| 2 | projects (Work index) | `222:1989` | page (variant) | 1440×4656 | `/{locale}/work` | pending |
| 2 | projects (Work index) | `261:2545` | page (variant) | 1440×4656 | `/{locale}/work` | pending |
| 2 | projects (short/state) | `226:3080` | page? state | 1440×1762 | `/{locale}/work` | pending |
| 3 | case study (Work show) | `336:5374` | page | 1440×7403 | `/{locale}/work/{slug}` | pending |
| 4 | services | `308:4492` | page | 1440×5333 | `/{locale}/services` | pending |
| 5 | about | `336:5623` | page | 1440×4021 | `/{locale}/about` | pending |
| 6 | blog list (Insights index) | `268:4158` | page (variant) | 1440×4277 | `/{locale}/insights` | pending |
| 6 | blog list (Insights index) | `285:4590` | page (variant) | 1440×4844 | `/{locale}/insights` | pending |
| 6 | blog list (state) | `416:4959` | page? state | 1440×1024 | `/{locale}/insights` | pending |
| 6 | blog list (state) | `416:5834` | page? state | 1440×1024 | `/{locale}/insights` | pending |
| 7 | contact us | `279:6325` | page | 1440×1755 | `/{locale}/contact` | pending |
| 8 | privacy policy | `279:5924` | page | 1440×2818 | `/{locale}/privacy-policy` | pending |
| 9 | term of use | `281:6773` | page | 1440×2443 | `/{locale}/terms` | pending |
| 10 | 404 | `82:2059` | error state | 1440×1024 | fallback | pending |
| 10 | 404 | `266:2825` | error state | 1440×1024 | fallback | pending |
| 10 | 405 | `88:2490` | error state | 1440×1024 | fallback | pending |

## Component sheets & card frames

| Frame name | Node ID | Type | Size | Notes | Status |
|---|---|---|---|---|---|
| Components | `21:2152` | component sheet | 1583×1094 | primary DS sheet — mine in Phase 4 | pending |
| blog card | `276:5724` | component | 652×1140 | insight card | pending |
| card | `537:5510` | component | 435×570 | generic card | pending |
| Frame 96449 | `537:5561` | component | 509×759 | card variant | pending |
| testimonial | `546:7528` | component | 340×728 | testimonial card | pending |
| member card | `598:5732` | component | 269×701 | team member | pending |
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
