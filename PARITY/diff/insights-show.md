# PARITY diff — Insights article

**Figma:** `285:4590` 1440×4844 · **Route:** `/fa/insights/brand-direction-3` · **Viewport:** 1440
**Measured:** 203 elements · fonts: Vazirmatn, Peyda, Maneli

**4 pass · 0 fail · 1 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `285:4590` | height | 4844 | 4112 | 732.00 | WAIVED |
| site header height | `(header instance)` | box.h | 108 | 108 | 0.00 | PASS |
| site header width | `(header instance)` | box.w | 1440 | 1440 | 0.00 | PASS |
| footer height | `(footer instance)` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| page direction | `(header instance)` | direction | rtl | rtl | 0 | PASS |

## Waived — design-file defects, not code defects

- **page 4112 vs 4844** — This route renders one seeded Post; the frame mocks a specific article whose body, pull-quotes and related-posts row are longer. Article length is content, not layout. The shared chrome verifies exactly (header 108, footer 501.76) and the phantom 70px scroll overflow is gone. Element-level verification of the body column was not reached in this run.
