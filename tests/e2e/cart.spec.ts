import { test, expect } from '@playwright/test';

test.describe('Cart', () => {
  test.beforeEach(async ({ page }) => {
    // Add one Large Linden Blossom candle (22.90 EUR).
    await page.goto('/product/linden-blossom-soy-candle/');
    await page.getByRole('combobox').first().selectOption('Large (250 g)');
    const addToCart = page.locator('.single_add_to_cart_button');
    await expect(addToCart).not.toHaveClass(/disabled/); // WooCommerce enables it once the size is matched
    await addToCart.click();
    await expect(page.getByText('has been added to your cart')).toBeVisible();
    await page.goto('/cart/');
  });

  test('contains the chosen size and price', async ({ page }) => {
    await expect(page.getByText('Linden Blossom Soy Candle').first()).toBeVisible();
    await expect(page.getByText('Large (250 g)').first()).toBeVisible();
    await expect(page.getByText('22.90').first()).toBeVisible();
  });

  test('applies the WELCOME10 coupon', async ({ page }) => {
    await page.getByText('Add coupons').click();
    await page.getByLabel('Enter code').fill('WELCOME10');
    await page.getByRole('button', { name: 'Apply' }).click();
    await expect(page.getByText('WELCOME10').first()).toBeVisible();
    await expect(page.getByText('2.29').first()).toBeVisible(); // 10 percent of 22.90
  });

  test('selects the cheapest demo shipping method for Latvia', async ({ page }) => {
    // 22.90 EUR is below the free-shipping limit (35 EUR), so the cheapest paid method is chosen.
    await expect(page.getByText('Parcel locker (demo rate)')).toBeVisible();
    await expect(page.getByText('2.99').first()).toBeVisible();
  });
});
