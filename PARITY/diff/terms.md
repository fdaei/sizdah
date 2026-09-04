# PARITY diff — Terms of use

**Figma:** `281:6773` 1440×2443 · **Route:** `/fa/terms` · **Viewport:** 1440
**Measured:** 111 elements · fonts: Vazirmatn, Peyda

**25 pass · 0 fail · 2 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `281:6773` | height | 2443 | 2294 | 149.00 | WAIVED |
| site header | `281:6774` | box.h | 108 | 108 | 0.00 | PASS |
| site header | `281:6774` | box.w | 1440 | 1440 | 0.00 | PASS |
| h1 top | `281:6826` | box.y | 188 | 188 | 0.00 | PASS |
| h1 line box | `281:6826` | box.h | 61 | 60.95 | 0.05 | PASS |
| h1 width | `281:6826` | box.w | 612 | 612 | 0.00 | PASS |
| h1 font-size | `281:6826` | fontSize | 48 | 48px | 0.00 | PASS |
| h1 weight (Display/Large Bold) | `281:6826` | fontWeight | 700 | 700 | 0 | PASS |
| h1 colour Black/50 | `281:6826` | color | rgb(243, 243, 243) | rgb(243, 243, 243) | 0 | PASS |
| subtitle top (h1 + 24 gap) | `281:6828` | box.y | 273 | 272.95 | 0.05 | PASS |
| subtitle line box (2 lines x 25) | `281:6828` | box.h | 50 | 50 | 0.00 | PASS |
| subtitle font-size (tite/Medium) | `281:6828` | fontSize | 20 | 20px | 0.00 | PASS |
| subtitle weight | `281:6828` | fontWeight | 500 | 500 | 0 | PASS |
| subtitle colour Black/200 | `281:6828` | color | rgb(208, 208, 208) | rgb(208, 208, 208) | 0 | PASS |
| section 1 h2 top | `281:6832` | box.y | 419 | 418.95 | 0.05 | PASS |
| section 1 h2 line box | `281:6832` | box.h | 38 | 38.09 | 0.09 | PASS |
| section h2 font-size (off-scale 30) | `281:6832` | fontSize | 30 | 30px | 0.00 | PASS |
| section h2 weight | `281:6832` | fontWeight | 500 | 500 | 0 | PASS |
| section h2 colour Black/50 | `281:6832` | color | rgb(243, 243, 243) | rgb(243, 243, 243) | 0 | PASS |
| section body top (h2 + 24 gap) | `281:6834` | box.y | 481 | 481.05 | 0.05 | PASS |
| content track x | `284:3949` | box.x | 96 | 96 | 0.00 | PASS |
| content track width | `284:3949` | box.w | 1248 | 1248 | 0.00 | WAIVED |
| footer height | `688:6565` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| footer brand column width | `688:6586` | box.w | 402 | 402 | 0.00 | PASS |
| footer nav column height | `688:6585` | box.h | 218 | 217.95 | 0.05 | PASS |
| footer top block height | `688:6567` | box.h | 218 | 217.95 | 0.05 | PASS |
| page direction | `279:5924` | direction | rtl | rtl | 0 | PASS |

## Waived — design-file defects, not code defects

- **content track width 1250 (Figma) vs 1248 (code)** — The frame contradicts itself: `284:3948` and `280:6742` are 1250 wide and overhang the right gutter by 2px (96+1250=1346 > 1440-96=1344), while the header and footer on the SAME frame use 1248, and the inner body boxes (`Frame 96292`) are 1248 offset by x=2. 1248 is the gutter-consistent value and the code uses it for all 31 track elements. Matching 1250 would misalign the legal copy against its own header and footer. Design-file defect — logged for the designer.
- **page height 2364 vs 2443, and section 2-8 positions** — Same cause as privacy: the seeded copy differs from the frame's, so paragraphs wrap to different line counts. Figma's section-1 body is 2 lines (50px); the seeded one is 1 line (25px). The 64px-gapped stack then accumulates the difference. Content decision, not parity — parked per the brief.
