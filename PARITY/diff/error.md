# PARITY diff — 404

**Figma:** `266:2825` 1440×1024 · **Route:** `/fa/no-such-page-xyz` · **Viewport:** 1440
**Measured:** 94 elements · fonts: Vazirmatn, Peyda

**4 pass · 0 fail · 2 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `266:2825` | height | 1024 | 1471 | 447.00 | WAIVED |
| site header height | `266:2895` | box.h | 108 | 108 | 0.00 | PASS |
| site header width | `266:2895` | box.w | 1440 | 1440 | 0.00 | PASS |
| footer height | `(none in frame)` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| page direction | `266:2895` | direction | rtl | rtl | 0 | PASS |
| h1 font-size | `266:2831` | fontSize | 32 | 32px | 0.00 | WAIVED |

## Waived — design-file defects, not code defects

- **the frame draws NO footer; the code renders one (502px)** — 266:2825 has exactly two children — the Header and a 725x693 content block at 383,124 — and 207px of unexplained slack below. Every other page frame carries an explicit Footer instance, so its absence here reads as an incomplete mock rather than a decision. Removing the site footer from the error page is a UX change with real consequences, so it is NOT being made on the strength of one under-built frame. STOP-condition park: needs a designer answer.
- **page 1471 vs 1024** — Almost entirely the footer above (502) plus the content block stretching under `min-h-screen-safe` where the frame fixes it at 693. Both follow from the same unresolved question.
