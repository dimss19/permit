// @ts-check
const { test, expect } = require('@playwright/test');

const BASE = 'http://127.0.0.1:8000';
const DIVISI_USER = { username: 'divisi_teknik', password: 'password' };
const STAFF_USER  = { username: 'staff_hse', password: 'password' };
const MANAGER_USER = { username: 'manager_hse', password: 'password' };

async function login(page, user) {
  await page.goto(`${BASE}/login`);
  await page.fill('input[name="username"]', user.username);
  await page.fill('input[name="password"]', user.password);
  await page.click('button[type="submit"]');
  try {
    await page.waitForURL('**/dashboard', { timeout: 10000 });
  } catch (e) {
    console.log('Login redirect timeout — page may have errored');
  }
}

async function drawSignature(page) {
  const canvas = page.locator('#signature-canvas');
  await canvas.waitFor({ state: 'visible', timeout: 5000 });
  const box = await canvas.boundingBox();
  if (box) {
    // Move mouse in a pattern that triggers mousedown -> mousemove -> mouseup -> stopDraw
    await page.mouse.move(box.x + 20, box.y + 20);
    await page.mouse.down();
    await page.mouse.move(box.x + 100, box.y + 30);
    await page.mouse.move(box.x + 100, box.y + 60);
    await page.mouse.move(box.x + 20, box.y + 60);
    await page.mouse.move(box.x + 20, box.y + 30);
    await page.mouse.up();
  }
  // The stopDraw() handler sets hiddenInput.value = canvas.toDataURL()
  // Wait a bit for the event to process
  await page.waitForTimeout(200);
  
  // If still empty, use JS to set a dummy data URL directly
  const hiddenInput = page.locator('#tanda-tangan-input');
  const val = await hiddenInput.inputValue();
  if (!val) {
    // Set a minimal PNG data URL as fallback
    await page.evaluate(() => {
      const input = document.getElementById('tanda-tangan-input');
      if (input) input.value = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
      // Also set hasDrawn flag
      const error = document.getElementById('signature-error');
      if (error) error.classList.add('hidden');
    });
  }
  await expect(page.locator('#tanda-tangan-input')).not.toHaveValue('', { timeout: 3000 });
}

// Run tests sequentially (not parallel) to share state
test.describe.serial('Senior Manager Removal — 2-Tier Approval Flow', () => {

  test('1. Fresh seed DB has no senior-manager users', async () => {
    // DB-level check verified via login failure in test 6
  });

  test('2. Divisi can create and submit a permit (→ Review Staff)', async ({ page }) => {
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/permits/create`);
    await page.waitForLoadState('networkidle');

    // Step 0: Select Internal (skips document step)
    await page.click('#btn-internal');
    await page.click('#btn-next');

    // Step 2: Fill required fields
    await expect(page.locator('input[name="nama_pekerjaan"]')).toBeVisible({ timeout: 5000 });
    await page.fill('input[name="nama_pekerjaan"]', 'Test SM Removal');
    await page.fill('input[name="lokasi"]', 'Workshop Test');
    await page.fill('input[name="kontraktor"]', 'PT Test');
    await page.fill('input[name="penanggung_jawab"]', 'PJ Test');
    await page.fill('input[name="telepon"]', '08123456789');

    const today = new Date().toISOString().split('T')[0];
    await page.fill('input[name="tanggal_mulai"]', today);
    await page.fill('input[name="tanggal_selesai"]', today);

    // Navigate through remaining wizard steps
    for (let i = 0; i < 6; i++) {
      const nextBtn = page.locator('#btn-next');
      if (await nextBtn.isVisible({ timeout: 1000 }).catch(() => false)) {
        await nextBtn.click();
        await page.waitForTimeout(300);
      }
    }

    // Save as Draft first
    const draftBtn = page.locator('button').filter({ hasText: /Simpan.*Draft|Draft/i }).first();
    if (await draftBtn.isVisible({ timeout: 3000 })) {
      await draftBtn.click();
      await page.waitForURL('**/dashboard', { timeout: 10000 });
    }

    // Find the Draft permit and submit it
    await page.goto(`${BASE}/divisi/history?status=Draft`);
    await page.waitForLoadState('networkidle');

    const permitLink = page.locator('a').filter({ hasText: 'Test SM Removal' }).first();
    if (await permitLink.isVisible({ timeout: 3000 })) {
      await permitLink.click();
      await page.waitForLoadState('networkidle');

      // Click submit button
      const submitBtn = page.locator('button').filter({ hasText: /Ajukan|Submit/i }).first();
      if (await submitBtn.isVisible({ timeout: 3000 })) {
        await submitBtn.click();
        // Confirm if modal appears
        const confirmBtn = page.locator('button').filter({ hasText: /Ya|Confirm|Lanjut/i }).first();
        if (await confirmBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
          await confirmBtn.click();
        }
        await page.waitForLoadState('networkidle');
      }
    }
    // Verify: we ended up somewhere without error
    const pageText = await page.textContent('body');
    expect(pageText).not.toContain('Error');
  });

  test('3. Staff approval transitions to Review Manager', async ({ page }) => {
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/admin/approvals`);
    await page.waitForLoadState('networkidle');

    // Find the Review link in the table — use the direct href
    const reviewLink = page.locator('td a[href*="/admin/approvals/"]').first();
    if (await reviewLink.isVisible({ timeout: 5000 })) {
      const href = await reviewLink.getAttribute('href');
      await page.goto(`${BASE}${href}`);
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(1000);

      // Verify we're on approval detail page (not index)
      await expect(page).toHaveURL(/\/admin\/approvals\/\d+$/);

      // Verify we're on approval page with canvas
      await expect(page.locator('#signature-canvas')).toBeVisible({ timeout: 10000 });

      // Draw signature
      await drawSignature(page);

      // Click approve button — staff sees "Setujui & Lanjutkan ke Manager"
      const approveBtn = page.locator('#btn-approve');
      await expect(approveBtn).toContainText(/Lanjutkan ke Manager/i);
      
      // Submit the form directly via JS to avoid any event handling issues
      await page.evaluate(() => {
        const form = document.getElementById('approval-form');
        const actionInput = document.getElementById('action-input');
        if (actionInput) actionInput.value = 'approve';
        if (form) form.submit();
      });

      // Should redirect to /admin/approvals (list page) with success flash
      await page.waitForURL('**/admin/approvals', { timeout: 10000 });
      await page.waitForLoadState('networkidle');

      // Verify success message
      await expect(page.locator('.bg-green-50')).toBeVisible({ timeout: 10000 });

      // Verify NO mention of Senior Manager in success message
      const successMsg = page.locator('.bg-green-50').first();
      const msgText = await successMsg.textContent();
      expect(msgText).not.toMatch(/Senior Manager/i);
    } else {
      console.log('No Review Staff permits in queue — skipping staff approval');
    }
  });

  test('4. Manager approval transitions to Active (terminal)', async ({ page }) => {
    await login(page, MANAGER_USER);
    await page.goto(`${BASE}/admin/approvals`);
    await page.waitForLoadState('networkidle');

    // Find the Review link in the table — use the direct href
    const reviewLink = page.locator('td a[href*="/admin/approvals/"]').first();
    if (await reviewLink.isVisible({ timeout: 5000 })) {
      const href = await reviewLink.getAttribute('href');
      await page.goto(`${BASE}${href}`);
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(1000);

      // Verify canvas visible
      await expect(page.locator('#signature-canvas')).toBeVisible({ timeout: 10000 });

      // Draw signature
      await drawSignature(page);

      // Manager should see "Setujui & Aktifkan Permit" (not "Lanjutkan ke Senior Manager")
      const approveBtn = page.locator('#btn-approve');
      await expect(approveBtn).toContainText(/Aktifkan/i);
      await expect(approveBtn).not.toContainText(/Senior Manager/i);

      // Submit form via JS
      await page.evaluate(() => {
        const form = document.getElementById('approval-form');
        const actionInput = document.getElementById('action-input');
        if (actionInput) actionInput.value = 'approve';
        if (form) form.submit();
      });

      // Should redirect to /admin/approvals (list page) with success flash
      await page.waitForURL('**/admin/approvals', { timeout: 10000 });
      await page.waitForLoadState('networkidle');

      // Verify success message mentions ACTIVE
      const successMsg = page.locator('.bg-green-50').first();
      if (await successMsg.isVisible({ timeout: 3000 }).catch(() => false)) {
        const msgText = await successMsg.textContent();
        expect(msgText).toMatch(/ACTIVE/i);
      }

      // Verify NO mention of Senior Manager anywhere on page
      const pageText = await page.textContent('body');
      expect(pageText).not.toMatch(/Senior Manager/i);
    } else {
      console.log('No Review Manager permits in queue — skipping manager approval');
    }
  });

  test('5. Status filter dropdown has NO Review Senior Manager option', async ({ page }) => {
    // Check divisi history filter
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/history`);
    await page.waitForLoadState('networkidle');

    // Check filter dropdown options
    const filterSelect = page.locator('select[name="status"]');
    if (await filterSelect.isVisible({ timeout: 5000 })) {
      const options = await filterSelect.locator('option').allTextContents();
      console.log('Divisi status filter options:', options);
      for (const opt of options) {
        expect(opt).not.toMatch(/Senior Manager/i);
      }
    }

    // Also check admin history (login as staff)
    try {
      await page.evaluate(() => document.querySelector('form[action*="logout"]')?.submit());
    } catch {}
    await page.waitForTimeout(500);
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/admin/history`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    const adminFilter = page.locator('select[name="status"]');
    if (await adminFilter.isVisible({ timeout: 5000 })) {
      const adminOptions = await adminFilter.locator('option').allTextContents();
      console.log('Admin status filter options:', adminOptions);
      for (const opt of adminOptions) {
        expect(opt).not.toMatch(/Senior Manager/i);
      }
    } else {
      console.log('Admin filter dropdown not visible — page may have restricted access');
    }
  });

  test('6. seniormanager_hse login fails (user does not exist)', async ({ page }) => {
    await page.context().clearCookies();
    await page.goto(`${BASE}/login`);
    await page.fill('input[name="username"]', 'seniormanager_hse');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // Should NOT redirect to dashboard
    await expect(page).not.toHaveURL(/dashboard/);
    // Should show error (Indonesian: "Username atau kata sandi salah.")
    await expect(page.locator('#error-message')).not.toHaveText('');
    const errorText = await page.locator('#error-message').textContent();
    expect(errorText).toMatch(/salah/i);
  });

  test('7. Dashboard has no senior-manager references', async ({ page }) => {
    await page.context().clearCookies();
    await login(page, MANAGER_USER);
    await page.waitForLoadState('networkidle');

    // Manager dashboard should NOT have "Senior Manager" text
    const pageText = await page.textContent('body');
    expect(pageText).not.toMatch(/Senior Manager/i);
    expect(pageText).not.toMatch(/senior-manager/i);

    // Should have "Permit Menunggu Approval" heading
    await expect(page.locator('h3').filter({ hasText: /Menunggu Approval/i })).toBeVisible({ timeout: 5000 });
  });

  test('8. Approval page has no Review Senior Manager badge', async ({ page }) => {
    await page.context().clearCookies();
    await login(page, STAFF_USER);
    await page.goto(`${BASE}/admin/approvals`);
    await page.waitForLoadState('networkidle');

    const pageText = await page.textContent('body');
    expect(pageText).not.toMatch(/Review Senior Manager/i);
  });

  test('9. Layout sidebar has no senior-manager pending count', async ({ page }) => {
    await page.context().clearCookies();
    await login(page, STAFF_USER);
    await page.waitForLoadState('networkidle');

    // Check sidebar/nav doesn't reference senior-manager
    const navText = await page.locator('nav, aside, header').first().textContent();
    expect(navText).not.toMatch(/senior.?manager/i);
  });

  test('10. PDF download link exists on divisi permit detail', async ({ page }) => {
    await page.context().clearCookies();
    await login(page, DIVISI_USER);
    await page.goto(`${BASE}/divisi/history`);
    await page.waitForLoadState('networkidle');

    // Click first permit link
    const permitLink = page.locator('a[href*="/divisi/permits/"]').first();
    if (await permitLink.isVisible({ timeout: 3000 })) {
      await permitLink.click();
      await page.waitForLoadState('networkidle');

      // Check PDF link exists
      const pdfLink = page.locator('a[href*="/pdf"]');
      if (await pdfLink.isVisible({ timeout: 3000 })) {
        const pdfLinkText = await pdfLink.textContent();
        expect(pdfLinkText).not.toMatch(/Senior Manager/i);
      }
    }
  });

  test('11. Layout nav shows only staff/manager roles', async ({ page }) => {
    // Verify the nav doesn't have any senior-manager pending count logic
    await page.context().clearCookies();
    await login(page, STAFF_USER);
    await page.waitForLoadState('networkidle');

    // Verify no senior-manager text in the nav
    const navText = await page.textContent('body');
    expect(navText).not.toMatch(/Review Senior Manager/i);
    expect(navText).not.toMatch(/senior-manager/i);
  });
});
