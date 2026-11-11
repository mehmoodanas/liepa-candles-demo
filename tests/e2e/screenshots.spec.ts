import { test } from '@playwright/test';
import path from 'node:path';

/** Not a real test: takes the pictures used in the README. Run with `npm run screenshots`. */
const OUT = path.resolve(__dirname, '../../docs/screenshots');

test.describe('@screenshots', () => {
  test.use({ viewport: { width: 1280, height: 800 } });

  test('home page (English)', async ({ page }) => {
    await page.goto('/');
    await page.screenshot({ path: `${OUT}/home-en.png`, fullPage: true });
  });

  test('home page (Latvian)', async ({ page }) => {
    await page.goto('/lv/');
    await page.screenshot({ path: `${OUT}/home-lv.png`, fullPage: true });
  });

  test('shop page', async ({ page }) => {
    await page.goto('/shop/');
    await page.screenshot({ path: `${OUT}/shop.png`, fullPage: true });
  });

  test('product page with the 3D model', async ({ page }) => {
    await page.goto('/product/linden-blossom-soy-candle/');
    await page.locator('.liepa-viewer').scrollIntoViewIfNeeded();
    await page.waitForFunction(() => (document.querySelector('model-viewer') as any)?.loaded === true, null, { timeout: 30_000 });
    await page.waitForTimeout(1500); // let the first frame render
    await page.locator('.liepa-viewer').screenshot({ path: `${OUT}/product-3d.png` });
    await page.evaluate(() => window.scrollTo(0, 0));
    await page.waitForTimeout(500);
    await page.screenshot({ path: `${OUT}/product-top.png` }); // top of the page only (no full-page capture: it breaks the sticky bar)
  });

  test('product page on a phone', async ({ browser }) => {
    const phone = await browser.newPage({ viewport: { width: 390, height: 844 }, deviceScaleFactor: 2 });
    await phone.goto('http://liepa-candles.local/');
    await phone.screenshot({ path: `${OUT}/home-mobile.png`, fullPage: false });
    await phone.close();
  });
});
