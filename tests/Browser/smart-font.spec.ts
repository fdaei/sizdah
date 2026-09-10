import { test, expect } from '@playwright/test'
import { detect, isNumeric, resolveDirection, segment } from '../../resources/js/lib/typeface'

/**
 * Content-driven face selection: digits in Lahzeh (لحظه), text in Peyda (پیدا).
 *
 * Three layers, cheapest first:
 *
 *   1. Detection — pure functions, no browser. Playwright transpiles the
 *      import, so `lib/typeface.ts` is exercised as-is rather than through a
 *      copy of its rules.
 *   2. Delivery — the licensed Lahzeh files are actually served. These are
 *      the assertions that fail if someone clones the repo without the
 *      commercially-licensed fonts (same failure mode as Doran/Peyda/Maneli).
 *   3. Resolution — `font-numeric` and `font-arabic` resolve to the intended
 *      families in a real browser, which is what catches a dropped Tailwind
 *      token or a Vite build that never picked up fonts.css.
 *
 * Layers 1 and 2 are viewport-independent but run once per project in
 * playwright.config.ts. They are milliseconds each; that is cheaper than
 * carving out a second config.
 */

test.describe('typeface detection', () => {
  test('classifies Persian text as textual', () => {
    expect(detect('سلام')).toBe('text')
    expect(detect('سیزده مارکتینگ')).toBe('text')
    expect(detect('ما به برندها کمک می‌کنیم واضح‌تر حرف بزنند.')).toBe('text')
  })

  test('classifies numbers as numeric, in all three digit scripts', () => {
    expect(detect('1402')).toBe('numeric') // ASCII
    expect(detect('۱۴۰۲')).toBe('numeric') // Persian, U+06F0
    expect(detect('١٤٠٢')).toBe('numeric') // Arabic-Indic, U+0660
    expect(detect('0')).toBe('numeric')
  })

  test('keeps a formatted number in one numeric run', () => {
    // Separators only bind when digits sit on both sides — that is what holds
    // a date, a time, a price and a range together.
    expect(detect('۱۴۰۲/۰۷/۱۲')).toBe('numeric')
    expect(detect('14:30')).toBe('numeric')
    expect(detect('1,200,000')).toBe('numeric')
    expect(detect('3.14')).toBe('numeric')
    expect(detect('۱۰-۲۰')).toBe('numeric')
  })

  test('padding is not content', () => {
    expect(detect('  1402  ')).toBe('numeric')
    expect(detect('')).toBe('empty')
    expect(detect('   ')).toBe('empty')
    expect(detect(null)).toBe('empty')
  })

  test('classifies mixed content as mixed', () => {
    expect(detect('سال ۱۴۰۲ بود')).toBe('mixed')
    expect(detect('قیمت: 1,200,000 تومان')).toBe('mixed')
    expect(detect('۱۲ پروژه در ۳ سال')).toBe('mixed')
  })

  test('splits mixed content into per-face runs', () => {
    expect(segment('سال ۱۴۰۲ بود')).toEqual([
      { value: 'سال ', typeface: 'text' },
      { value: '۱۴۰۲', typeface: 'numeric' },
      { value: ' بود', typeface: 'text' },
    ])

    // The colon here is punctuation, not a time separator: no digit precedes it.
    expect(segment('قیمت: 1,200 تومان')).toEqual([
      { value: 'قیمت: ', typeface: 'text' },
      { value: '1,200', typeface: 'numeric' },
      { value: ' تومان', typeface: 'text' },
    ])

    // The Arabic comma stays a sentence comma, so this is two numbers.
    expect(segment('۱۲، ۱۳')).toEqual([
      { value: '۱۲', typeface: 'numeric' },
      { value: '، ', typeface: 'text' },
      { value: '۱۳', typeface: 'numeric' },
    ])
  })

  test('is not order-dependent across calls', () => {
    // Regression guard: the run matcher is a module-level /g regex, so a
    // stale lastIndex would make the second call skip the leading match.
    expect(segment('۱۴۰۲')).toHaveLength(1)
    expect(segment('۱۴۰۲')).toHaveLength(1)
  })

  test('resolves direction from the first strong character', () => {
    expect(resolveDirection('سلام')).toBe('rtl')
    expect(resolveDirection('سال ۱۴۰۲')).toBe('rtl')
    // Digits are bidi class AN, not strong RTL: a bare number reads LTR.
    expect(resolveDirection('۱۴۰۲')).toBe('ltr')
    expect(resolveDirection('Sizdah')).toBe('ltr')
  })

  test('isNumeric is the strict whole-value test', () => {
    expect(isNumeric('1402')).toBe(true)
    expect(isNumeric(' ۱۴۰۲ ')).toBe(true)
    expect(isNumeric('سال ۱۴۰۲')).toBe(false)
    expect(isNumeric('1402ب')).toBe(false)
  })
})

test.describe('font delivery', () => {
  // The eight weights declared in resources/css/fonts.css.
  const weights = [
    'ExtraLight',
    'Light',
    'Regular',
    'Medium',
    'SemiBold',
    'Bold',
    'ExtraBold',
    'Black',
  ]

  for (const weight of weights) {
    test(`serves LahzehFaNum-${weight}.woff2`, async ({ request }) => {
      const response = await request.get(`/fonts/lahzeh/LahzehFaNum-${weight}.woff2`)

      expect(response.status()).toBe(200)
      expect(Number(response.headers()['content-length'] ?? 0)).toBeGreaterThan(10_000)
    })
  }

  test('the browser can load Lahzeh and Peyda', async ({ page }) => {
    await page.goto('/fa')
    await page.waitForLoadState('networkidle')

    const loaded = await page.evaluate(async () => {
      await Promise.all([
        document.fonts.load('400 1em "Lahzeh"'),
        document.fonts.load('400 1em "Peyda"'),
      ])

      return {
        lahzeh: document.fonts.check('400 1em "Lahzeh"'),
        peyda: document.fonts.check('400 1em "Peyda"'),
      }
    })

    expect(loaded.lahzeh).toBe(true)
    expect(loaded.peyda).toBe(true)
  })
})

test.describe('site-wide digits', () => {
  /*
   | The requirement these guard: every figure on the site renders in Lahzeh,
   | as a Persian numeral, without any component opting in. The mechanism is
   | the `unicode-range` on the Lahzeh faces plus Lahzeh leading the three
   | stacks — so the way to break it is to reorder a stack or widen a range,
   | and neither shows up in a screenshot diff.
   */

  test('Lahzeh draws the digits inside ordinary body copy', async ({ page }) => {
    await page.goto('/fa')
    await page.waitForLoadState('networkidle')

    const widths = await page.evaluate(async () => {
      await Promise.all([
        document.fonts.load('400 1em "Lahzeh"'),
        document.fonts.load('400 1em "Peyda"'),
      ])

      // getComputedStyle reports the declared stack, not the face that drew a
      // given glyph, so measure instead: if removing Lahzeh from the front of
      // the stack changes the advance width of a digit string, Lahzeh was the
      // one drawing it.
      const measure = (fontFamily: string, text: string) => {
        const probe = document.createElement('span')
        probe.textContent = text
        probe.style.cssText = `position:absolute;visibility:hidden;white-space:pre;font-size:100px;font-family:${fontFamily}`
        document.body.append(probe)
        const width = probe.getBoundingClientRect().width
        probe.remove()

        return width
      }

      return {
        asciiWithLahzeh: measure('Lahzeh, Peyda, sans-serif', '1402'),
        asciiWithout: measure('Peyda, sans-serif', '1402'),
        // A letter must be unaffected — the range claims digits only.
        letterWithLahzeh: measure('Lahzeh, Peyda, sans-serif', 'سلام'),
        letterWithout: measure('Peyda, sans-serif', 'سلام'),
      }
    })

    expect(widths.asciiWithLahzeh).not.toBeCloseTo(widths.asciiWithout, 1)
    expect(widths.letterWithLahzeh).toBeCloseTo(widths.letterWithout, 1)
  })

  test('the digit range claims figures and nothing else', async ({ page }) => {
    await page.goto('/fa')
    await page.waitForLoadState('networkidle')

    const range = await page.evaluate(() => {
      for (const face of document.fonts as unknown as Iterable<FontFace>) {
        if (face.family.replace(/["']/g, '') === 'Lahzeh') {
          return face.unicodeRange
        }
      }

      return null
    })

    expect(range).not.toBeNull()
    const normalised = (range ?? '').toUpperCase().replace(/\s/g, '')

    expect(normalised).toContain('U+30-39') // ASCII digits, per the CSSOM's own shorthand
    expect(normalised).toContain('U+6F0-6F9') // Persian
    expect(normalised).toContain('U+660-669') // Arabic-Indic
    // Sentence punctuation must stay with the body face.
    expect(normalised).not.toContain('U+2C')
    expect(normalised).not.toContain('U+2E')
  })

  test('Lahzeh leads the Persian stacks, and only those', async ({ page }) => {
    await page.goto('/fa')
    await page.waitForLoadState('networkidle')

    const leads = await page.evaluate(() => {
      const host = document.createElement('div')
      host.innerHTML =
        '<span class="font-arabic" id="a"></span>' +
        '<span class="font-sans" id="s"></span>' +
        '<span class="font-display" id="d"></span>'
      document.body.append(host)

      const lead = (id: string) =>
        getComputedStyle(document.getElementById(id) as Element)
          .fontFamily.split(',')[0]
          .replace(/["']/g, '')
          .trim()

      const result = { arabic: lead('a'), sans: lead('s'), display: lead('d') }
      host.remove()

      return result
    })

    expect(leads.arabic).toBe('Lahzeh')
    expect(leads.display).toBe('Lahzeh')
    /*
     | `sans` is the Latin stack app.blade.php uses for `font: 'sans'` locales
     | (en). Lahzeh's FaNum cut draws ASCII 0-9 as Persian numerals, which is
     | wrong on an English page — so re-enabling `en` in config/locales.php
     | must stay the one-line move that file promises. Do not "fix" this by
     | adding Lahzeh here.
     */
    expect(leads.sans).toBe('Poppins')
  })

  test('a numeric run is tracked 0.1em tighter than the copy around it', async ({
    page,
    request,
  }) => {
    /*
     | Asserted against the BUILT stylesheet rather than the running page.
     | `npm run dev` keeps a Vite server whose Tailwind does not always re-read
     | tailwind.config.js after a new key is added, so a live-page assertion
     | here reports a stale `normal` and reads as a code defect when it is only
     | a dev server that wants restarting. The built asset is what ships.
     */
    const manifest = await request.get('/build/manifest.json')
    expect(manifest.status()).toBe(200)

    // Vite lists a JS entry's stylesheets under its `css` array, not as
    // top-level entries of their own.
    const stylesheet = Object.values(
      (await manifest.json()) as Record<string, { css?: string[] }>,
    ).flatMap((item) => item.css ?? [])[0]

    expect(stylesheet, 'no CSS in the Vite manifest — run npm run build').toBeTruthy()

    await page.goto('/fa')
    const tracking = await page.evaluate(async (href: string) => {
      const link = document.createElement('link')
      link.rel = 'stylesheet'
      link.href = href
      const ready = new Promise((resolve) => link.addEventListener('load', resolve))
      document.head.append(link)
      await ready

      const host = document.createElement('p')
      host.className = 'font-arabic'
      host.style.fontSize = '100px'
      host.innerHTML = '<span class="font-numeric tracking-numeric" id="n">۱۴۰۲</span>'
      document.body.append(host)

      const read = (el: Element) => getComputedStyle(el).letterSpacing
      const result = {
        numeric: read(document.getElementById('n') as Element),
        copy: read(host),
      }

      host.remove()
      link.remove()

      return result
    }, `/build/${stylesheet}`)

    // -0.1em at 100px resolves to -10px.
    expect(tracking.numeric).toBe('-10px')
    // The surrounding Persian copy keeps default tracking — tightening a whole
    // paragraph would tighten its letters, which is not what was asked for.
    expect(tracking.copy).toBe('normal')
  })

  test('the document root renders through one of those stacks', async ({ page }) => {
    await page.goto('/fa')
    await page.waitForLoadState('networkidle')

    // app.blade.php puts font-arabic / font-sans on <html> from the locale.
    const rootLead = await page.evaluate(() =>
      getComputedStyle(document.documentElement)
        .fontFamily.split(',')[0]
        .replace(/["']/g, '')
        .trim(),
    )

    expect(rootLead).toBe('Lahzeh')
  })
})

test.describe('face resolution in the browser', () => {
  test('the Tailwind tokens lead with the intended families', async ({ page }) => {
    await page.goto('/fa')
    await page.waitForLoadState('networkidle')

    const families = await page.evaluate(() => {
      const host = document.createElement('div')
      host.innerHTML =
        '<span class="font-numeric" id="n">۱۴۰۲</span>' +
        '<span class="font-arabic" id="t">سلام</span>'
      document.body.append(host)

      const read = (id: string) =>
        getComputedStyle(document.getElementById(id) as Element)
          .fontFamily.split(',')
          .map((entry) => entry.replace(/["']/g, '').trim())

      const result = { numeric: read('n'), text: read('t') }
      host.remove()

      return result
    })

    expect(families.numeric[0]).toBe('Lahzeh')

    /*
     | `font-arabic` leads with Lahzeh too, and that is not a bug: Lahzeh's
     | unicode-range claims only the digit blocks, so Peyda in second place is
     | what actually draws every letter. Assert the pair, not just the head —
     | dropping Peyda would leave Persian text on Vazirmatn and this test would
     | still have passed if it only checked position 0.
     */
    expect(families.text.slice(0, 2)).toEqual(['Lahzeh', 'Peyda'])
  })

  test('a numeric run inside RTL copy is bidi-isolated', async ({ page }) => {
    await page.goto('/fa')
    await page.waitForLoadState('networkidle')

    // The markup shape <SmartText> emits for mixed content.
    const order = await page.evaluate(() => {
      const host = document.createElement('p')
      host.dir = 'rtl'
      host.className = 'font-arabic'
      // Shrink-wrapped: a full-width paragraph would put this short line at
      // its right edge and make any measurement against the box meaningless.
      host.style.width = 'fit-content'
      host.innerHTML = '<span id="word">نسخه</span> <bdi class="font-numeric" id="num">۲.۰</bdi>:'
      document.body.append(host)

      const num = document.getElementById('num') as HTMLElement
      const word = document.getElementById('word') as HTMLElement
      const family = getComputedStyle(num).fontFamily

      const numRect = num.getBoundingClientRect()
      const wordRect = word.getBoundingClientRect()

      host.remove()

      return {
        family,
        // RTL flows right to left, so the number — which follows the word in
        // source order — has to paint to that word's left. Without <bdi> the
        // trailing colon joins the number's own run and shifts it.
        followsWordLeftwards: numRect.right <= wordRect.left,
      }
    })

    expect(order.family.split(',')[0].replace(/["']/g, '').trim()).toBe('Lahzeh')
    expect(order.followsWordLeftwards).toBe(true)
  })
})
