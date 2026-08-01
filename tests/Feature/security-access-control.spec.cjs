// @ts-check
const { test, expect } = require('@playwright/test');

const BASE = 'http://127.0.0.1:8000';
const DIVISI_USER = { username: 'divisi_teknik', password: 'password' };
const STAFF_USER  = { username: 'staff_hse', password: 'password' };
const SUPERADMIN_USER = { username: 'superadmin', password: 'password' };

async function login(page, user) {
  await page.goto(`${BASE}/login`);
  await page.fill('input[name="username"]', user.username);
  await page.fill('input[name="password"]', user.password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard', { timeout: 10000 });
}

test.describe('Security checks â€” role-based access control', () => {

  test('1. Divisi user CANNOT access admin approvals', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/admin/approvals`);
    // Should be 403 forbidden
    await expect(page.getByText('Akses ditolak').first()).toBeVisible({ timeout: 5000 });
  });

  test('2. Divisi user CANNOT access superadmin users', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/superadmin/users`);
    await expect(page.getByText('Akses ditolak').first()).toBeVisible({ timeout: 5000 });
  });

  test('3. Staff user CANNOT access superadmin dashboard', async ({ page }) => {
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/superadmin/dashboard`);
    await expect(page.getByText('Akses ditolak').first()).toBeVisible({ timeout: 5000 });
  });

  test('4. Staff user CANNOT access divisi permits', async ({ page }) => {
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/divisi/permits/create`);
    await expect(page.getByText('Akses ditolak').first()).toBeVisible({ timeout: 5000 });
  });

  test('5. Superadmin CAN access superadmin dashboard', async ({ page }) => {
    await login(page, SUPERADMIN_USER);
    await page.goto(`${BASE}/superadmin/dashboard`);
    // Should load successfully (not 403)
    await expect(page.locator('h1, h2, h3').first()).toBeVisible({ timeout: 5000 });
  });

  test('6. Unauthenticated user redirected to login', async ({ page }) => {
    await page.goto(`${BASE}/divisi/history`);
    await page.waitForURL('**/login', { timeout: 10000 });
  });
});

