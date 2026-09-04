# PARITY — Blockers

Raised under the prompt's STOP CONDITIONS. Each needs a decision before the
dependent work can proceed. Nothing has been guessed or filled in.

---

## B1 — Five Home frames, no canonical marker  · **RESOLVED 2026-09-04**

**Resolution:** user — *"the one next to the rest of the frames; the others
were tests."* Canvas geometry confirms this exactly. Two rows exist:

- **Production row, y ≈ −47:** `772:5193`, **`268:2962`**, `266:2825` (404),
  `222:1989` (projects), `279:6325`, `268:4158`, `285:4590`, `308:4492`,
  `416:4959`/`416:5834`, `336:5374`, `336:5623`, `279:5924`, `281:6773`
- **Test row, y ≈ 8433, negative x:** `450:4908`, `13:2766`, `504:7399` (all
  three 11273-tall Homes), plus `226:3080`, `261:2545`, `82:2059`, `88:2490`

**Canonical Home = `268:2962`** — the Home sitting directly against the run of
pages, and already the one cited by [routes/web.php](routes/web.php).
`772:5193` is its pixel-equivalent twin (RMSE 0.078%) at the row's left edge;
the choice carries no fidelity risk.

**This also resolved three other duplicate questions for free:** `222:1989`
(not `261:2545`) is canonical for Work index, `266:2825` (not `82:2059` /
`88:2490`) for the error page, and `226:3080` is confirmed a test.

<details><summary>original</summary>

## B1 — Five Home frames, no canonical marker  · blocks Phase 3 (Home)

The file carries five top-level frames named "Home":

| Node ID | Size | Notes |
|---|---|---|
| `268:2962` | 1440×9919 | dark; cited by [routes/web.php](routes/web.php) and `.figma-sync` as canonical |
| `772:5193` | 1440×9919 | **visually indistinguishable** from `268:2962` |
| `13:2766` | 1440×11273 | light palette |
| `504:7399` | 1440×11273 | — |
| `450:4908` | 1440×11273 | 88 direct children |

`268:2962` vs `772:5193` measured with ImageMagick at thumbnail scale:
**RMSE 51.1 / 0.078 %**, differences spread thinly over the full height with
no localized structural change — i.e. render noise, not a design delta.

Nothing in the file marks a canonical frame. The 11273-tall trio is a
different, taller composition than the 9919 pair, so this is not a
one-of-five pick — it is two questions.

**Question:** confirm `268:2962` as canonical and treat the other four as
superseded? Or is one of the 11273-tall frames the current design?

</details>

---

## B2 — Phase 5 (RTL & i18n) cannot run as written  · **RESOLVED**

**Resolution:** user chose an **fa-only RTL checklist**. Phase 5 becomes:
logical properties (`ps-`/`pe-`), directional-icon mirroring, logo NOT
flipped, Persian line-height adequacy, and overflow. No en/ar work.

The prompt specifies `en / ar / fa` with full RTL, and asks for each frame
verified and screenshotted in all three.

Neither side of that exists:

- **The Figma file has no `en` or `ar` frame.** Every text node across all 21
  page frames is Persian. Already recorded as `.figma-sync/GAPS.md` G6.
- **The app is Persian-only by an explicit user decision dated 2026-08-21**
  ([config/locales.php](config/locales.php)): only `fa` is under `supported`;
  `en` and `ar` are parked under `inactive`. `/en/*` and `/ar/*` do not
  resolve. [CLAUDE.md](CLAUDE.md) records this as deliberate.

There is no source of truth to verify `en`/`ar` against, so Phase 5 would be
producing invented output — exactly what the Prime Directive forbids.

**Question:** drop Phase 5 to an `fa`-only RTL checklist (logical properties,
directional-icon mirroring, Persian line-height, overflow)? Or is
re-activating `en`/`ar` part of this job — which would be new design work,
not parity work?

---

## B3 — Font stack in the prompt is behind the project  · **RESOLVED**

**Resolution:** Figma wins, as assumed. Confirmed across 16 nodes: every
`FA-Desktop/*` style is **Peyda**, accents are **Maneli**, and the contact page
(`279:6325`) additionally uses **`EN-Desktop/*` in Poppins** — so the existing
`sans`/`arabic`/`display` token trio is already correct. No change needed.

The prompt says: *"فونت LTR: Poppins — فونت RTL: Vazirmatn (تا زمانی که
لایسنس Doran FaNum تهیه شود)."*

The live Figma variables name neither. Every FA text style in `268:2962`
resolves to **`fontFamily: Peyda`** (`FA-Desktop/Body/Large`,
`FA-Desktop/Display/Medium`, `FA-Desktop/Headline/Small`, …), and the
eyebrow/display accent to **`Maneli`**.

The project has already moved past the prompt: Peyda (9 weights) and Maneli
are licensed and self-hosted under `public/fonts/`, wired in
[resources/css/fonts.css](resources/css/fonts.css) and
[tailwind.config.js](tailwind.config.js), with Vazirmatn kept only as a
metric-compatible fallback.

Following the prompt literally would **regress** the app off its licensed
fonts onto a fallback.

**Assumption I will proceed on unless corrected:** Figma wins — Peyda and
Maneli are the type tokens; the Vazirmatn/Doran line in the prompt is stale.

---

## B4 — The icon ban collides with the design file  · **RESOLVED**

**Resolution:** user chose **export everything from Figma**, including the
`lucide-icon` nodes, and drop the npm dependency. Artwork is identical since
the source is the same. Scheduled for Phase 2.

The prompt bans `lucide-vue-next` outright and requires every icon exported
from its Figma node.

Two complications:

1. **`lucide-vue-next` is in use** — 5 files, ~7 glyphs (`X`, `Menu`, `Mail`,
   `MapPin`, `Phone`, `CheckCircle2`, `XCircle`). These are all chrome
   (menu toggle, flash messages, footer contact), not illustration.
2. **The Figma file itself contains a component named `lucide-icon`**
   (`13:224`), i.e. the designer placed Lucide glyphs into the design. For
   those nodes, "export from Figma" and "don't use Lucide" describe the same
   artwork.

The site's real icon vocabulary is the 41 hand-drawn glyphs on the sprite
sheet `149:2691` (`thought-bubble`, `paper-airplane`, `calendar-hand-drawn-tool`,
`quote-hand-drawn-symbol`, …) plus `690:7085` Share icons — none of which
have a `resources/js/icons/` home yet.

**Question:** export the `lucide-icon` nodes as SVG from Figma too (removing
the npm dependency, keeping identical artwork), or keep the dependency for
those specific chrome glyphs and apply the ban only to the hand-drawn set?

---

## B5 — No tablet or mobile frames  · **RESOLVED**

**Resolution:** user put Phase 6 **out of scope**. Existing derived responsive
behaviour below `lg` stays untouched. Phase 4 verifies at 1440px only, and the
final report will state plainly that sub-1440 is unverified.

All 21 page frames are 1440 px. There is no frame at any other width
anywhere in the file. Phase 6 says to implement only breakpoints Figma
defines, and to ask rather than invent — so Phase 6 has **zero** in-scope work
as specified.

The app nonetheless already ships responsive behaviour below `lg`, derived
rather than designed ([tailwind.config.js](tailwind.config.js) comments;
`docs/RESPONSIVE-QA.md`).

**Question:** leave the existing derived responsive behaviour untouched and
out of scope, or audit it against something (and if so, what)?

---

## B6 — `get_metadata` under-reports this file  · methodology, resolved

Not a design blocker; recording it so the method is auditable.

`get_metadata` returns some large frames as childless self-closing elements
(`268:2962`, `772:5193`, `504:7399`). Verified false via `get_screenshot`
(full page renders) and `get_figma_data` (full subtree).

**Resolution, no decision needed:** top-level inventory from `get_metadata`;
all per-frame structure from `get_figma_data` / `get_design_context`. No
frame will be declared empty on `get_metadata` evidence alone.

---

## Not a blocker, but flagged

The prompt describes the stack as **Laravel 11** and the token file as
**`tailwind.config.ts`**. The project is **Laravel 12** with
**`tailwind.config.js`**. I will write tokens to the existing `.js` file
rather than introduce a parallel `.ts` config.


---

## B7 — Two colours exist in the design but have no Figma variable  · **RESOLVED 2026-09-04**

**Resolution:** user chose role-named tokens. Rationale recorded because it
generalises: the "exactly the Figma variable name" rule exists to stop you
*inventing* colours — not to force a colour the designer really used, but never
promoted to a variable, to stay anonymous. Left as raw hex, the next pass
cannot tell a deliberate raw fill from one somebody eyeballed.

Implemented in [tailwind.config.js](tailwind.config.js) as a **separate block**,
deliberately not merged into `brand`/`ink`/`gold`/`warm`, so the boundary
between "Figma variable" and "raw fill" stays visible:

| Token | Value | Raw fill on |
|---|---|---|
| `surface-raised` | `#FDFCFA` | `268:3032`, `553:7779` |
| `brand-pressed`  | `#f3b435` | hover variant `537:5511` |

Consumers wired: [ServiceOrbit.vue:118](resources/js/Components/ServiceOrbit.vue#L118),
[ProcessStepCard.vue:69](resources/js/Components/ProcessStepCard.vue#L69).
Verified both compile: `bg-surface-raised` → `rgb(253 252 250)`,
`hover:bg-brand-pressed:hover` → `rgb(243 180 53)`.

Hardcoded count **19 → 17**. The remaining 17 are classified and justified in
[hardcoded-values.md](PARITY/hardcoded-values.md) and are not to be touched.

The real fix is upstream, in the Figma file — logged in
[REPORT.md](PARITY/REPORT.md) § "برای طراح".

<details><summary>original</summary>

## B7 — Two colours exist in the design but have no Figma variable

Phase 1's exit criterion is *"صفر مقدار هاردکد"*, and the colour rule is
*"با **دقیقاً همان نام متغیر Figma**"*. Two values cannot satisfy both:

| Site | Value | Evidence it is a raw fill |
|---|---|---|
| [ServiceOrbit.vue:118](resources/js/Components/ServiceOrbit.vue#L118) | `#FDFCFA` | appears as a literal fill on `268:3032` and `553:7779`; recurring surface |
| [ProcessStepCard.vue:69](resources/js/Components/ProcessStepCard.vue#L69) | `#f3b435` | hover variant `537:5511`; `get_variable_defs` on `537:5510` returns no matching variable |

Neither is a Figma variable, so **neither has a Figma name to copy**. Naming
them is a design-system decision, not transcription — so I have not invented
names.

**Question:** add role-named tokens (e.g. `surface-raised` for `#FDFCFA`,
`brand-pressed` for `#f3b435`), clearly commented as raw fills rather than
Figma variables? Or leave them as arbitrary values and accept that Phase 1
exits at 19 rather than 0?

Blocking only the "zero hardcoded" claim — Phase 2 can proceed either way.

</details>
