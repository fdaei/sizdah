// PARITY Phase 2 — asset gate.
// Run: node scripts/parity-assets.mjs
//
// Calibrated to decision "Option A" (PARITY/assets.md §4): the icon ban is
// enforced wherever the Figma file actually has a node. Four glyphs have no
// Figma source at all — they live in the sub-1440 range the file never draws,
// and in runtime toast UI that was never designed — so `lucide-vue-next` stays
// for those and only those. Widening this allowlist without a Figma node is
// the failure mode this script exists to catch.
import { readFileSync, existsSync, statSync } from 'node:fs'
import { execSync } from 'node:child_process'

const sh = (c) => execSync(c, { encoding: 'utf8' }).trim()
const files = sh("find resources/js -type f \\( -name '*.vue' -o -name '*.ts' \\)").split('\n')

let failures = 0
const fail = (m) => {
  console.log(`  FAIL  ${m}`)
  failures++
}
const pass = (m) => console.log(`  ok    ${m}`)

// Glyphs with no Figma node. Each entry is a claim that the file was checked.
const ALLOWED_LUCIDE = {
  Menu: 'no sub-1440 frame exists; header 21:2159 has no hamburger',
  X: 'no mobile menu in file; dialog 416:5399 draws no close button',
  CheckCircle2: 'no toast UI designed; success state 416:6003 uses an illustration',
  XCircle: 'no toast UI designed anywhere in the file',
}
// Glyphs that DO have a Figma node — importing these again is a regression.
const BANNED_LUCIDE = ['Mail', 'MapPin', 'Phone']

console.log('\n1. lucide imports')
const imported = new Set()
for (const f of files) {
  for (const m of readFileSync(f, 'utf8').matchAll(
    /import\s*\{([^}]*)\}\s*from\s*['"]lucide-vue-next['"]/g,
  )) {
    for (const g of m[1].split(',').map((s) => s.trim()).filter(Boolean)) {
      imported.add(g)
      if (BANNED_LUCIDE.includes(g)) fail(`${f} imports "${g}" — this glyph HAS a Figma node`)
      else if (!(g in ALLOWED_LUCIDE)) fail(`${f} imports "${g}" — not on the no-Figma-source list`)
    }
  }
}
for (const g of BANNED_LUCIDE) if (!imported.has(g)) pass(`"${g}" no longer imported from lucide`)
if (imported.size) console.log(`  note  still imported (no Figma source): ${[...imported].sort().join(', ')}`)

console.log('\n2. Figma-exported footer icons present and non-empty')
for (const p of [
  'resources/images/sizdah/shared/footer-location.svg',
  'resources/images/sizdah/shared/footer-phone.svg',
  'resources/images/sizdah/shared/footer-email.svg',
]) {
  if (!existsSync(p)) fail(`missing: ${p}`)
  else if (statSync(p).size === 0) fail(`zero bytes: ${p}`) // Figma can return 0 bytes silently
  else pass(`${p} (${statSync(p).size} B)`)
}

console.log('\n3. every ~img reference resolves on disk')
const refs = new Set()
for (const f of files) {
  for (const m of readFileSync(f, 'utf8').matchAll(/~img\/([A-Za-z0-9/._-]+)/g)) refs.add(m[1])
}
let unresolved = 0
for (const r of refs) {
  const p = `resources/images/${r}`
  if (!existsSync(p)) {
    fail(`referenced but missing: ~img/${r}`)
    unresolved++
  } else if (statSync(p).size === 0) {
    fail(`referenced but zero bytes: ~img/${r}`)
    unresolved++
  }
}
if (!unresolved) pass(`all ${refs.size} references resolve, all non-empty`)

console.log('\n4. orphan assets (on disk, no importer)')
// 7 orphans predate this phase and are deliberately NOT deleted — several map
// to real Figma nodes whose consumer was lost in a rewrite, and Phase 3 is
// where that surfaces. The gate fails if the count GROWS.
const ORPHAN_BUDGET = 7
const haystack = files.map((f) => readFileSync(f, 'utf8')).join('\n')
const orphans = sh('find resources/images -type f')
  .split('\n')
  .filter((p) => !haystack.includes(p.split('/').pop()))
if (orphans.length > ORPHAN_BUDGET) {
  fail(`orphans grew to ${orphans.length} (budget ${ORPHAN_BUDGET}):`)
  orphans.forEach((o) => console.log(`          ${o}`))
} else {
  pass(`${orphans.length} orphans, within budget of ${ORPHAN_BUDGET}`)
}

console.log('\n5. build health')
const step = (label, cmd) => {
  try {
    execSync(cmd, { stdio: 'pipe' })
    pass(label)
  } catch (e) {
    fail(`${label}\n${(e.stdout || e.stderr || '').toString().slice(-1500)}`)
  }
}
step('vue-tsc --noEmit', 'npx vue-tsc --noEmit')
step('vite build', 'npx vite build')

// eslint: compare against the measured HEAD baseline, not against zero.
const BASELINE = { errors: 0, warnings: 3 }
let lint
try {
  lint = JSON.parse(execSync('npx eslint resources/js -f json', { encoding: 'utf8', stdio: 'pipe' }))
} catch (e) {
  lint = JSON.parse(e.stdout.toString())
}
const errors = lint.reduce((a, f) => a + f.errorCount, 0)
const warnings = lint.reduce((a, f) => a + f.warningCount, 0)
if (errors > BASELINE.errors) fail(`eslint errors ${errors} > baseline ${BASELINE.errors}`)
else if (warnings > BASELINE.warnings) fail(`eslint warnings ${warnings} > baseline ${BASELINE.warnings}`)
else pass(`eslint ${errors}e/${warnings}w, baseline ${BASELINE.errors}e/${BASELINE.warnings}w`)

console.log(
  `\n${failures === 0 ? 'PASS — Phase 2 gate green.' : `FAIL — ${failures} check(s) failed.`}\n`,
)
process.exit(failures === 0 ? 0 : 1)
