# Figma Sync — Gaps & Decisions Log

Every inferred, ambiguous, or missing piece a human should review. Node IDs use
`:` form. Add entries; never silently invent a design.

## G1 — `docs/` audit is STALE vs. current Figma file  (severity: high)
`docs/FIGMA-AUDIT.md` and `docs/TRACEABILITY.md` describe a two-page file
(`1:2` "User interface" = canonical, `0:1` "Wireframing" = archived) and cite
canonical node IDs `1419:9192` (Home), `541:1558` (Projects), `908:1576`
(About), `447:790` (Contact), etc. The **live file has one page — `0:1`
"User Interface" — and none of those node IDs are top-level frames there.** The
current frames are a different, lower-ID generation (Home = `13:2766` /
`268:2962` / `504:7399` / `450:4908`, etc.). The existing code was built against
the *old* structure. This rebuild targets the *current* frames only; the old
audit's node IDs are not reused. `docs/` should be regenerated or archived at
end of run.

## G2 — Duplicate page frames; canonical not yet resolved  (severity: med)
The single page carries multiple same-named page frames. Canonical pick is
deferred to Phase 5 (resolved by screenshot at build time, cheapest point):
- Home ×4: `13:2766` (11273h), `268:2962` (9919h), `504:7399` (11273h), `450:4908` (11273h)
- projects (Work index) ×2 full: `222:1989`, `261:2545` (both 4656h) + `226:3080` (1762h — likely a section/empty-state, not full page)
- blog list ×2 full: `268:4158` (4277h), `285:4590` (4844h) + `416:4959`, `416:5834` (both 1024h — likely list states, single-blog, or 404-ish)
Selection heuristic (from stale audit, still reasonable): prefer the LTR/English
frame with the most complete section set and bound variables. Verify per page.

## G3 — No mobile page frames in the file  (severity: med)
Every real page frame is 1440-wide (desktop). There are no 402/390-wide mobile
page frames (the stale audit listed a mobile set — those nodes are gone).
Component frames exist at natural sizes but no responsive page frames.
Consequence: mobile/tablet layouts are **inferred mobile-first**, not designed.
TARGET_BREAKPOINTS (left blank in the brief) will follow the existing Tailwind
config once tokens are read; breakpoints used will be logged here per page.

## G4 — Brief placeholders were blank  (severity: low)
PROJECT_ROOT / STACK / LOCALES / TARGET_BREAKPOINTS were empty in the template.
Filled from detection: root `/home/fdaei/project/my/sizdah`; stack per
PROGRESS.md; locales en(LTR)/fa(RTL)/ar(RTL); breakpoints per G3.

## G5 — Single-blog (Insights/Show) & single-project source frames unconfirmed  (severity: med)
"case study" `336:5374` is the likely Single-project (Work/Show) source.
Single-blog (Insights/Show) has no obviously-named frame — candidates are the
tall blog frames or one of the 1024h `blog list` frames. Confirm at Phase 5.

## G6 — i18n: file is English-only  (severity: low)
Frames render English copy. fa/ar strings are NOT in the design; they come from
`lang/{fa,ar}/*.php` (existing) or must be authored. Rebuild extracts EN strings
to locale keys; fa/ar translation is out of scope of a pixel rebuild and flagged
where a page needs it.
