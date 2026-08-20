# Figma Sync — Progress

## Current phase: 2 — Design tokens
## Last completed unit: Phase 1 inventory / MANIFEST (page 0:1)
## Next unit: get_variable_defs on 0:1 + representative frames → Tailwind token layer
## Stack: Laravel 12.61 + Inertia 2 + Vue 3.5 (TS, strict) + Tailwind 3.4 + Vite 6; Filament 3 admin. Locales: en (LTR), fa (RTL), ar (RTL). Assets → public/. Fonts self-hosted in resources/css/fonts.css.
## Blockers: none

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
