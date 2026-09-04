// PARITY Phase 4 — numeric measurement harness.
// Usage: node scripts/parity-measure.mjs <slug> <path> [--width=1440]
//   e.g. node scripts/parity-measure.mjs privacy /fa/privacy-policy
//
// Writes PARITY/measure/<slug>.json (computed styles for every laid-out
// element) and PARITY/diff/<slug>.code.png (full-page screenshot).
//
// Two things here are load-bearing; without them the whole diff loop is noise:
//   1. `document.fonts.ready` — Peyda and Maneli are self-hosted. Measuring
//      before they land gives fallback metrics and every type row lies.
//   2. animations off — GSAP/ScrollTrigger reveal animations mutate opacity
//      and transform. Measured mid-flight, the same element differs between
//      runs and the loop can never converge.
import { chromium } from 'playwright'
import { mkdirSync, writeFileSync } from 'node:fs'

const [slug, urlPath] = process.argv.slice(2)
const width = Number((process.argv.find((a) => a.startsWith('--width=')) || '').split('=')[1] || 1440)
if (!slug || !urlPath) {
  console.error('usage: node scripts/parity-measure.mjs <slug> <path> [--width=N]')
  process.exit(2)
}
const BASE = process.env.BASE_URL ?? 'http://localhost:8000'

mkdirSync('PARITY/measure', { recursive: true })
mkdirSync('PARITY/diff', { recursive: true })

const browser = await chromium.launch()
const ctx = await browser.newContext({
  viewport: { width, height: 900 },
  deviceScaleFactor: 1,
  reducedMotion: 'reduce', // honoured by motion.ts
})
const page = await ctx.newPage()

// Belt and braces: motion.ts respects prefers-reduced-motion, but any stray
// CSS transition would still drift between runs.
await page.addStyleTag({
  content: `*,*::before,*::after{animation:none!important;transition:none!important}`,
}).catch(() => {})

await page.goto(BASE + urlPath, { waitUntil: 'networkidle' })
await page.addStyleTag({
  content: `*,*::before,*::after{animation:none!important;transition:none!important}`,
})
await page.evaluate(() => document.fonts.ready)
// Reveal animations set opacity:0 pre-animation via html.motion-ready; with
// reduced motion they resolve, but give the class a beat to settle.
await page.waitForTimeout(400)

const data = await page.evaluate(() => {
  const PROPS = [
    'display', 'flexDirection', 'justifyContent', 'alignItems', 'flexWrap',
    'gridTemplateColumns', 'gap', 'rowGap', 'columnGap',
    'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft',
    'marginTop', 'marginRight', 'marginBottom', 'marginLeft',
    'fontFamily', 'fontSize', 'fontWeight', 'lineHeight', 'letterSpacing',
    'textAlign', 'textTransform', 'textDecorationLine',
    'color', 'backgroundColor', 'backgroundImage',
    'borderTopWidth', 'borderRightWidth', 'borderBottomWidth', 'borderLeftWidth',
    'borderTopColor', 'borderStyle',
    'borderTopLeftRadius', 'borderTopRightRadius', 'borderBottomRightRadius', 'borderBottomLeftRadius',
    'boxShadow', 'opacity', 'mixBlendMode', 'filter', 'backdropFilter',
    'position', 'direction',
  ]
  const path = (el) => {
    const parts = []
    for (let n = el; n && n.nodeType === 1 && parts.length < 6; n = n.parentElement) {
      let s = n.tagName.toLowerCase()
      if (n.id) { parts.unshift(`${s}#${n.id}`); break }
      const sibs = n.parentElement ? [...n.parentElement.children].filter((c) => c.tagName === n.tagName) : []
      if (sibs.length > 1) s += `:nth-of-type(${sibs.indexOf(n) + 1})`
      parts.unshift(s)
    }
    return parts.join('>')
  }
  const out = []
  let order = 0
  for (const el of document.querySelectorAll('body *')) {
    const r = el.getBoundingClientRect()
    const cs = getComputedStyle(el)
    if (cs.display === 'none' || cs.visibility === 'hidden') continue
    if (r.width === 0 && r.height === 0) continue
    const style = {}
    for (const p of PROPS) style[p] = cs[p]
    // own text only, not descendants'
    const text = [...el.childNodes]
      .filter((n) => n.nodeType === 3)
      .map((n) => n.textContent.trim())
      .join(' ')
      .trim()
    out.push({
      order: order++,
      tag: el.tagName.toLowerCase(),
      path: path(el),
      cls: el.getAttribute('class') || '',
      text: text.slice(0, 120),
      box: {
        x: +(r.x + scrollX).toFixed(2), y: +(r.y + scrollY).toFixed(2),
        w: +r.width.toFixed(2), h: +r.height.toFixed(2),
      },
      style,
    })
  }
  return {
    url: location.href,
    docWidth: document.documentElement.scrollWidth,
    docHeight: document.documentElement.scrollHeight,
    dir: document.documentElement.dir,
    lang: document.documentElement.lang,
    fonts: [...document.fonts].filter((f) => f.status === 'loaded').map((f) => `${f.family} ${f.weight}`),
    elements: out,
  }
})

writeFileSync(`PARITY/measure/${slug}.json`, JSON.stringify(data, null, 2))
await page.screenshot({ path: `PARITY/diff/${slug}.code.png`, fullPage: true })
await browser.close()

console.log(`${slug}: ${data.elements.length} elements, doc ${data.docWidth}x${data.docHeight}, dir=${data.dir}`)
console.log(`  fonts loaded: ${[...new Set(data.fonts.map((f) => f.split(' ')[0]))].join(', ') || 'NONE'}`)
