# PARITY — Phase 1: hardcoded values

Regenerate with `node scripts/parity-hardcoded.mjs`.
Token coverage gate: `node scripts/parity-tokens.mjs` (exit 0 = every Figma
variable has a token).

**Before:** 25 occurrences · **After:** 19 · **Token coverage:** PASS (0 unmapped)

---

## Method note that changed the result

`get_variable_defs` returns **only the variables the queried node uses**, not
the file's variable set. A single call against Home reports `Yellow/100`,
`Yellow/300` and `Black/900` as non-existent — they simply aren't used on that
page.

This had already caused a real defect: [ArticleMeta.vue](resources/js/Components/ArticleMeta.vue)
carried a docblock asserting *"Yellow/300 has no slot in the `brand` scale, so
it's an arbitrary value rather than a token."* Yellow/300 is a genuine Figma
variable. The claim, and the hardcoded value it justified, were both wrong.

`PARITY/tokens/figma-variables.json` is therefore a **union across 16 canonical
nodes**, not a single read.

## Fixed in this pass

| Value | Figma variable | Now | Sites |
|---|---|---|---|
| `#fff8eb` | `Yellow/100` | `brand-100` (added) | [ArticleShare.vue](resources/js/Components/ArticleShare.vue) ×3 |
| `#fdeac3` | `Yellow/300` | `brand-300` (added) | [ArticleMeta.vue](resources/js/Components/ArticleMeta.vue), [LeadMagnetModal.vue](resources/js/Components/LeadMagnetModal.vue) ×2 |

Both tokens were **absent from `tailwind.config.js` entirely** — the hardcoding
was a symptom, not the disease.

Verified after: `vue-tsc --noEmit` clean · `vite build` succeeds · `eslint` no
new problems (3 pre-existing `vue/html-indent` warnings in
[LeadMagnetBanner.vue](resources/js/Components/LeadMagnetBanner.vue), confirmed
present at HEAD).

---

## Remaining 19 — classified

### A · Inline-style gradient stops — 14 occurrences, **cannot** be Tailwind classes

[StatCard.vue:49-50](resources/js/Components/StatCard.vue#L49) ·
[LeadMagnetModal.vue:143-144](resources/js/Components/LeadMagnetModal.vue#L143) ·
[InsightsShowcase.vue:50-51](resources/js/Components/InsightsShowcase.vue#L50) ·
[ArticleMeta.vue:63-64](resources/js/Components/ArticleMeta.vue#L63) ·
[LeadMagnetBanner.vue:58](resources/js/Components/LeadMagnetBanner.vue#L58) ·
[Work/Show.vue:161-162](resources/js/Pages/Work/Show.vue#L161) ·
[About.vue:229-230](resources/js/Pages/About.vue#L229)

These sit in `style="…"` attributes and JS style objects. **Tailwind's
`theme()` does not resolve there** — I tried substituting it into
`InsightsShowcase.vue` and reverted it, because it would have emitted invalid
CSS and silently killed the gradient.

Faithfulness check against Figma — all correct:

- `rgb(248 185 55 / …)` = `#F8B937` = **Yellow/1000** at alpha.
- `rgb(245 185 64 / 6%→0%)` on the KPI card matches Figma's own raw fill
  `linear-gradient(139deg, rgba(245,185,64,0.06) 0%, rgba(245,185,64,0) 100%)`
  (node `71:2145`). Note `#F5B940` ≠ `#F8B937` — the design really does use a
  second, slightly different amber here. **Not a bug; do not "correct" it.**
- `#141414` = Black/1000, `#fff8eb` = Yellow/100, `#fef1d7` = Yellow/200 —
  these three *do* have tokens and could be lifted out if the gradients moved
  to a Tailwind layer or CSS custom properties. That is a refactor of working
  visual code; not done unprompted.

### B · Raw fills that Figma never named — 2 occurrences

| Site | Value | In Figma |
|---|---|---|
| [ServiceOrbit.vue:118](resources/js/Components/ServiceOrbit.vue#L118) | `bg-[#FDFCFA]` | raw fill on `268:3032` **and** `553:7779` — a recurring surface |
| [ProcessStepCard.vue:69](resources/js/Components/ProcessStepCard.vue#L69) | `hover:bg-[#f3b435]` | hover variant `537:5511`; `get_variable_defs` on `537:5510` returns no such variable, confirming a raw fill |

**These are the Phase 1 exit-criterion conflict.** The brief requires both
*"zero hardcoded values"* and *"exactly the Figma variable name"*. These colours
have no Figma variable and therefore no Figma name. Satisfying one rule breaks
the other.

I have **not** invented names for them — that is a design-system decision, not
a transcription. See Blocker B7.

### C · Non-CSS contexts — 2 occurrences

- [HeroJourney.vue:52](resources/js/Components/HeroJourney.vue#L52) —
  `stroke="#6C6C69"`, an SVG presentation attribute. Matches Figma's raw fill
  `#6C6C69` exactly.
- [app.ts:42](resources/js/app.ts#L42) — Inertia progress-bar colour, consumed
  by JS at runtime. `#F8B937` = Yellow/1000. Could import a shared constant;
  cosmetic.

### D · Not colour — untouched

179 arbitrary `[Npx]` values remain. These are **not** defects: Figma spacing
is exact px and the token scale (`space0…space96`, `radiusXS…radiusROUND`)
already covers every *named* step. Arbitrary px appears where the design uses
an unnamed measurement (e.g. `size-[68px]` on the share rail). Phase 4 verifies
them numerically against `get_design_context`; that is the right place to catch
a wrong one, not a grep.

---

## Token additions made

[tailwind.config.js](tailwind.config.js) — `brand.100` `#FFF8EB`, `brand.300`
`#FDEAC3`, with a comment recording *why* they were missing so the next reader
doesn't repeat the scoped-read mistake.

`primary white` `#ffffff` needs no custom token — it is Tailwind's built-in
`white`, and the config's `colors` block extends rather than replaces. The
audit script encodes this as an explicit allowlist entry rather than silently
passing.
