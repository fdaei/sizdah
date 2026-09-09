# Figma Sync — Progress

## Current phase: 8 — Fresh page-by-page pixel-instance re-verification (user-directed 2026-09-02), distinct from phases 1-7's build pass: re-pull each page-instance node fresh (not the old node IDs / "done" statuses in this file, which G53 just showed can go stale), re-screenshot, re-diff. Home re-verified and FROZEN — no further changes unless a shared-component fix demonstrably affects it.
## Last completed unit: insights show (`/insights/{slug}`, Figma `285:4590`) — user-directed phase-8 re-pull, out of manifest order, 2026-09-09. The page was laid out on ONE 48px inter-block gap; the frame runs seven different ones (88 title→meta, 64 meta→cover, 113 cover→prose, 72 around the lead magnet, 81 prose→related, 48 related heading→cards, 224 related→CTA, 123 CTA→footer), and its content starts at y=207, not the 180 `section-first` gives every other page. Three real defects behind that: (1) the share rail (`690:7022`) shared a centred flex row with the prose, so the row centred rail+copy as a unit and pushed the 831 column 75px off the centre line the frame draws it on — the rail is now positioned out of flow at the frame's 33px from the track's inline-start edge, gated at 1336px (xl/1280 leaves only a 128px margin for a 152px rail); (2) `LeadMagnetBanner` renders the strip and its modal as TWO roots, so the `mx-auto max-w-*` the page passed it was silently dropped and the card sat flush to the track's inline-start instead of on the prose column — it is centred by a wrapper now; (3) the article body ran the legal-page `.rich-prose` scale (h 30, p 20/500 on 64/24/32) against the frame's h 24 / p 18/400 on 72/40/22 — added `.rich-prose-article` rather than editing the base rule, which Legal (`279:6728`) is correctly tuned to. Also: share circles are Yellow/100 `#fff8eb`, not the Yellow/50 they carried, and "مقالات مرتبط" (`294:7670`) is Peyda SemiBold, not `display-sm`'s Bold. Verified by measuring the live DOM against the frame's node geometry at 1440 (every block within ~2px; residual deltas are seeded-copy line counts, not layout) and at 390/768/1024/1280. Typecheck and lint clean. Note the footer's decorative `w-[1248px]` plate overflows at 1280 — pre-existing, shared by every page, left alone.

## Previously completed unit: case study (`/work/{project}`, Figma `336:5374`) — fresh phase-8 re-pull, see GAPS G55. The page was marked faithful by G19 and was not: Figma reports auto-layout in LTR even for an RTL frame, so `alignItems: flex-end` had been read as CSS `items-end` (which is the LEFT in RTL) and row children had been kept in Figma order. That mirrored the goal/deliverable cards, the meta chip row, the before/after pair and the next-project block. Also rebuilt: the strategy block is a three-column quadrant whose first column IS the heading (spanning both rows) with 3px brand-200 rules between the two card columns, not a 2x2 grid under a heading; deliverables are 3-across at r24 against goals' 4-across at r16; banner and before/after plates got their exact ratios (1248x624 and 612x306), radii and casts; glow angles corrected to -44deg from 0%. Two schema gaps closed rather than skipped: the five result-tile glyphs now ride `section_items.icon` (exported to `work/result-*.svg`), and `project_images.tag` backs the showcase FilterChips row (430:5201), which stays hidden until an image is tagged. Verified 1440/1280/1024/834/768/390/360; typecheck, lint and build clean.
## Next unit: continue the page-by-page loop in manifest order — Work index (`/work`, Figma `222:1989`) is still outstanding, then services, about, insights index, contact, legal, 404 (insights SHOW is done, see above). Carry G55's RTL rule into every one of them: Figma `flex-end` maps to CSS-logical `start`, and a row's Figma child order is the REVERSE of the DOM order you want. Do not trust this file's old "done" statuses — G53 and now G55 have each proved a page can be marked faithful and not be.
## Stack: Laravel 12.61 + Inertia 2 + Vue 3.5 (TS, strict) + Tailwind 3.4 + Vite 6; Filament 3 admin. Locales: fa (RTL) only — en/ar sit under `locales.inactive` (G15); the machinery stays. Assets → resources/images (via `~img` alias) + public/fonts. Fonts self-hosted (poppins, vazirmatn, doran, idealist).
## Blockers: none for design work. Environment: `pdo_sqlite` is not built into this PHP 8.5 install, so `php artisan test` cannot run (pre-existing, see CLAUDE.md); WebKit system libraries are missing, so the `mobile-390` Playwright project cannot launch. Both need root.

## Context (user, 2026-08-21)
This repo was started from an earlier, similar project ("Sahra") because the
**backends matched**; the frontend is what must now be rebuilt from Figma. That
explains the Sahra residue throughout: the footer component in every frame, the
`docs/` audit's dead node IDs, and the seeded copy. Treat the Laravel/data layer
as sound and the Vue layer as the deliverable.

## Key facts locked
- The file is **Persian-only** — no en/ar frame exists anywhere (G6, corrected).
  fa is source copy; en/ar are derived and need native review (G10).
- The file has **three pages**, not one (G9). Build only from `0:1`.
- Home canonical: **`268:2962`** — confirmed by the user 2026-08-21 with a dev-mode
  link to that node. 13:2766 is the light twin; 450:4908 and 504:7399 are dark
  variants differing in the "Lost Level" section ground and the process-card
  treatment. All three are superseded.
- Build order: the MANIFEST "simplest → most complex" list, user-confirmed —
  Legal → Contact → Insights → [About done] → Services → Work + case study →
  Home → 404/405.
- Work index canonical: **`222:1989`** — dark, SIZDAH logo, ends on the cream
  CTA card. `261:2545` is the light twin and still ends on the old English
  Sahra card; superseded. (`226:3080` is a stray exploration duplicate — an
  old-template Contact draft, unrelated to Work despite its name — not a
  page needing implementation; see GAPS G52.)
- The cream CTA is **the same card on two pages** — 577:10889 (about) and
  577:9134 (projects) are duplicated frames, not instances, but their five text
  runs, illustration and geometry are byte-identical. Extracted to
  `Components/StartTogetherCard.vue` so they cannot drift. The dark
  `FinalCtaCard` (294:7672) stays for services and the article page.
- Site is **fa-only** (G15): content Persian, i18n plumbing retained.
- Type scale runs on **1.25/1.27**, not 1.5 (G14) — this was a foundational
  error and it changed every page.
- Insights: index 268:4158, show 285:4590. Services 308:4492. About 336:5623.
- NEW live frames (were undesigned): about 336:5623 **[built]**, projects
  222:1989/261:2545, case study 336:5374.
- Token line-height = 1.5 for the body scale (measured); page and section
  headlines are 1.27 (`hero-line`, `hero-accent`, `section-line`).

## Done this run
- Phase 2 tokens: `section-line`, the `display` font family, and the whole type
  scale re-measured off the frames (G14 — the big one).
- Phase 3 assets: 8 About files + 4 woff2 display faces (see ASSETS.md).
- Phase 5: About rebuilt end to end and reseeded from the frame's text runs;
  Legal corrected; Contact / Insights index+show / Services structurally
  verified against their frames (no layout delta — they were already built
  against the current node IDs).
- `Components/StartTogetherCard.vue` extracted from About for reuse on Work.
- fa-only applied per G15.
- Fixes found on the way: G11 (display fonts never wired), G12 (final-CTA
  services line never rendered), G13 (stale git-root warning in CLAUDE.md),
  G14 (type scale on the wrong leading).

## Remaining
**No page frame is outstanding.** The three "remaining" frames below had in fact
already been rebuilt — this ledger's own note was the stale part, not the code.
A 2026-08-21 diff pass against the live file confirmed each against its frame
and found four real defects, all now fixed (GAPS G20-G22):

1. Work index `222:1989` — built and faithful. Was missing the 1200x1200
   hairline mesh `266:2771`; header column widened to the frame's 612-in-670.
2. Case study `336:5374` — built and faithful. Was missing mesh `511:9519`.
3. Home `268:2962` — built and faithful; its twelve-section order matches.
   Mesh `268:2966` was drawn square (884) instead of 884x778. The lead-magnet
   copy now follows frame `391:4795` verbatim (G16 closed by user decision).
4. Error page — already built against `266:2825`. The frame named "405" turned
   out to be the Persian 404's light twin, so there was never a 405 to build
   (G20). Restored the frame's hard line break in the body copy.

Site-wide: the dark-frame corner wash (`.page-wash`) was missing from every
page and is now on `AppLayout` (G21).

---

## Run mode
Full from-scratch rebuild (user-selected 2026-08-20). Frontend deliverables
(tokens, assets, components, Vue pages) are regenerated from the live Figma file
unit-by-unit, overwriting existing files. Laravel backend / data layer is NOT
rewritten unless a regenerated page forces a controller↔TypeScript contract
change (which is flagged in GAPS.md).

## Restore point
Git tag `pre-figma-rebuild-2026-08-20` at HEAD `8079e60`. Revert any file with
`git checkout pre-figma-rebuild-2026-08-20 -- <path>`. Repo root is $HOME —
scope every git command to project paths.

## Figma source
- File: `v1l4ANft5Wtb8wPThyP7P9` (SizdahMarketing)
- Single page: `0:1` "User Interface" (the template's stated root; also the only page)
- Auth: Nasim Daei (starter tier — mind rate limits; batch reads, low-res thumbnails)

## Phase status
- [x] Phase 0 — Setup & stack detection
- [x] Phase 1 — Inventory / MANIFEST
- [x] Phase 2 — Design tokens (About's needs; re-read `1:2` before further token work)
- [x] Phase 3 — Assets (About + display fonts; per-page from here)
- [~] Phase 4 — Shared layout & components — sheet `21:2152` mined 2026-08-21;
      header, footer, CTA, header item, KPIs, blog card, process card and
      testimonial all corrected (G25). Member card + project cards outstanding.
- [x] Phase 5 — Pages (one at a time) — every page frame done/verified/superseded
