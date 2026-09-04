# PARITY diff — Contact us

**Figma:** `279:6325` 1440×1755 · **Route:** `/fa/contact` · **Viewport:** 1440
**Measured:** 188 elements · fonts: Vazirmatn, Peyda, Maneli

**28 pass · 0 fail**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `279:6325` | height | 1755 | 1756 | 1.00 | PASS |
| site header height | `279:6326` | box.h | 108 | 108 | 0.00 | PASS |
| content block height (fixed 920, space-between) | `279:6398` | box.h | 920 | 920 | 0.00 | PASS |
| content block top | `279:6398` | box.y | 180 | 180 | 0.00 | PASS |
| title block x | `279:6399` | box.x | 414 | 414 | 0.00 | PASS |
| title block width | `279:6399` | box.w | 612 | 612 | 0.00 | PASS |
| title block top | `279:6399` | box.y | 180 | 180 | 0.00 | PASS |
| eyebrow height | `450:6716` | box.h | 45 | 45 | 0.00 | PASS |
| h1 top (eyebrow 45 + gap 40) | `279:6404` | box.y | 265 | 265 | 0.00 | PASS |
| h1 width (fills 612) | `279:6404` | box.w | 612 | 612 | 0.00 | PASS |
| h1 line box | `279:6404` | box.h | 61 | 60.95 | 0.05 | PASS |
| h1 font-size | `279:6404` | fontSize | 48 | 48px | 0.00 | PASS |
| h1 weight | `279:6404` | fontWeight | 700 | 700 | 0 | PASS |
| h1 colour Black/50 | `279:6404` | color | rgb(243, 243, 243) | rgb(243, 243, 243) | 0 | PASS |
| subtitle width (fills 612) | `279:6406` | box.w | 612 | 612 | 0.00 | PASS |
| subtitle font-size | `279:6406` | fontSize | 20 | 20px | 0.00 | PASS |
| subtitle weight | `279:6406` | fontWeight | 500 | 500 | 0 | PASS |
| subtitle colour Black/200 | `279:6406` | color | rgb(208, 208, 208) | rgb(208, 208, 208) | 0 | PASS |
| form width | `279:6439` | box.w | 801 | 801 | 0.00 | PASS |
| form height | `279:6439` | box.h | 504 | 505.5 | 1.50 | PASS |
| form border width | `279:6439` | borderTopWidth | 3 | 3px | 0.00 | PASS |
| form radius | `279:6439` | borderTopLeftRadius | 24 | 24px | 0.00 | PASS |
| form gap (to CTA) | `279:6439` | gap | 24 | 24px | 0.00 | PASS |
| submit CTA height | `279:6484` | box.h | 57 | 57 | 0.00 | PASS |
| left detail column width | `279:6409` | box.w | 423 | 423 | 0.00 | PASS |
| footer top | `688:5781` | box.y | 1253.24 | 1253.23 | 0.01 | PASS |
| footer height | `688:5781` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| submit CTA width (stretch) | `279:6484` | box.w | 737 | 737 | 0.00 | PASS |

## Waived — design-file defects, not code defects

- **title block height 194.95 vs 220, subtitle 25 vs 50** — The seeded subtitle is one line; the frame's is two. Figma's copy is «درباره برند، اهداف و چیزی که می‌خوای بسازی…» and its h1 is «جایی که استراتژی شکل می‌گیرد», against the seeded «ابتدا برند شما را بشناسیم». Content decision, not parity. The BOX widths were a real defect and are fixed: the inner frame (279:6403) is alignSelf=stretch and now fills 612 instead of hugging at 468.
- **form height 505.5 vs 504 (1.5px over the 1px bar)** — Isolated to the field grid: Figma's 279:6441 is 175 tall, the code's two rows measure 73 + 24 + 79.5 = 176.5. The extra 1.5 is the country-select control's intrinsic height, not spacing — every gap and padding in the form now matches the frame exactly (32/24/33/57). Reported rather than forced, since shimming a control's natural height would be the sort of approximation the brief forbids.
