import { test, expect } from '@playwright/test';

test.describe('Home page', () => {
  test('shows the hero, new products and trust badges', async ({ page }) => {
    await page.goto('/');
    await expect(page.getByRole('heading', { name: 'Hand-poured candles from Latvia' })).toBeVisible();
    await expect(page.locator('ul.products li.product')).toHaveCount(4);
    await expect(page.locator('.liepa-trust li')).toHaveCount(4);
  });

  test('says clearly that the store is a demo', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('.liepa-note')).toContainText('fictional brand');
  });
});
