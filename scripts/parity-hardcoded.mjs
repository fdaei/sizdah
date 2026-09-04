// PARITY Phase 1 — hardcoded value scan over resources/js.
// Strips /* */, //, and <!-- --> comments so documented hexes don't count.
import { readFileSync } from 'node:fs'
import { globSync } from 'node:fs'
import { execSync } from 'node:child_process'

const files = execSync(
  "find resources/js -type f \\( -name '*.vue' -o -name '*.ts' \\)",
  { encoding: 'utf8' }
).trim().split('\n')

const strip = (s) => s
  .replace(/<!--[\s\S]*?-->/g, '')
  .replace(/\/\*[\s\S]*?\*\//g, '')
  .replace(/^\s*\/\/.*$/gm, '')

const findings = []
for (const f of files) {
  const raw = readFileSync(f, 'utf8')
  const lines = raw.split('\n')
  const cleanLines = strip(raw).split('\n')
  // re-walk original lines but only report those surviving the strip
  const survived = new Set(cleanLines.map(l => l.trim()).filter(Boolean))
  lines.forEach((line, i) => {
    if (!survived.has(line.trim())) return
    const hex = line.match(/#[0-9a-fA-F]{3,8}\b/g)
    const rgb = line.match(/\brgba?\([^)]*\)/g)
    if (hex) findings.push({ f, n: i + 1, kind: 'hex', vals: hex, line: line.trim() })
    if (rgb) findings.push({ f, n: i + 1, kind: 'rgb', vals: rgb, line: line.trim() })
  })
}

console.log(`Hardcoded colour occurrences outside comments: ${findings.length}\n`)
for (const x of findings) {
  console.log(`${x.f}:${x.n}  [${x.kind}] ${x.vals.join(', ')}`)
  console.log(`    ${x.line.slice(0, 150)}`)
}
