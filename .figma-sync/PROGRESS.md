# Figma Sync — Progress

## Current phase: 7 (new) — Pixel-level re-measurement pass (per-node `get_design_context`, not just `get_metadata`), at the user's explicit request (2026-08-24) to go beyond "is anything missing" into "is every value correct."
## Last completed unit: G38 — `Eyebrow`'s "square" marker doesn't exist in the file; fixed 4 page-header call sites (Services/Insights-index/Contact/Work-index) missing `marker="dot"`, corrected the component's own stale docblock. Also: `ArticleMeta` border colour fix, `TestimonialCard`/`FilterChips`/`ClientLogo`/Legal/404 confirmed clean. Committed as `95ad281`. All work through G38 is committed (`8efde5c`, `8796bce`, `bbb6f4f`, `95ad281`), not yet pushed.
## Next unit: Confirmed clean this pass: Home (hero, KPI, trust proof, process, why-us — bar the G33/G35 fixes), Contact, About, BlogCard, PostMeta, ProjectPostCard (bar G37's shadow). Fixed: Home hero CTA size (G33), dead FinalCtaCard on 2 pages (G34), FaqAccordion + InsightsShowcase colours (G35), ProjectPostCard shadow (G37). Not yet touched at this per-value tier: Home's services/orbit and testimonials (component-level rigor from G25/G32 trusted, not re-verified), Legal x2, Work index (structure)/Work show, Insights index/show (structure done, article prose/meta chips not value-checked), Services (G29's flourish work only), 404, TestimonialCard, ArticleMeta, FilterChips, ClientLogo. This is now a large, real, multi-session-scale effort — every component checked so far has taken several tool calls, and roughly 1 in 3 has had a real, previously-unknown defect.
## Stack: Laravel 12.61 + Inertia 2 + Vue 3.5 (TS, strict) + Tailwind 3.4 + Vite 6; Filament 3 admin. Locales: en (LTR), fa (RTL), ar (RTL). Assets → resources/images (via `~img` alias) + public/fonts. Fonts self-hosted (poppins, vazirmatn, doran, idealist).
## Blockers: none. Figma MCP reconnected 2026-08-23. (`pdo_sqlite` is still absent so the Pest suite cannot run — pre-existing, see CLAUDE.md.)

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
  Sahra card; superseded. (`226:3080` is a short state, not a full page.)
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
