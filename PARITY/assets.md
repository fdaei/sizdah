# PARITY — Phase 2: Asset inventory

**Status: Phase 2 executed under Option A (approved 2026-09-04). Gate green:
`node scripts/parity-assets.mjs` → PASS.**

Path convention taken from the project, not the prompt: assets live in
`resources/images/sizdah/{page}/` and are imported through the `~img` alias
(`vite.config.ts:31` → `resources/images`). **`resources/js/icons/` is not
created** — it would be a parallel convention.

---

## 1 · Existing base — measured

| Check | Result |
|---|---|
| Assets on disk | 113 |
| Distinct `~img` references in code | 105 |
| Referenced but **missing** on disk | **0** |
| Zero-byte files | **0** |
| On disk but **no importer** (orphans) | **7** |

The asset layer is in far better shape than Phase 0's "`resources/js/icons/`
absent" line suggested. Previous passes already exported most of the file.
The genuinely outstanding work is the lucide removal plus the orphans.

## 2 · Orphans — kept, tracked, not deleted

| File | Likely origin / why it may still be wanted |
|---|---|
| `sizdah/home/final-cta-art.svg` | delete, or wire to the `553:7779` cream CTA card |
| `sizdah/home/note-arrow.png` | the only `.png` in the tree — all else is SVG; suspect a superseded export |
| `sizdah/home/process-card.svg` | likely superseded by the `523:5798` bordered grid |
| `sizdah/home/testimonial-rule.svg` | delete, or wire to `546:7528` |
| `sizdah/insights/book-arrow.svg` | pairs with `book.svg`; likely the lead-magnet block |
| `sizdah/insights/book.svg` | ditto |
| `sizdah/shared/nav-squiggle.svg` | `nav-underline.svg` is used; this looks like its predecessor |

**Decision (delegated): keep all seven.** No rendering is broken, and several
map to real Figma nodes whose consumer was likely lost in a rewrite — Phase 3
rebuilds exactly those components, which is when a genuinely needed file would
surface. Deleting now is destructive with no upside: the files cost nothing,
while Figma export URLs expire (~7 days) and re-exporting is real work.

The gate holds the line at **7**: `scripts/parity-assets.mjs` fails if the
orphan count grows, so this is a tracked budget rather than an ignored mess.
Re-evaluate at Phase 3 completion, when every consumer is known.

## 3 · lucide removal — the actual Phase 2 work

Seven glyphs, five files. I traced every one to the Figma file:

### 3a · Have a Figma node — **EXPORTED**

The footer "Info" column (`437:5923`) carries all three as 16×16 instances:

| Glyph | Code site | Figma node | Component | Target file |
|---|---|---|---|---|
| `MapPin` | [AppFooter.vue:105](resources/js/Layouts/AppFooter.vue#L105) | `437:5981` | `13:903` | `sizdah/shared/footer-location.svg` |
| `Phone` | [AppFooter.vue:109](resources/js/Layouts/AppFooter.vue#L109) | `437:5986` | `13:880` | `sizdah/shared/footer-phone.svg` |
| `Mail` | [AppFooter.vue:119](resources/js/Layouts/AppFooter.vue#L119) | `437:5990` | `13:900` | `sizdah/shared/footer-email.svg` |

**Dedup check — done, resolved as "not duplicates".** The existing
`sizdah/contact/contact-{phone,location,email}.svg` are the *same glyph family*
but a different export:

| | footer | contact page |
|---|---|---|
| viewBox | `0 0 16 16` | `0 0 24 24` |
| stroke-width | 1 | 1.5 |
| relative weight | 6.25% | **6.25%** — identical |
| stroke colour | `#E8E8E8` Black/100 | `#F8B937` Yellow/1000 |
| path count | 2 / 1 / 2 | 2 / 1 / 2 — identical |

Raster RMSE was ~8.6%, but an alpha-only (colour-blind) diff gave the same
~8.4% — so the difference is not colour, it is antialiasing of different
absolute stroke widths upscaled to a common size. Same drawing, two sizes.
Both sets are kept; collapsing them would have changed the footer's icon
weight. Rendered check: pin / phone / envelope, correct glyphs, correct colour.

Colour: the instances carry `fills=[]` and inherit; the surrounding text is
`Black/100`. The footer rows have **no colour variant in Figma** — the code's
`hover:text-brand` applies to the *link text*, not the icon. So these are
fixed-colour → `<img>` via `~img` is safe.

### 3b · No Figma node — STOP CONDITION

| Glyph | Code site | Why there is no node |
|---|---|---|
| `Menu` | [AppHeader.vue:134](resources/js/Layouts/AppHeader.vue#L134) | Rendered `lg:hidden`. The header component `21:2159` has no hamburger, because **the file has no frame below 1440px at all**. The glyph lives entirely in the un-designed range (B5). |
| `X` | [MobileMenu.vue:68](resources/js/Layouts/MobileMenu.vue#L68) | Same — no mobile menu exists in Figma. |
| `X` | [LeadMagnetModal.vue:154](resources/js/Components/LeadMagnetModal.vue#L154) | Checked `416:5399` directly: the dialog is title + subtitle + fields + CTA + footnote. **No close affordance is drawn.** |
| `CheckCircle2`, `XCircle` | [FlashMessages.vue:48](resources/js/Components/FlashMessages.vue#L48) | Runtime toast UI. Checked the lead-magnet success state `416:6003` — it uses a 96×96 hand-drawn illustration (`609:5935`), not a status glyph. No toast is designed anywhere. |

Per the Prime Directive I have **not** drawn substitutes and have **not**
pulled from another icon set.

## 4 · The conflict — RESOLVED as Option A

Phase 2's exit gate requires `lucide-vue-next` gone from `package.json`. Four
of the seven glyphs have no Figma source, and three of those four are load-bearing:

- removing the two `X` glyphs leaves **no way to close** the mobile menu or the
  lead-magnet modal;
- removing `Menu` leaves **no way to open** navigation below `lg`;
- removing the flash icons degrades form feedback.

All four sit in exactly the two areas the design file does not cover: **the
sub-1440 range** (out of scope per B5) and **runtime feedback UI** (never
designed). So this is not sloppiness in the existing code — it is the design
file's boundary.

Three ways forward:

| Option | Effect |
|---|---|
| **A** — export 3a, keep lucide for the other four | Honest; ban applied wherever Figma has a source. `lucide-vue-next` stays, exit gate relaxed to "zero lucide where a Figma node exists". |
| **B** — export 3a, replace the other four with hand-authored SVGs | Removes the dependency, but means **drawing four glyphs that do not exist in the design** — a direct Prime Directive violation. |
| **C** — export 3a, ask the designer to add the four | Correct long-term fix; blocks Phase 2 completion until the file is updated. Logged in REPORT.md § "برای طراح" either way. |

**Decision: A.** The ban is enforced wherever the Figma file has a node; the
four glyphs with no source keep `lucide-vue-next`. Nothing was drawn by hand.

`scripts/parity-assets.mjs` encodes this so it cannot silently erode: `Mail`,
`MapPin` and `Phone` are now **banned** from lucide imports (re-importing one
fails the gate), and only the four documented no-source glyphs are allowed.
Adding a fifth fails too.

The four are logged for the designer in [REPORT.md](PARITY/REPORT.md) §
"برای طراح" — if they are ever drawn, the dependency goes.

## 5 · Not exporting

- Test-row frames: `450:4908`, `13:2766`, `504:7399`, `226:3080`, `261:2545`,
  `82:2059`, `88:2490`
- The 36 hidden off-canvas raster plates (`ChatGPT Image …`, `image 1NN`)
- Off-canvas reference sheets already marked superseded in
  [manifest.md](PARITY/manifest.md) §3 (`411:7564`, `537:5561`, `598:5751`)
