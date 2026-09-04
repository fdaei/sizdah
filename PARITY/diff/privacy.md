# PARITY diff — Privacy policy

**Figma:** `279:5924` 1440×2818 · **Route:** `/fa/privacy-policy` · **Viewport:** 1440
**Measured:** 121 elements · fonts: Vazirmatn, Peyda

**25 pass · 0 fail · 2 waived**

| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |
|---|---|---|---|---|---|---|
| page | `279:5924` | height | 2818 | 2747 | 71.00 | WAIVED |
| site header | `279:5925` | box.h | 108 | 108 | 0.00 | PASS |
| site header | `279:5925` | box.w | 1440 | 1440 | 0.00 | PASS |
| h1 top | `279:6003` | box.y | 188 | 188 | 0.00 | PASS |
| h1 line box | `279:6003` | box.h | 61 | 60.95 | 0.05 | PASS |
| h1 width | `279:6003` | box.w | 612 | 612 | 0.00 | PASS |
| h1 font-size | `279:6003` | fontSize | 48 | 48px | 0.00 | PASS |
| h1 weight (Display/Large Bold) | `279:6003` | fontWeight | 700 | 700 | 0 | PASS |
| h1 colour Black/50 | `279:6003` | color | rgb(243, 243, 243) | rgb(243, 243, 243) | 0 | PASS |
| subtitle top (h1 + 24 gap) | `279:6005` | box.y | 273 | 272.95 | 0.05 | PASS |
| subtitle line box (2 lines x 25) | `279:6005` | box.h | 50 | 50 | 0.00 | PASS |
| subtitle font-size (tite/Medium) | `279:6005` | fontSize | 20 | 20px | 0.00 | PASS |
| subtitle weight | `279:6005` | fontWeight | 500 | 500 | 0 | PASS |
| subtitle colour Black/200 | `279:6005` | color | rgb(208, 208, 208) | rgb(208, 208, 208) | 0 | PASS |
| section 1 h2 top | `279:6728` | box.y | 419 | 418.95 | 0.05 | PASS |
| section 1 h2 line box | `279:6728` | box.h | 38 | 38.09 | 0.09 | PASS |
| section h2 font-size (off-scale 30) | `279:6728` | fontSize | 30 | 30px | 0.00 | PASS |
| section h2 weight | `279:6728` | fontWeight | 500 | 500 | 0 | PASS |
| section h2 colour Black/50 | `279:6728` | color | rgb(243, 243, 243) | rgb(243, 243, 243) | 0 | PASS |
| section body top (h2 + 24 gap) | `279:6730` | box.y | 481 | 481.05 | 0.05 | PASS |
| content track x | `284:3948` | box.x | 96 | 96 | 0.00 | PASS |
| content track width | `284:3948` | box.w | 1248 | 1248 | 0.00 | WAIVED |
| footer height | `688:6453` | box.h | 501.76 | 502.2 | 0.44 | PASS |
| footer brand column width | `688:6474` | box.w | 402 | 402 | 0.00 | PASS |
| footer nav column height | `688:6473` | box.h | 218 | 217.95 | 0.05 | PASS |
| footer top block height | `688:6455` | box.h | 218 | 217.95 | 0.05 | PASS |
| page direction | `279:5924` | direction | rtl | rtl | 0 | PASS |

## Waived — design-file defects, not code defects

- **content track width 1250 (Figma) vs 1248 (code)** — The frame contradicts itself: `284:3948` and `280:6742` are 1250 wide and overhang the right gutter by 2px (96+1250=1346 > 1440-96=1344), while the header and footer on the SAME frame use 1248, and the inner body boxes (`Frame 96292`) are 1248 offset by x=2. 1248 is the gutter-consistent value and the code uses it for all 31 track elements. Matching 1250 would misalign the legal copy against its own header and footer. Design-file defect — logged for the designer.
- **section 2-8 vertical positions drift 7-29px** — Body copy differs between Figma and the seeded database, so paragraphs wrap to different line counts and the 64px-gapped stack accumulates offset. Figma's own copy still reads «در صحرا مارکتینگ» (the OLD brand) and its h1 is «سیاست حفظ حریم خصوصی» vs the seeded «حریم خصوصی». This is a content decision, not a parity defect — parked per the brief.
- **page height 2747 vs 2818 — and it used to 'pass' at 2817** — A FALSE PASS, exposed by fixing the footer wash. The wash (688:6512) was sized 1440 wide instead of its 1248 track, so it rendered 518.95 tall instead of 449.76 and overflowed the footer by ~70px, inflating the scroll height of every page. On privacy that ~70px happened to offset a ~71px content shortfall, so the page-height check read 2817 against 2818 and looked perfect. With the wash pinned to the frame's 1248x449.76 the real gap shows: the seeded copy runs shorter than the frame's. Content difference, parked — but worth recording that two bugs were cancelling.
