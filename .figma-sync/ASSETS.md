# Figma Sync — Asset Map

Populated in Phase 3. One row per exported asset.
Rules: icons/logos → optimized SVG (currentColor where token-colored);
photos → WebP @1x/2x with original fallback; kebab-case semantic names;
placed under `public/`. Flag any file >300 KB after optimization in GAPS.md.

| Figma node | Local path | Used in | Dimensions | File size | Format |
|---|---|---|---|---|---|
| `343:9292` | `resources/images/sizdah/about/hero-team.webp` | About hero | 1392x1044 (@2x of 696x522) | 155 kB | WebP q88 |
| `354:9302` | `resources/images/sizdah/about/hero-script.svg` | About hero headline | 128x46 | 4.1 kB | SVG |
| `583:5905` | `resources/images/sizdah/about/story-mark.svg` | About story | 144x144 | 7.0 kB | SVG |
| `591:5940` | `resources/images/sizdah/about/principle-discovery.svg` | About principle card 1 | 40x40 | 2.4 kB | SVG |
| `593:5966` | `resources/images/sizdah/about/principle-identity.svg` | About principle card 2 | 40x40 | 2.0 kB | SVG |
| `598:5718` | `resources/images/sizdah/about/principle-impact.svg` | About principle card 3 | 40x40 | 1.9 kB | SVG |
| `598:5713` | `resources/images/sizdah/about/principle-path.svg` | About principle card 4 | 40x40 | 2.2 kB | SVG |
| `577:10891` | `resources/images/sizdah/about/cta-journey.svg` | About final CTA | 592x417 | 84 kB | SVG |
| `511:9188` (+3 instances) | `resources/images/sizdah/services/ring.svg` | Services numeral flourish (all 4 blocks) | 163x109 | 62 kB | SVG |
| `511:9195` (+2 instances) | `resources/images/sizdah/services/spark.svg` | Services between-block divider (all 3 gaps) | 20x25 | 1.5 kB | SVG |
| `298:7715` | `resources/images/sizdah/insights/book.svg` | Insights index header accent | 48x38.5 | 2.6 kB | SVG |
| `299:7724` | `resources/images/sizdah/insights/book-arrow.svg` | Insights index header accent (paired w/ book) | 16x20 | 1.5 kB | SVG |
| `456:6721` | `resources/images/sizdah/insights/paperclip.svg` | Insights index, header/grid gap | 64x64 | 1.6 kB | SVG |
| `268:3728` | `resources/images/sizdah/home/testimonial-rule.svg` | Home testimonials heading | 81x4 | 1.5 kB | SVG |
| `294:7700` | `resources/images/sizdah/insights/related-rule.svg` | Insights show, "related articles" heading | 188x5 | 1.6 kB | SVG |
| — | `public/fonts/idealist/Idealist-Models.woff2` | `font-display` token | — | 36 kB | woff2 |
| — | `public/fonts/doran/DoranFaNum-{Regular,Medium,Bold}.woff2` | `font-display` token | — | 49-50 kB ea. | woff2 |

Notes:
- All SVGs run through `svgo --multipass`. The four principle glyphs and the
  hero script are two-tone Streamline Freehand / hand-drawn art with baked
  brand gold, not monochrome icons, so they do **not** take `currentColor`.
- `cta-journey.svg` is the largest at 84 kB — under the 300 kB flag threshold.
- The CTA card background (imageRef `d7b452aea69f…`, 1200x884) is a plain
  hairline grid and is **not** shipped: it is drawn by `.grid-mesh` with
  `--mesh-color` / `--mesh-cell-x` / `--mesh-cell-y` set at the call site.
- `hero-team.webp` is only a fallback — `about_hero.image` from the CMS wins
  when an editor sets one.
