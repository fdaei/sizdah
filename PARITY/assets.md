# PARITY — Phase 2: Asset inventory

**Status: inventory only. Nothing exported yet — awaiting approval.**

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

## 2 · Orphans — on disk, imported by nothing

| File | Decision needed |
|---|---|
| `sizdah/home/final-cta-art.svg` | delete, or wire to the `553:7779` cream CTA card |
| `sizdah/home/note-arrow.png` | delete? (only `.png` in the tree — all else is SVG) |
| `sizdah/home/process-card.svg` | likely superseded by the `523:5798` bordered grid |
| `sizdah/home/testimonial-rule.svg` | delete, or wire to `546:7528` |
| `sizdah/insights/book-arrow.svg` | — |
| `sizdah/insights/book.svg` | — |
| `sizdah/shared/nav-squiggle.svg` | `nav-underline.svg` is used; this looks like its predecessor |

These are *dead files*, not missing ones — no rendering is broken. I have not
deleted any: several correspond to real Figma nodes and may simply have lost
their consumer in a rewrite. **Ask before I delete or re-wire.**

## 3 · lucide removal — the actual Phase 2 work

Seven glyphs, five files. I traced every one to the Figma file:

### 3a · Have a Figma node — exportable

The footer "Info" column (`437:5923`) carries all three as 16×16 instances:

| Glyph | Code site | Figma node | Component | Target file |
|---|---|---|---|---|
| `MapPin` | [AppFooter.vue:105](resources/js/Layouts/AppFooter.vue#L105) | `437:5981` | `13:903` | `sizdah/shared/footer-location.svg` |
| `Phone` | [AppFooter.vue:109](resources/js/Layouts/AppFooter.vue#L109) | `437:5986` | `13:880` | `sizdah/shared/footer-phone.svg` |
| `Mail` | [AppFooter.vue:119](resources/js/Layouts/AppFooter.vue#L119) | `437:5990` | `13:900` | `sizdah/shared/footer-email.svg` |

**Dedup check owed at export:** `sizdah/contact/contact-{phone,location,email}.svg`
already exist, exported from the Contact *page* frame. If the footer components
`13:880/900/903` are the same artwork, these must reuse the existing files
rather than adding three near-duplicates. I will raster-diff before writing
anything, and only create new files if the artwork genuinely differs.

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

## 4 · The conflict this creates — needs your decision

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

**My recommendation: A now, C in parallel.** It applies the ban exactly as far
as the source of truth reaches, keeps the site functional, and puts the real
fix upstream where it belongs. B is the only option that requires inventing
artwork, which is the one thing the brief forbids.

## 5 · Not exporting

- Test-row frames: `450:4908`, `13:2766`, `504:7399`, `226:3080`, `261:2545`,
  `82:2059`, `88:2490`
- The 36 hidden off-canvas raster plates (`ChatGPT Image …`, `image 1NN`)
- Off-canvas reference sheets already marked superseded in
  [manifest.md](PARITY/manifest.md) §3 (`411:7564`, `537:5561`, `598:5751`)
