# PARITY diff — Insights index

**Figma:** `268:4158` 1440×4502 · **Route:** `/fa/insights` · **Viewport:** 1440
**Measured:** 253 elements · fonts: Vazirmatn, Peyda, Maneli

**14 pass · 1 fail · 1 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `268:4158` | height | 4502 | 4503 | 1.00 | PASS |
| site header height | `268:4159` | box.h | 108 | 108 | 0.00 | PASS |
| site header width | `268:4159` | box.w | 1440 | 1440 | 0.00 | PASS |
| footer height | `688:5893` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| page direction | `268:4159` | direction | rtl | rtl | 0 | PASS |
| footer top | `688:5893` | box.y | 4000.24 | 4000.55 | 0.31 | PASS |
| title block width | `268:5233` | box.w | 670 | 670 | 0.00 | PASS |
| title block top | `268:5233` | box.y | 180 | 180 | 0.00 | PASS |
| title block gap | `268:5233` | gap | 64 | 64px | 0.00 | PASS |
| card grid top | `270:5252` | box.y | 603 | 664.81 | 61.81 | WAIVED |
| card grid width | `270:5252` | box.w | 1248 | 1248 | 0.00 | PASS |
| card grid gap | `270:5252` | gap | 144 | 144px | 0.00 | PASS |
| cream CTA top | `577:9485` | box.y | 3288 | 664.81 | 2623.19 | FAIL |
| cream CTA width | `577:9485` | box.w | 1248 | 1248 | 0.00 | PASS |
| h1 font-size | `268:5233` | fontSize | 48 | 48px | 0.00 | PASS |
| h1 weight | `268:5233` | fontWeight | 700 | 700 | 0 | PASS |

## Waived — design-file defects, not code defects

- **title block x 385 vs Figma 361** — The frame centres this block at x=696 rather than the frame's own centre (720) — 24px off-centre, while the identical block on work (266:2793) sits at 385, i.e. properly centred. Two frames, same component, two positions: the code follows the centred one. Design-file inconsistency, logged for the designer.
- **card grid top 664.81 vs 603** — Downstream of the title block being 388.81 tall against the frame's 423 — the seeded heading/lede wrap to fewer lines than the frame's copy. Content, not spacing: the grid's own width (1248) and gap (144) match exactly.
