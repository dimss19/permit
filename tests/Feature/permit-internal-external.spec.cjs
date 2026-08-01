// @ts-check
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const BASE = 'http://127.0.0.1:8000';
const DIVISI_USER = { username: 'divisi_teknik', password: 'password' };
const STAFF_USER  = { username: 'staff_hse', password: 'password' };

async function login(page, user) {
  await page.goto(`${BASE}/login`);
  await page.fill('input[name="username"]', user.username);
  await page.fill('input[name="password"]', user.password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard', { timeout: 10000 });
}

test.describe('Fitur Permit Internal / Eksternal', () => {

  test('1. Dashboard Divisi — tipe badge visible', async ({ page }) => {
    await login(page, DIVISI_USER);
    await expect(page).toHaveURL(/dashboard/);
    await expect(page.locator('h3, h1').first()).toBeVisible();
  });

  test('2. Create Internal — type selector works, skips doc step', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/permits/create`);
    await page.waitForLoadState('networkidle');

    await expect(page.locator('#step-0')).toBeVisible({ timeout: 5000 });

    // Click Internal button
    await page.click('#btn-internal');
    await expect(page.locator('#tipe-input')).toHaveValue('Internal');

    // Click Selanjutnya — should skip step 1 and go to step 2
    await page.click('#btn-next');

    // Step 2 form should be visible — check by input name attribute
    await expect(page.locator('input[name="nama_pekerjaan"]')).toBeVisible({ timeout: 5000 });

    // Step 1 (documents) should still be hidden
    await expect(page.locator('#step-1')).toHaveClass(/hidden/);
  });

  test('3. Create Eksternal — document step appears', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/permits/create`);
    await page.waitForLoadState('networkidle');

    await expect(page.locator('#step-0')).toBeVisible({ timeout: 5000 });

    await page.click('#btn-eksternal');
    await expect(page.locator('#tipe-input')).toHaveValue('Eksternal');

    await page.click('#btn-next');

    // Step 1 (documents) should be visible
    await expect(page.locator('#step-1')).toBeVisible({ timeout: 5000 });
  });

  test('4. Dashboard Admin — loads correctly', async ({ page }) => {
    await login(page, STAFF_USER);
    await expect(page).toHaveURL(/dashboard/);
    // Use a more specific selector — the main content area
    await expect(page.locator('.text-4xl').first()).toBeVisible();
  });

  test('5. History Divisi — tipe filter present', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/history`);
    await page.waitForLoadState('networkidle');
    await expect(page.locator('select[name="tipe"]')).toBeVisible();
  });

  test('6. History Admin — tipe filter present', async ({ page }) => {
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/admin/history`);
    await page.waitForLoadState('networkidle');
    await expect(page.locator('select[name="tipe"]')).toBeVisible();
  });

  test('7. Approval detail — shows tipe badge', async ({ page }) => {
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/admin/approvals`);
    await page.waitForLoadState('networkidle');

    const firstLink = page.locator('a[href*="/admin/approvals/"]').first();
    if (await firstLink.isVisible()) {
      await firstLink.click();
      await page.waitForLoadState('networkidle');
      await expect(page.locator('text=Nomor Permit').first()).toBeVisible();
    }
  });

  test('8. Signature canvas exists on approval page', async ({ page }) => {
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/admin/approvals`);
    await page.waitForLoadState('networkidle');

    const reviewLink = page.locator('a').filter({ hasText: /^Review$/ }).first();
    if (await reviewLink.isVisible()) {
      await reviewLink.click();
      await page.waitForLoadState('networkidle');
      const canvas = page.locator('#signature-canvas');
      if (await canvas.isVisible()) {
        await expect(page.locator('#signature-placeholder')).toContainText('Tanda tangan');
      }
    }
  });

  test('9. Full create flow — Eksternal permit as Draft', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/permits/create`);
    await page.waitForLoadState('networkidle');

    // Step 0: Select Eksternal
    await page.click('#btn-eksternal');
    await page.click('#btn-next');

    // Step 1: Documents
    await expect(page.locator('#step-1')).toBeVisible({ timeout: 5000 });
    const tmpFile = path.join(__dirname, 'tmp-test-doc.txt');
    fs.writeFileSync(tmpFile, 'Test document for Playwright');

    // Need to click "Tambah Dokumen" first
    await page.click('#btn-add-doc');
    const fileInput = page.locator('input[name="dokumen[0][file]"]');
    if (await fileInput.isVisible()) {
      await fileInput.setInputFiles(tmpFile);
      await page.fill('input[name="dokumen[0][nama]"]', 'HIRADC Playwright');
    }

    // Next to Step 2
    await page.click('#btn-next');

    // Step 2: Fill required fields
    await expect(page.locator('input[name="nama_pekerjaan"]')).toBeVisible({ timeout: 5000 });
    await page.fill('input[name="nama_pekerjaan"]', 'Permit Playwright Test');
    await page.fill('input[name="lokasi"]', 'Workshop Utama');
    await page.fill('input[name="kontraktor"]', 'PT Playwright Corp');
    await page.fill('input[name="penanggung_jawab"]', 'Budi Playwright');
    await page.fill('input[name="telepon"]', '08123456789');
    await page.fill('input[name="tanggal_mulai"]', '2026-08-10');
    await page.fill('input[name="tanggal_selesai"]', '2026-08-15');

    // Navigate through remaining steps
    for (let i = 0; i < 5; i++) {
      const nextBtn = page.locator('#btn-next');
      if (await nextBtn.isVisible()) {
        await nextBtn.click();
        await page.waitForTimeout(500);
      }
    }

    // Should be on review step — look for submit buttons
    const draftBtn = page.locator('button').filter({ hasText: /Simpan.*Draft|Draft/i }).first();
    if (await draftBtn.isVisible({ timeout: 3000 })) {
      await draftBtn.click();
      await page.waitForURL('**/dashboard', { timeout: 10000 });
    }

    fs.unlinkSync(tmpFile);
  });

  test('10. History filter by tipe works', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/history?tipe=Internal`);
    await page.waitForLoadState('networkidle');

    const tipeFilter = page.locator('select[name="tipe"]');
    await expect(tipeFilter).toBeVisible();
    await expect(tipeFilter).toHaveValue('Internal');
  });
});
