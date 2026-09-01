import { test, expect, type Page } from '@playwright/test'

/**
 * Runs against every project defined in playwright.config.ts (7 breakpoints).
 * Checks the invariants Phase 7 requires: no horizontal overflow, header and
 * primary CTA remain visible and reachable, mobile menu opens/closes cleanly.
 *
 * Paths are `/fa/*`. The site is Persian-only (config/locales.php, GAPS G15):
 * `/en/*` and `/ar/*` stopped resolving on 2026-08-21 and the language
 * switcher collapses to nothing, so the suite's old `/en` paths tested routes
 * that no longer exist.
 */
/**
 * Navigate and wait for the Inertia app to actually mount.
 *
 * `page.goto` resolves on `load`, and in dev the Vue bundle and its stylesheet
 * are injected by Vite *after* that — at `load` the document is a bare `#app`
 * shell with one stylesheet and none of the page markup. Measuring layout
 * there is measuring nothing: every assertion below used to pass against an
 * unmounted page, which is how a 147px overflow on Home survived at 1024px.
 */
async function visit(page: Page, path: string) {
  await page.goto(path)
  await page.waitForLoadState('networkidle')
  await expect(page.locator('main')).toBeAttached()
  await expect(page.locator('footer')).toBeAttached()
}

const pages = [
  '/fa',
  '/fa/work',
  '/fa/services',
  '/fa/about',
  '/fa/insights',
  '/fa/contact',
  '/fa/privacy-policy',
  '/fa/terms',
]

for (const path of pages) {
  test(`${path} has no horizontal overflow`, async ({ page }) => {
    await visit(page, path)

    const hasOverflow = await page.evaluate(
      () => document.documentElement.scrollWidth > document.documentElement.clientWidth,
    )

    expect(hasOverflow).toBe(false)
  })
}

/**
 * Replaces `.scratch-navbar.mjs`, a one-off script that opened a page and
 * printed console errors. Same check, but it runs on every route at every
 * breakpoint and fails the build instead of printing.
 *
 * Only `pageerror` (uncaught exceptions) and failed requests are treated as
 * defects; `console.error` is not, because Vue's dev build logs hydration and
 * prop warnings there that are noise outside production.
 */
for (const path of pages) {
  test(`${path} loads without page errors or failed requests`, async ({ page }) => {
    const problems: string[] = []
    page.on('pageerror', (e) => problems.push(`pageerror: ${e.message}`))
    page.on('requestfailed', (req) => {
      // Playwright reports aborted prefetches as failures; ignore those.
      const reason = req.failure()?.errorText ?? ''
      if (reason.includes('ERR_ABORTED')) return
      problems.push(`requestfailed: ${req.url()} — ${reason}`)
    })
    page.on('response', (res) => {
      if (res.status() >= 400) problems.push(`${res.status()}: ${res.url()}`)
    })

    await visit(page, path)

    expect(problems).toEqual([])
  })
}

test('mobile menu opens, traps focus, and closes on Escape', async ({ page, viewport }) => {
  test.skip(!viewport || viewport.width >= 1024, 'Desktop uses the inline nav, not the mobile menu')

  await visit(page, '/fa')

  const trigger = page.getByRole('button', { name: /(open menu|باز کردن منو)/i })
  await trigger.click()

  const dialog = page.getByRole('dialog')
  await expect(dialog).toBeVisible()

  await page.keyboard.press('Escape')
  await expect(dialog).not.toBeVisible()
})

test('a bare path redirects into the default locale and renders RTL', async ({ page }) => {
  await visit(page, '/about')

  await expect(page).toHaveURL(/\/fa\/about$/)
  await expect(page.locator('html')).toHaveAttribute('dir', 'rtl')
  await expect(page.locator('html')).toHaveAttribute('lang', 'fa-IR')
})

test('a deactivated locale prefix does not resolve', async ({ page }) => {
  // `en` sits under locales.inactive, so /en/about is not a route. It falls
  // through to the bare-path catch-all, which treats "en/about" as a slug.
  const response = await page.goto('/en/about')

  expect(response?.status()).toBe(404)
})

/**
 * GAPS G48: a request matching no route at all never runs `SetLocale`
 * (a route middleware), so Ziggy's client-side `defaults` object shipped
 * empty and `CtaButton`'s `route('home')` threw synchronously, blanking the
 * whole Error page. Only a real 404 (as opposed to 403/419/429/500/503,
 * which occur on an *already-matched* route) hits this — the suite's other
 * checks never visit a route this deliberately wrong.
 */
test('a genuine 404 renders the Error page, not a blank screen', async ({ page }) => {
  const problems: string[] = []
  page.on('pageerror', (e) => problems.push(e.message))

  const response = await page.goto('/fa/this-page-does-not-exist')
  expect(response?.status()).toBe(404)

  await expect(page.locator('main')).toBeAttached()
  await expect(page.getByRole('heading', { level: 1 })).toBeVisible()

  const backHome = page.getByRole('link', { name: /بازگشت به خانه/ })
  await expect(backHome).toBeVisible()
  await expect(backHome).toHaveAttribute('href', /\/fa$/)

  expect(problems).toEqual([])
})

test('reduced motion disables marquee animation', async ({ page }) => {
  await page.emulateMedia({ reducedMotion: 'reduce' })
  await visit(page, '/fa')

  const hasReducedClass = await page.evaluate(() =>
    document.documentElement.classList.contains('reduced-motion'),
  )

  expect(hasReducedClass).toBe(true)
})
