// PARITY Phase 4 — numeric diff.
// Usage: node scripts/parity-diff.mjs <slug>
// Reads PARITY/specs/<slug>.json + PARITY/measure/<slug>.json,
// writes PARITY/diff/<slug>.md, exits non-zero if any check FAILs.
//
// Tolerances (from the brief):
//   exact  — colour, font-family, font-weight, font-size, line-height, DOM order
//   <=1px  — dimensions, position, padding, gap, radius
import { readFileSync, writeFileSync, mkdirSync } from 'node:fs'

const slug = process.argv[2]
if (!slug) { console.error('usage: node scripts/parity-diff.mjs <slug>'); process.exit(2) }

const spec = JSON.parse(readFileSync(`PARITY/specs/${slug}.json`, 'utf8'))
const meas = JSON.parse(readFileSync(`PARITY/measure/${slug}.json`, 'utf8'))
mkdirSync('PARITY/diff', { recursive: true })

const EXACT = new Set(['color', 'backgroundColor', 'fontFamily', 'fontWeight', 'fontSize', 'lineHeight', 'borderTopColor', 'direction', 'display', 'flexDirection', 'order'])

// --- element lookup -------------------------------------------------------
const find = (q) => {
  let pool = meas.elements
  if (q.tag) pool = pool.filter((e) => e.tag === q.tag)
  if (q.text) pool = pool.filter((e) => e.text.includes(q.text))
  if (q.cls) pool = pool.filter((e) => q.cls.split(' ').every((c) => e.cls.split(/\s+/).includes(c)))
  if (q.minW) pool = pool.filter((e) => e.box.w >= q.minW)
  if (q.minH) pool = pool.filter((e) => e.box.h >= q.minH)
  if (typeof q.nth === 'number') return pool[q.nth]
  return pool[0]
}
const get = (el, prop) => {
  if (prop.startsWith('box.')) return el.box[prop.slice(4)]
  if (prop === 'order') return el.order
  return el.style[prop]
}
const norm = (v) => {
  if (typeof v !== 'string') return v
  return v.trim().replace(/\s+/g, ' ').toLowerCase()
}
const px = (v) => (typeof v === 'string' && v.endsWith('px') ? parseFloat(v) : typeof v === 'number' ? v : NaN)

const rows = []
let failed = 0, passed = 0, waived = 0

// Page height is always checked. `waivePageHeight` only suppresses the
// pass/fail verdict — the number is still reported — and is only honoured
// when the spec also carries a waiver explaining it, so it cannot be used
// as a silent escape hatch.
{
  const exp = spec.frame.h, act = meas.docHeight
  const d = Math.abs(exp - act)
  const ok = d <= 1
  const canWaive = spec.waivePageHeight === true && spec.waivers?.length > 0
  const status = ok ? 'PASS' : canWaive ? 'WAIVED' : 'FAIL'
  rows.push({ el: 'page', node: spec.figmaNode, prop: 'height', figma: exp, code: act, delta: d.toFixed(2), status })
  if (status === 'PASS') passed++
  else if (status === 'WAIVED') waived++
  else failed++
}

for (const c of spec.checks) {
  const el = find(c.find)
  if (!el) {
    rows.push({ el: c.desc, node: c.node || '', prop: c.prop, figma: c.expect, code: 'ELEMENT NOT FOUND', delta: '—', status: 'FAIL' })
    failed++
    continue
  }
  const actRaw = get(el, c.prop)
  const isNum = c.prop.startsWith('box.') || !isNaN(px(actRaw))
  let status, delta = '—'
  if (c.waive) {
    status = 'WAIVED'; waived++
    delta = isNum ? Math.abs(px(actRaw) - Number(c.expect)).toFixed(2) : '—'
  } else if (isNum && typeof c.expect === 'number') {
    const a = c.prop.startsWith('box.') ? actRaw : px(actRaw)
    const d = Math.abs(a - c.expect)
    const tol = c.tol ?? (EXACT.has(c.prop) ? 0 : 1)
    status = d <= tol ? 'PASS' : 'FAIL'
    delta = d.toFixed(2)
    status === 'PASS' ? passed++ : failed++
  } else {
    const ok = norm(actRaw) === norm(c.expect)
    status = ok ? 'PASS' : 'FAIL'
    delta = ok ? '0' : 'differs'
    ok ? passed++ : failed++
  }
  rows.push({ el: c.desc, node: c.node || '', prop: c.prop, figma: c.expect, code: typeof actRaw === 'number' ? +actRaw.toFixed(2) : actRaw, delta, status })
}

const esc = (v) => String(v).replace(/\|/g, '\\|')
const md = [
  `# PARITY diff — ${spec.name}`,
  ``,
  `**Figma:** \`${spec.figmaNode}\` ${spec.frame.w}×${spec.frame.h} · **Route:** \`${spec.route}\` · **Viewport:** 1440`,
  `**Measured:** ${meas.elements.length} elements · fonts: ${[...new Set(meas.fonts.map((f) => f.split(' ')[0]))].join(', ')}`,
  ``,
  `**${passed} pass · ${failed} fail${waived ? ` · ${waived} waived` : ''}**`,
  ``,
  `| المان | node | خاصیت | Figma | کد | اختلاف | وضعیت |`,
  `|---|---|---|---|---|---|---|`,
  ...rows.map((r) => `| ${esc(r.el)} | \`${r.node}\` | ${r.prop} | ${esc(r.figma)} | ${esc(r.code)} | ${r.delta} | ${r.status} |`),
]
if (spec.waivers?.length) {
  md.push(``, `## Waived — design-file defects, not code defects`, ``)
  for (const w of spec.waivers) md.push(`- **${w.what}** — ${w.why}`)
}
writeFileSync(`PARITY/diff/${slug}.md`, md.join('\n') + '\n')

console.log(`${slug}: ${passed} pass, ${failed} fail${waived ? `, ${waived} waived` : ''}`)
for (const r of rows.filter((r) => r.status === 'FAIL')) {
  console.log(`  FAIL ${r.el} [${r.node}] ${r.prop}: figma=${r.figma} code=${r.code} Δ=${r.delta}`)
}
process.exit(failed === 0 ? 0 : 1)
