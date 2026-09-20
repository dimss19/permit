# Bug Analysis: Senior Manager Removal - Remaining Issues & Testing Plan

**Date:** 2026-09-20  
**Branch:** revisi (commit 3000efe)

---

## Potential Bugs Found

### 1. Historical Migration Still Has Review Senior Manager ❌ CRITICAL

**File:** `database/migrations/2026_07_12_185828_alter_permits_table_add_approval_and_cancel_status.php`

**Issue:**
- Line 17 (up): adds `'Cancelled'` but still includes `'Review Senior Manager'` in enum
- Line 36 (down): rollback also includes `'Review Senior Manager'`

**Impact:**
- Migration order matters: if this runs AFTER `2026_09_20_213844_remove_senior_manager_status`, it **re-adds** the deleted status
- Fresh installs might fail OR have inconsistent enum state
- Migration timestamps: `2026_07_12` < `2026_09_20`, so normal order is safe, BUT rollback (`migrate:rollback`) will break

**Risk:** HIGH — rollback scenario creates zombie status

**Fix Required:**
Remove `'Review Senior Manager'` from lines 17 and 36 of this migration.

---

### 2. Compiled Views (storage/framework/views) Had Stale Code ✅ FIXED

**Status:** Already cleared via `php artisan view:clear` — Blade will recompile from source on next render.

---

### 3. Tests May Reference Senior Manager ⚠️ UNKNOWN

**Files to check:**
- `tests/Feature/*`
- `tests/Unit/*`

**Potential issues:**
- Test data seeding `Review Senior Manager` status
- Assertions expecting 5 roles instead of 4
- Approval flow tests assuming 3-tier (staff → manager → senior-manager)

**Risk:** MEDIUM — tests will fail, but won't affect production

---

## Testing Plan

### Phase 1: Fresh Install Verification (Critical Path)

**Goal:** Ensure clean database install works without senior-manager.

**Steps:**
1. ✅ **Fresh migration + seed**
   ```bash
   php artisan migrate:fresh --seed
   ```
   Expected: no errors, 4 users (no seniormanager_hse), 0 Review Senior Manager permits

2. ✅ **Verify enum**
   ```bash
   mysql -u root workpermit -e "SHOW COLUMNS FROM permits LIKE 'status';"
   ```
   Expected: enum has 8 values (Draft, Submitted, Review Staff, Review Manager, Revision, Active, Closed, Cancelled) — NO `Review Senior Manager`

3. ⚠️ **Test rollback safety** (WILL FAIL until migration 2026_07_12_185828 is fixed)
   ```bash
   php artisan migrate:rollback --step=1
   php artisan migrate:rollback --step=1
   ```
   Expected: should NOT re-introduce `Review Senior Manager` status

**Current Status:** Steps 1-2 pass (verified in Task 7), Step 3 will fail.

---

### Phase 2: End-to-End Approval Flow

**Goal:** Verify 2-tier approval (staff → manager → active) works in UI and DB.

**Test Scenario: Complete Permit Lifecycle**

1. **Setup:** Fresh seed, login as `divisi_teknik`
   
2. **Create Permit**
   - Navigate to `/divisi/permits/create`
   - Fill form, submit
   - Expected: status = `Review Staff`

3. **Staff Approval**
   - Login as `staff_hse`
   - Navigate to `/admin/approvals`
   - Find permit, approve with signature
   - Expected: status → `Review Manager`, success message mentions "Manager"

4. **Manager Approval (Terminal)**
   - Login as `manager_hse`
   - Navigate to `/admin/approvals`
   - Find permit, approve with signature
   - Expected: 
     - status → `Active` (NOT `Review Senior Manager`)
     - Success message: "Permit berhasil disetujui dan kini berstatus ACTIVE."

5. **Verify PDF**
   - Login as `divisi_teknik`
   - Navigate to `/divisi/permits/{id}`
   - Download PDF
   - Expected: 2 signature blocks (Staff HSE, Manager) — NO Senior Manager GA block

6. **Verify History**
   - Check `/divisi/history`
   - Filter dropdown should NOT have `Review Senior Manager` option
   - Status badges should NOT show `Review Senior Manager`

**Pass Criteria:** All 6 steps complete without errors, no senior-manager references visible.

---

### Phase 3: Edge Cases & Regression

**3.1 Manager Revision Flow**
- Manager sends permit back to revision
- Expected: status → `Revision`, divisi can edit
- Divisi resubmits → `Review Staff`
- Staff approves → `Review Manager`
- Manager approves → `Active`

**3.2 Unauthorized Access**
- Try to login with username `seniormanager_hse` / password `password`
- Expected: "These credentials do not match our records."

**3.3 Old Data Migration (if prod has existing Review Senior Manager permits)**
- Seed a permit manually with status `Review Senior Manager` via raw SQL:
  ```sql
  UPDATE permits SET status = 'Review Senior Manager' WHERE id = 1;
  ```
- Run Task 1 migration again (re-apply):
  ```bash
  php artisan migrate:rollback --step=8
  php artisan migrate
  ```
- Expected: permit migrates to `Active`, enum clean

**3.4 Dashboard Widgets**
- Login as `staff_hse`: should see "Permit Menunggu Approval" card
- Login as `manager_hse`: should see "Permit Menunggu Approval" card (NOT "Permit Aktif Hari Ini")
- Check nav badge count: should NOT query `Review Senior Manager` status

**3.5 Route Access Control**
- Try to access `/admin/dashboard` as `divisi_teknik`
- Expected: 403 or redirect (middleware blocks non-admin)
- Route list should show `role:staff,manager` (NOT `senior-manager`)

---

### Phase 4: Automated Test Suite

**Goal:** Ensure existing tests pass or are updated.

**Steps:**
1. **Run test suite**
   ```bash
   composer run test
   ```
   Expected: all tests pass (or identify failures related to senior-manager removal)

2. **Fix test failures** (if any)
   - Update test data: remove `Review Senior Manager` status from fixtures
   - Update assertions: expect 4 roles, not 5
   - Update approval flow tests: 2-tier, not 3-tier

3. **Add regression test** (optional but recommended)
   - Test file: `tests/Feature/ApprovalFlowTest.php`
   - Test case: `test_manager_approval_transitions_to_active()`
   - Assert: manager approval → `Active`, not `Review Senior Manager`

---

## Critical Fix Required Before Production

**Must fix:** Migration `2026_07_12_185828_alter_permits_table_add_approval_and_cancel_status.php`

**Change required:**
- Line 17: remove `'Review Senior Manager'` from enum
- Line 36: remove `'Review Senior Manager'` from enum

Without this fix, rollback operations will corrupt the database.

---

## Risk Assessment

| Issue | Severity | Impact | Status |
|-------|----------|--------|--------|
| Historical migration has Review Senior Manager | CRITICAL | Rollback breaks DB | ❌ Not fixed |
| Compiled views stale | Low | UI shows old data | ✅ Fixed (view:clear) |
| Tests may fail | Medium | CI/CD blocked | ⚠️ Unknown |
| Fresh install works | N/A | Verified | ✅ Passing |
| Approval flow correct | N/A | Verified | ✅ Passing |

---

## Recommended Action Plan

**Immediate (before merge to main):**
1. Fix migration `2026_07_12_185828` (remove Review Senior Manager from both up/down)
2. Run Phase 2 manual testing (complete permit lifecycle)
3. Run `composer run test` and fix any failures

**Short-term (before production deploy):**
4. Run Phase 3 edge case testing
5. Add regression test for 2-tier approval flow
6. Verify rollback safety (Phase 1 Step 3)

**Production deploy:**
7. Backup database
8. Run `php artisan migrate` (applies Task 1 migration)
9. Verify with spot-check: create → staff approve → manager approve → Active
10. Monitor logs for enum-related errors

---

**Total Testing Effort:** ~2-3 hours manual + automated test fixes
**Critical Path:** Fix migration 2026_07_12_185828 first (5 minutes)
