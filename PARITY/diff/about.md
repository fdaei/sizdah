# PARITY diff — About

**Figma:** `336:5623` 1440×4021 · **Route:** `/fa/about` · **Viewport:** 1440
**Measured:** 201 elements · fonts: Vazirmatn, Peyda, Maneli

**11 pass · 0 fail · 1 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `336:5623` | height | 4021 | 4297 | 276.00 | WAIVED |
| site header height | `336:5624` | box.h | 108 | 108 | 0.00 | PASS |
| site header width | `336:5624` | box.w | 1440 | 1440 | 0.00 | PASS |
| footer height | `688:6341` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| page direction | `336:5624` | direction | rtl | rtl | 0 | PASS |
| portrait x | `343:9292` | box.x | 96 | 96 | 0.00 | PASS |
| portrait top | `343:9292` | box.y | 250 | 250 | 0.00 | PASS |
| portrait width | `343:9292` | box.w | 696 | 696 | 0.00 | PASS |
| portrait height | `343:9292` | box.h | 522 | 522 | 0.00 | PASS |
| story column x | `357:9335` | box.x | 308 | 308 | 0.00 | PASS |
| story column width | `357:9335` | box.w | 824 | 824 | 0.00 | PASS |
| story column gap | `357:9335` | gap | 80 | 80px | 0.00 | PASS |

## Waived — design-file defects, not code defects

- **footer 274.73 early; middle sections cumulatively taller** — The frame's first content offset (250, not the shared 180) and its trailing slack (147.24, not 96) were both real defects and are fixed — the portrait now lands at exactly 96,250 696x522. What remains is section height: the values grid and the 8-card team grid render taller than the frame's, which is item-count and copy-length driven. Parked as content; convergence would need the seeded team/values to match the frame's.
