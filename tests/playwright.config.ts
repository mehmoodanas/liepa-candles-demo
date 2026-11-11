import { defineConfig } from '@playwright/test';

/**
 * Runs against the local store, so the site must be started in Local first.
 * Uses the Chrome that is already installed (no extra browser download).
 */
export default defineConfig({
  testDir: './e2e',
  timeout: 60_000,
  workers: 1, // one shared local server: keep tests in order
  reporter: [['list'], ['html', { open: 'never' }]],
  use: {
    baseURL: process.env.STORE_URL ?? 'http://liepa-candles.local',
    channel: 'chrome',
    trace: 'retain-on-failure',
  },
});
