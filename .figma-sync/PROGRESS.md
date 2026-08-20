# Figma Sync — Progress

## Current phase: 2 — Design tokens (token values done; awaiting scope confirmation before Phase 3+)
## Last completed unit: token layer enrichment — added gold/*, warm/* (lowercase black/*), primary-black, space0 (tailwind.config.js), verified against live variables (13:2766/308:4492/336:5623)
## Next unit: AWAIT USER STEER on refined scope (see report). Then: /design-tokens swatch route + font decision (G8), or pivot to About/Work/case-study new-frame builds (G7)
## Stack: Laravel 12.61 + Inertia 2 + Vue 3.5 (TS, strict) + Tailwind 3.4 + Vite 6; Filament 3 admin. Locales: en (LTR), fa (RTL), ar (RTL). Assets → public/. Fonts self-hosted (public/fonts: poppins, vazirmatn, doran, idealist).
## Blockers: scope re-evaluation — existing pages are largely faithful to CURRENT canonical frames; real new work is G7 (About/Work/case-study now designed). Reported to user 2026-08-20.

## Key facts locked
- Home canonical: 268:2962 (dark). Light twin 13:2766 superseded.
- Insights: index 268:4158, show 285:4590. Services 308:4492.
- NEW live frames (were undesigned): about 336:5623, projects 222:1989/261:2545, case study (Work/show) 336:5374.
- Token line-height = 1.5 (measured), NOT raw variable 100%.

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
- [ ] Phase 2 — Design tokens
- [ ] Phase 3 — Assets
- [ ] Phase 4 — Shared layout & components
- [ ] Phase 5 — Pages (one at a time)
