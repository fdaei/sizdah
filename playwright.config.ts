import { defineConfig, devices } from '@playwright/test'

/**
 * Responsive + accessibility smoke tests.
 *
 * Viewport widths mirror docs/RESPONSIVE-QA.md (Phase 7): the design's two
 * real breakpoints (402 mobile, 1440 desktop) plus the derived tablet range.
 *
 * Run: npm run test:e2e — the `webServer` block below boots the app itself and
 * reuses an already-running one, so no separate terminal is needed.
 *
 * PHP_CLI_SERVER_WORKERS matters: `artisan serve` wraps PHP's built-in server,
 * which is single-process unless that variable is set. With `fullyParallel` and
 * one worker per CPU, a single-process server queues requests until
 * `page.goto` times out, and tests fail with navigation errors that look like
 * layout defects but are pure back-pressure.
 */
export default defineConfig({
  testDir: './tests/Browser',
  fullyParallel: true,
  retries: process.env.CI ? 2 : 0,
  reporter: [['html', { open: 'never' }]],

  use: {
    baseURL: process.env.BASE_URL ?? 'http://localhost:8000',
    trace: 'on-first-retry',
  },

  /*
   | Reused when a server is already listening (the default outside CI), so a
   | `composer run dev` session is not fought over.
   */
  webServer: {
    command: 'PHP_CLI_SERVER_WORKERS=8 php artisan serve --port=8000',
    url: 'http://localhost:8000/fa',
    reuseExistingServer: !process.env.CI,
    timeout: 60_000,
  },

  projects: [
    // 320 is the narrowest viewport still in use (iPhone SE 1st gen). It is
    // below anything the file designs for, so it exists purely to catch
    // min-width floors — fixed thumbnails, un-shrinkable inputs — that only
    // surface once the content box drops under ~300px.
    { name: 'mobile-320', use: { viewport: { width: 320, height: 800 } } },
    { name: 'mobile-360', use: { ...devices['Pixel 5'], viewport: { width: 360, height: 800 } } },
    // The only WebKit project. Needs `npx playwright install-deps` (root) —
    // without those libs it fails to launch, which reads as 12 layout failures.
    { name: 'mobile-390', use: { ...devices['iPhone 12'], viewport: { width: 390, height: 844 } } },
    { name: 'mobile-414', use: { viewport: { width: 414, height: 896 } } },
    { name: 'tablet-768', use: { viewport: { width: 768, height: 1024 } } },
    { name: 'tablet-1024', use: { viewport: { width: 1024, height: 1366 } } },
    { name: 'desktop-1440', use: { viewport: { width: 1440, height: 900 } } },
    { name: 'desktop-1920', use: { viewport: { width: 1920, height: 1080 } } },
  ],
})
