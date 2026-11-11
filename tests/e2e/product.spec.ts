import { test, expect } from '@playwright/test';

const PRODUCT = '/product/linden-blossom-soy-candle/';

test.describe('Product page (Linden Blossom)', () => {
  test('offers two sizes with their own prices', async ({ page }) => {
    await page.goto(PRODUCT);
    const size = page.getByRole('combobox').first();
    await expect(size.locator('option')).toHaveText(['Choose an option', 'Small (150 g)', 'Large (250 g)']);

    await size.selectOption('Large (250 g)');
    await expect(page.locator('.woocommerce-variation-price')).toContainText('22.90');
  });

  test('has a 3D viewer that loads only when scrolled into view', async ({ page }) => {
    const libraryRequests: string[] = [];
    page.on('request', (req) => req.url().includes('model-viewer') && libraryRequests.push(req.url()));

    await page.setViewportSize({ width: 1280, height: 300 }); // short screen: viewer starts well beyond the 300 px load margin
    await page.goto(PRODUCT);
    expect(libraryRequests, 'library must not load before scrolling').toHaveLength(0);

    await page.locator('.liepa-viewer').scrollIntoViewIfNeeded();
    await expect.poll(() => libraryRequests.length).toBeGreaterThan(0);
    await expect(page.locator('model-viewer')).toHaveAttribute('src', /GlassHurricaneCandleHolder\.glb$/);
    // Needs internet: the library comes from Google's CDN. `loaded` becomes true once the model is drawn.
    await page.waitForFunction(() => (document.querySelector('model-viewer') as any)?.loaded === true, null, { timeout: 30_000 });
  });

  test('3D model file is small', async ({ request }) => {
    const response = await request.get('/wp-content/uploads/models/GlassHurricaneCandleHolder.glb');
    expect(response.ok()).toBeTruthy();
    expect((await response.body()).length).toBeLessThan(2 * 1024 * 1024); // budget: under 2 MB
  });

  test('a sold-out product cannot be added to the cart', async ({ page }) => {
    await page.goto('/product/winter-spice-soy-candle/');
    const summary = page.locator('.summary');
    await expect(summary.getByText('Out of stock')).toBeVisible();
    await expect(summary.locator('.single_add_to_cart_button')).toHaveCount(0);
  });
});
