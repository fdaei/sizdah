const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1440, height: 1000 } });
  await page.goto(process.argv[2] || 'http://localhost:8000/fa/insights', { waitUntil: 'networkidle' });
  await page.waitForTimeout(1000);
  await page.screenshot({ path: process.argv[3] || '/tmp/claude-1000/-home-fdaei-workspace-mine-sizdah/aa468365-f43d-4bd7-8dbf-5e8d209942b0/scratchpad/figma/local.png', fullPage: true });
  const height = await page.evaluate(() => document.documentElement.scrollHeight);
  console.log('Page height:', height);
  await browser.close();
})();
