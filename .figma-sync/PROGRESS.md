# Figma Sync — Progress

## Current phase: 8 — Fresh page-by-page pixel-instance re-verification (user-directed 2026-09-02), distinct from phases 1-7's build pass: re-pull each page-instance node fresh (not the old node IDs / "done" statuses in this file, which G53 just showed can go stale), re-screenshot, re-diff. Home re-verified and FROZEN — no further changes unless a shared-component fix demonstrably affects it.
## Last completed unit: shared chrome sweep (header, footer, container gutter) — see GAPS G53. Header desktop: re-pulled `268:3031` fresh, overlay+pixel-diffed against `/fa` — clean, the only diff pixels are hero-background gradient noise behind the translucent header, not the header itself. Header mobile + MobileMenu: functional check only (no Figma mobile frame exists, confirmed via CLAUDE.md) — hamburger, open/close, nav list, CTA all render correctly, no console errors. Container/gutter: `container-sizdah`'s `xl:px-24` (96px) reconfirmed against the frame's 96px gutter, and independently reconfirmed by the footer wash aligning exactly inside it with no extra offset math. Footer: **the file's footer was redesigned since the 2026-08-21 pass** — old instance id `279:6327` (and every docblock/GAPS entry citing it) 404s now; live footer nodes are `688:5xxx`/`688:6xxx` per page, height 501.76 not 431. Missing decorative wash (`Group 26`, 7 brand-yellow blobs at 3% opacity) built and wired in as `resources/images/sizdah/shared/footer-bloom.svg`; bottom-bar Terms/Privacy order was inverted (RTL reading order is Terms-then-Privacy) and is now fixed; `footer.privacy_policy` copy was short-form, now matches the frame's full phrase; stale SAHRA-deviation docblock rationale rewritten (frame is properly Sizdah-branded now). Verified live at 1440 (overlay-diffed against the frame) and 390 (wash correctly hidden, no regression), and re-verified on frozen Home to confirm the shared-component change doesn't break it.
## Next unit: page-by-page loop, manifest order — Work index (`/work`, Figma `222:1989`) is next (manifest's #2, right after Home). Then case study, services, about, insights index/show, contact, legal (privacy/terms), 404. Each gets a fresh section inventory + page-instance re-verification per the loop in the user's brief — do not trust this file's old "done" statuses, G53 just proved a page can drift after being marked done.
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
