const path = require('path');
const { chromium } = require('playwright');

(async () => {
  const root = path.resolve(__dirname, '..');
  const source = `file:///${path.join(root, 'mockups', 'cart-recovery', 'index.html').replace(/\\/g, '/')}`;
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const page = await browser.newPage({ viewport: { width: 1440, height: 1000 }, deviceScaleFactor: 1 });
  await page.goto(source, { waitUntil: 'load' });
  await page.screenshot({ path: path.join(root, 'mockups', 'cart-recovery', 'review-desktop.png'), fullPage: true });

  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto(`${source}#email1`, { waitUntil: 'load' });
  for (const id of ['email1', 'email2']) {
    const email = page.locator(`#${id} .email`);
    const metrics = await email.evaluate((element) => ({
      clientWidth: element.clientWidth,
      scrollWidth: element.scrollWidth,
      viewportWidth: document.documentElement.clientWidth,
      pageScrollWidth: document.documentElement.scrollWidth,
    }));
    if (metrics.scrollWidth > metrics.clientWidth || metrics.pageScrollWidth > metrics.viewportWidth) {
      throw new Error(`${id} overflows its 390px mobile viewport: ${JSON.stringify(metrics)}`);
    }
    await email.screenshot({ path: path.join(root, 'mockups', 'cart-recovery', `${id}-mobile.png`) });
  }

  await page.goto(`${source}#popup`, { waitUntil: 'load' });
  const popup = page.locator('#popup .stage');
  await popup.screenshot({ path: path.join(root, 'mockups', 'cart-recovery', 'popup-mobile.png') });
  await browser.close();
})().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
