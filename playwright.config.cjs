const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests/Feature',
  testMatch: '*.spec.cjs',
  timeout: 30000,
  expect: { timeout: 10000 },
  use: {
    baseURL: 'http://127.0.0.1:8000',
    headless: false,
    screenshot: 'only-on-failure',
    trace: 'retain-on-failure',
  },
  projects: [
    { name: 'chromium', use: { browserName: 'chromium' } },
  ],
});
