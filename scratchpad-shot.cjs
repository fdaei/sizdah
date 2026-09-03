// Generic screenshot + measure script.
// Usage: node shot.js <path> <outfile> <viewportW> <viewportH> [selector-to-measure-json]
const { chromium } = require('playwright');

(async () => {
  const [, , urlPath, outFile, vw, vh, selectorsJson] = process.argv;
  const width = parseInt(vw || '1440', 10);
  const height = parseInt(vh || '900', 10);
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width, height } });
  const consoleErrors = [];
  page.on('console', (msg) => {
    if (msg.type() === 'error') consoleErrors.push(msg.text());
  });
  page.on('pageerror', (err) => consoleErrors.push('pageerror: ' + err.message));

  const base = process.env.BASE_URL || 'http://localhost:8000';
  await page.goto(base + urlPath, { waitUntil: 'networkidle' });
  await page.waitForTimeout(400);

  await page.screenshot({ path: outFile, fullPage: true });

  let measurements = {};
  if (selectorsJson) {
    const selectors = JSON.parse(selectorsJson);
    for (const [name, sel] of Object.entries(selectors)) {
      const loc = page.locator(sel).first();
      const count = await page.locator(sel).count();
      if (count === 0) {
        measurements[name] = { error: 'not found', selector: sel };
        continue;
      }
      const box = await loc.boundingBox();
      const styles = await loc.evaluate((el) => {
        const cs = getComputedStyle(el);
        return {
          color: cs.color,
          backgroundColor: cs.backgroundColor,
          fontSize: cs.fontSize,
          fontWeight: cs.fontWeight,
          lineHeight: cs.lineHeight,
          padding: cs.padding,
          gap: cs.gap,
          borderRadius: cs.borderRadius,
          borderColor: cs.borderColor,
          borderWidth: cs.borderWidth,
        };
      });
      measurements[name] = { box, styles, count };
    }
  }

  console.log(JSON.stringify({ measurements, consoleErrors, url: base + urlPath }, null, 2));
  await browser.close();
})();
