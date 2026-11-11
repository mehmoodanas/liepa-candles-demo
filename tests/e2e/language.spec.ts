import { test, expect } from '@playwright/test';

test.describe('English / Latvian switcher', () => {
  test('switching to Latvian shows Latvian content only', async ({ page }) => {
    await page.goto('/');
    await page.locator('.liepa-lang').getByText('LV', { exact: true }).click();

    await expect(page).toHaveURL(/\/lv\//);
    await expect(page.getByRole('heading', { name: 'Rokām liets sveces no Latvijas' })).toBeVisible();
    await expect(page.locator('ul.products li.product')).toHaveCount(4);
    await expect(page.locator('ul.products')).not.toContainText('Soy Candle');
  });

  test('switching back to English shows English products', async ({ page }) => {
    await page.goto('/lv/');
    await page.locator('.liepa-lang').getByText('EN', { exact: true }).click();

    await expect(page.getByRole('heading', { name: 'Hand-poured candles from Latvia' })).toBeVisible();
    await expect(page.locator('ul.products')).not.toContainText('sojas svece');
  });
});
