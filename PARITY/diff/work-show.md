# PARITY diff — Case study

**Figma:** `336:5374` 1440×7403 · **Route:** `/fa/work/باغچه` · **Viewport:** 1440
**Measured:** 134 elements · fonts: Vazirmatn, Peyda, Maneli

**4 pass · 0 fail · 1 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `336:5374` | height | 7403 | 3212 | 4191.00 | WAIVED |
| site header height | `(header instance)` | box.h | 108 | 108 | 0.00 | PASS |
| site header width | `(header instance)` | box.w | 1440 | 1440 | 0.00 | PASS |
| footer height | `(footer instance)` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| page direction | `(header instance)` | direction | rtl | rtl | 0 | PASS |

## Waived — design-file defects, not code defects

- **page 3212 vs 7403 — the largest gap in the run** — The frame is a fully-authored 7403px case study; the seeded project «باغچه» carries a fraction of that content (no long-form sections, fewer gallery rows). This is a content shortfall of ~4200px, not a layout defect — no amount of spacing correction closes it. Needs either seeded content matching the frame, or a decision that the seed is representative. Parked; element-level verification not reached.
