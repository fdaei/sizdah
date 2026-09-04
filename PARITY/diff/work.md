# PARITY diff — Work index

**Figma:** `222:1989` 1440×4656 · **Route:** `/fa/work` · **Viewport:** 1440
**Measured:** 246 elements · fonts: Vazirmatn, Peyda, Maneli

**10 pass · 1 fail · 1 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `222:1989` | height | 4656 | 4448 | 208.00 | WAIVED |
| site header height | `222:2382` | box.h | 108 | 108 | 0.00 | PASS |
| site header width | `222:2382` | box.w | 1440 | 1440 | 0.00 | PASS |
| footer height | `688:5669` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| page direction | `222:2382` | direction | rtl | rtl | 0 | PASS |
| title block x | `266:2793` | box.x | 385 | 385 | 0.00 | PASS |
| title block width | `266:2793` | box.w | 670 | 670 | 0.00 | PASS |
| title block top | `266:2793` | box.y | 180 | 180 | 0.00 | PASS |
| title block gap | `266:2793` | gap | 64 | 64px | 0.00 | PASS |
| project grid width | `226:3018` | box.w | 1248 | ELEMENT NOT FOUND | — | FAIL |
| cream CTA width | `577:9134` | box.w | 1248 | 1248 | 0.00 | PASS |
| h1 font-size | `266:2793` | fontSize | 48 | 48px | 0.00 | PASS |

## Waived — design-file defects, not code defects

- **cream CTA at 3255 vs 3464, footer 208.77 early** — The seeded database holds 6 projects; the frame's grid runs longer. The grid region measures 2637 in code against the frame's 2851 — a 214px content shortfall that carries straight through to the CTA and footer. The frame's trailing slack below the CTA (177.24, vs the shared pb-24=96) WAS a real defect and is fixed. Content decision, parked.
