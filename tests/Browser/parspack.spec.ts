import { test, expect, type Frame, type Locator, type Page } from '@playwright/test'

const PARSPACK_URL = 'https://my.parspack.com/'
const email = process.env.PARSPACK_EMAIL
const password = process.env.PARSPACK_PASSWORD
const domain = process.env.PARSPACK_DOMAIN ?? 'sizdahmarketing.com'
type BrowserRoot = Page | Frame

function requiredCredentials(): { email: string; password: string } {
  if (!email || !password) {
    throw new Error(
      'Set PARSPACK_EMAIL and PARSPACK_PASSWORD before running the ParsPack test. ' +
        'Credentials are intentionally not stored in the repository.',
    )
  }

  return { email, password }
}

async function firstVisible(locators: Locator[]): Promise<Locator | null> {
  for (const locator of locators) {
    if (
      await locator
        .first()
        .isVisible()
        .catch(() => false)
    )
      return locator.first()
  }

  return null
}

function inEveryFrame(page: Page, create: (root: BrowserRoot) => Locator[]): Locator[] {
  return page.frames().flatMap(create)
}

async function signIn(page: Page, credentials: { email: string; password: string }) {
  await page.goto(PARSPACK_URL, { waitUntil: 'domcontentloaded' })
  await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined)

  // The panel can retain a session between runs. In that case there is no
  // login form, and the domain can be selected immediately.
  const emailInput = await firstVisible(
    inEveryFrame(page, (root) => [
      root.getByLabel(/email|ایمیل/i),
      root.locator('input[type="email"]'),
      root.locator('input[name*="email" i]'),
      root.locator('input[autocomplete="username"]'),
    ]),
  )

  if (!emailInput) return

  await emailInput.fill(credentials.email)

  const passwordInput = await firstVisible(
    inEveryFrame(page, (root) => [
      root.getByLabel(/password|رمز عبور|کلمه عبور/i),
      root.locator('input[type="password"]'),
      root.locator('input[autocomplete="current-password"]'),
    ]),
  )
  if (!passwordInput) throw new Error('ParsPack password field was not found.')
  await passwordInput.fill(credentials.password)

  const submit = await firstVisible(
    inEveryFrame(page, (root) => [
      root.getByRole('button', { name: /log in|login|sign in|ورود/i }),
      root.locator('button[type="submit"]'),
      root.locator('input[type="submit"]'),
    ]),
  )
  if (!submit) throw new Error('ParsPack login button was not found.')

  await Promise.all([
    page.waitForLoadState('domcontentloaded').catch(() => undefined),
    submit.click(),
  ])

  // Give an SPA enough time to render the account panel after authentication.
  await page.waitForLoadState('networkidle').catch(() => undefined)

  const loginError = await firstVisible(
    inEveryFrame(page, (root) => [root.getByText(/invalid|incorrect|نامعتبر|اشتباه/i)]),
  )
  if (loginError) {
    throw new Error('ParsPack rejected the supplied credentials.')
  }
}

async function openHosting(page: Page) {
  const domainText = await firstVisible(
    inEveryFrame(page, (root) => [root.getByText(domain, { exact: false })]),
  )
  if (!domainText) throw new Error(`Domain ${domain} was not found in ParsPack.`)

  await expect(domainText, `Domain ${domain} was not found in ParsPack`).toBeVisible({
    timeout: 30_000,
  })

  // Domain cards are links in some panel versions and buttons in others.
  // Clicking the visible domain first keeps this test independent of card CSS.
  if (await domainText.isEnabled().catch(() => false)) await domainText.click()

  const hostingAction = await firstVisible(
    inEveryFrame(page, (root) => [
      root.getByRole('link', { name: /hosting|هاست|control panel|پنل کنترل|پنل هاست/i }),
      root.getByRole('button', { name: /hosting|هاست|control panel|پنل کنترل|پنل هاست/i }),
      root.getByText(/ورود به هاست|ورود به پنل|مدیریت هاست|hosting|control panel/i),
    ]),
  )

  if (!hostingAction) {
    throw new Error(`The hosting action for ${domain} was not found in ParsPack.`)
  }

  return hostingAction
}

test('logs in to ParsPack and opens the sizdahmarketing.com hosting panel', async ({
  page,
  context,
}) => {
  if (process.env.KEEP_BROWSER_OPEN === '1') test.setTimeout(0)

  const credentials = requiredCredentials()
  await signIn(page, credentials)

  const hostingAction = await openHosting(page)
  const accountUrl = page.url()
  const popup = context.waitForEvent('page', { timeout: 8_000 }).catch(() => null)

  await hostingAction.click()
  const hostingPage = (await popup) ?? page
  await hostingPage.waitForLoadState('domcontentloaded').catch(() => undefined)

  // A control-panel handoff may use a new host, a new tab, or an in-place SPA
  // route. Accept all three while still checking that the hosting UI appeared.
  await expect
    .poll(
      async () => {
        const currentUrl = hostingPage.url()
        const bodyText = await hostingPage
          .locator('body')
          .innerText()
          .catch(() => '')

        return (
          currentUrl !== accountUrl ||
          /hosting|هاست|control panel|پنل کنترل|پنل هاست/i.test(bodyText)
        )
      },
      { timeout: 30_000 },
    )
    .toBe(true)

  if (process.env.KEEP_BROWSER_OPEN === '1') {
    // Keep the headed browser available for manual inspection. Ctrl+C ends
    // the runner and closes the browser context.
    await new Promise<void>(() => undefined)
  }
})
