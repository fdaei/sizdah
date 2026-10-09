# ParsPack hosting smoke test

The test logs in to `my.parspack.com`, finds `sizdahmarketing.com`, and opens
its hosting/control-panel entry. It does not save credentials in the project.

Run it with the credentials supplied through the environment:

```sh
PARSPACK_EMAIL='your-email' \
PARSPACK_PASSWORD='your-password' \
KEEP_BROWSER_OPEN=1 npm run test:e2e:parspack
```

The command runs headed Chromium so the browser window is visible. To leave the
window open for your inspection, add `KEEP_BROWSER_OPEN=1` and press `Ctrl+C`
when you are done. Set `PARSPACK_DOMAIN` when a different domain should be
opened. For a headless run, use the dedicated config directly with `HEADED=0`:

```sh
HEADED=0 PARSPACK_EMAIL='your-email' PARSPACK_PASSWORD='your-password' \
npx playwright test --config=playwright.parspack.config.ts
```
