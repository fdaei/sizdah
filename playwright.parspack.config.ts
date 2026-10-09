import { defineConfig, devices } from '@playwright/test'

/**
 * Dedicated config for the external ParsPack smoke test.
 *
 * It deliberately has no webServer: this suite exercises the ParsPack panel,
 * not the local Laravel application. Set HEADED=1 to watch the browser.
 */
export default defineConfig({
  testDir: './tests/Browser',
  testMatch: 'parspack.spec.ts',
  fullyParallel: false,
  timeout: 60_000,
  reporter: [['list']],
  use: {
    ...devices['Desktop Chrome'],
    browserName: 'chromium',
    channel: process.env.PLAYWRIGHT_CHANNEL || undefined,
    headless: process.env.HEADED !== '1',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
})
