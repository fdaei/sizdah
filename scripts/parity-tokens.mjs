// PARITY Phase 1 — token coverage audit.
// Compares the unioned live Figma variable set against tailwind.config.js.
// Run: node scripts/parity-tokens.mjs
import { readFileSync } from 'node:fs'

const v = JSON.parse(readFileSync('PARITY/tokens/figma-variables.json', 'utf8'))
const cfg = readFileSync('tailwind.config.js', 'utf8')

// Strip BOTH block and line comments — a hex documented in prose is not a token.
const code = cfg.replace(/\/\*[\s\S]*?\*\//g, '').replace(/^\s*\/\/.*$/gm, '')

const hexes = new Set([...code.matchAll(/#[0-9a-fA-F]{3,8}\b/g)].map((m) => m[0].toLowerCase()))
const nums = new Set([...code.matchAll(/(\d+)px/g)].map((m) => m[1]))

let fail = 0
const report = (title, rows) => {
  console.log(`\n${title} (${rows.length})`)
  for (const r of rows) console.log('   ' + r)
}

// 'primary white' #ffffff is Tailwind's built-in `white`; no custom token needed.
const builtin = new Set(['#ffffff'])
const missingColor = Object.entries(v.color).filter(([, h]) => !hexes.has(h.toLowerCase()) && !builtin.has(h.toLowerCase()))
const missingSpace = Object.entries(v.space).filter(([, n]) => n !== 0 && !nums.has(String(n)))
const missingRadius = Object.entries(v.radius).filter(([, n]) => !nums.has(String(n)))
const sizes = new Set([...code.matchAll(/(\d+)px/g)].map((m) => m[1]))
const missingType = Object.entries(v.type).filter(([, t]) => !sizes.has(String(t.size)))

report('MISSING colour tokens', missingColor.map(([n, h]) => `${n.padEnd(16)} ${h}`))
report('MISSING spacing tokens', missingSpace.map(([n, x]) => `${n.padEnd(16)} ${x}`))
report('MISSING radius tokens', missingRadius.map(([n, x]) => `${n.padEnd(16)} ${x}`))
report('MISSING type sizes', missingType.map(([n, t]) => `${n.padEnd(28)} ${t.size}px/${t.weight}`))

fail = missingColor.length + missingSpace.length + missingRadius.length + missingType.length
console.log(`\n${fail === 0 ? 'PASS — every Figma variable has a token.' : `FAIL — ${fail} Figma variable(s) unmapped.`}`)
process.exit(fail === 0 ? 0 : 1)
