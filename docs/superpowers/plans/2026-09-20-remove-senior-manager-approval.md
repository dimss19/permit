# Remove Senior Manager Approval Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Remove senior-manager role and `Review Senior Manager` status, making manager approval the terminal approval step.

**Architecture:** Delete senior-manager approval layer from 3-tier chain (staff → manager → senior-manager). Manager now transitions permits directly to Active. Enum migration handles in-flight data.

**Tech Stack:** Laravel 11, MySQL 8, Blade, Tailwind CSS

## Global Constraints

- Approval flow: Draft → Review Staff → Review Manager → Active → Closed
- No `Review Senior Manager` status in enum or UI
- No `senior-manager` role in routes, controllers, or auth
- Manager `nextStatus` = `Active` (not `Review Senior Manager`)
- PDF signatures: Staff + Manager only
- All UI text remains Indonesian

---

## File Structure

**Modified files:**
- `database/migrations/YYYY_MM_DD_HHMMSS_remove_senior_manager_status.php` (new)
- `app/Http/Controllers/Admin/ApprovalController.php` (lines 28-46, 144, 162)
- `app/Http/Controllers/Admin/DashboardController.php` (lines 40-47)
- `app/Http/Controllers/Admin/HistoryController.php` (line 19)
- `app/Http/Controllers/SuperAdmin/PermitController.php` (line 15)
- `app/Http/Controllers/Divisi/DashboardController.php` (line 19)
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` (line 39)
- `routes/web.php` (lines 25, 62)
- `resources/views/welcome.blade.php` (line 445)
- `resources/views/admin/history/index.blade.php` (lines 12, 64)
- `resources/views/divisi/cancellations/index.blade.php` (line 61)
- `resources/views/admin/approvals/show.blade.php` (line 10)
- `resources/views/divisi/history.blade.php` (lines 20, 111)
- `resources/views/divisi/dashboard.blade.php` (line 145)
- `resources/views/divisi/permits/show.blade.php` (line 11)
- `resources/views/admin/dashboard.blade.php` (lines 8, 44-60)
- `resources/views/layouts/app.blade.php` (lines 26, 30, 99, 141)
- `resources/views/divisi/permits/pdf.blade.php` (lines 304, 327)
- `database/seeders/UserSeeder.php` (delete entry ~line 50-54)
- `database/seeders/PermitSeeder.php` (line 141)
- `AGENTS.md` (role list, flow descriptions, redirect map)
- `presentasi-sistem-permit.md` (flow diagrams, status tables)

---

### Task 1: Database Migration

**Files:**
- Create: `database/migrations/2026_09_20_142952_remove_senior_manager_status.php`

**Interfaces:**
- Consumes: nothing
- Produces: `permits.status` enum without `Review Senior Manager`, no `senior-manager` users

- [ ] **Step 1: Create migration file**

```bash
php artisan make:migration remove_senior_manager_status
```

- [ ] **Step 2: Write migration up() method**

File: `database/migrations/2026_09_20_HHMMSS_remove_senior_manager_status.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate in-flight permits
        DB::table('permits')
            ->where('status', 'Review Senior Manager')
            ->update(['status' => 'Active']);

        // Remove senior-manager users
        DB::table('users')
            ->where('role', 'senior-manager')
            ->delete();

        // Alter enum: remove 'Review Senior Manager'
        DB::statement("ALTER TABLE permits MODIFY status ENUM('Draft', 'Submitted', 'Review Staff', 'Review Manager', 'Revision', 'Active', 'Closed', 'Cancelled') DEFAULT 'Draft'");
    }

    public function down(): void
    {
        // Re-add enum value (data loss expected)
        DB::statement("ALTER TABLE permits MODIFY status ENUM('Draft', 'Submitted', 'Review Staff', 'Review Manager', 'Review Senior Manager', 'Revision', 'Active', 'Closed', 'Cancelled') DEFAULT 'Draft'");
    }
};
```

- [ ] **Step 3: Run migration**

```bash
php artisan migrate
```

Expected output: `Migrating: 2026_09_20_HHMMSS_remove_senior_manager_status ... DONE`

- [ ] **Step 4: Verify database changes**

```bash
mysql -u root workpermit -e "SHOW COLUMNS FROM permits LIKE 'status';"
```

Expected: `status` enum does NOT include `Review Senior Manager`

```bash
mysql -u root workpermit -e "SELECT COUNT(*) FROM permits WHERE status = 'Review Senior Manager';"
```

Expected: `0`

```bash
mysql -u root workpermit -e "SELECT COUNT(*) FROM users WHERE role = 'senior-manager';"
```

Expected: `0`

- [ ] **Step 5: Commit**

```bash
git add database/migrations/2026_09_20_*_remove_senior_manager_status.php
git commit -m "db: remove Review Senior Manager status and senior-manager role"
```

---

### Task 2: Backend Controllers - Approval Logic

**Files:**
- Modify: `app/Http/Controllers/Admin/ApprovalController.php:28-46,144,162`
- Modify: `app/Http/Controllers/Admin/DashboardController.php:40-47`
- Modify: `app/Http/Controllers/Admin/HistoryController.php:19`
- Modify: `app/Http/Controllers/SuperAdmin/PermitController.php:15`
- Modify: `app/Http/Controllers/Divisi/DashboardController.php:19`
- Modify: `app/Http/Controllers/Auth/AuthenticatedSessionController.php:39`

**Interfaces:**
- Consumes: migration removes `Review Senior Manager` from enum
- Produces: manager approval → `Active`, no senior-manager controller branches

- [ ] **Step 1: Update ApprovalController getRoleConfig()**

File: `app/Http/Controllers/Admin/ApprovalController.php`

Delete lines 37-46 (the `elseif ($role === 'senior-manager')` block).

Change manager config (lines 28-36):

```php
} elseif ($role === 'manager') {
    return [
        'roleName' => 'Manager',
        'expectedStatus' => 'Review Manager',
        'nextStatus' => 'Active',
        'nextRoleName' => 'Aktif',
        'sortCol' => 'updated_at',
        'dateColumnLabel' => 'Tgl. Masuk Manager'
    ];
}
```

- [ ] **Step 2: Update ApprovalController downloadPdf() allowedStatuses**

File: `app/Http/Controllers/Admin/ApprovalController.php:144`

```php
$allowedStatuses = ['Review Staff', 'Review Manager', 'Revision', 'Active', 'Closed'];
```

- [ ] **Step 3: Update ApprovalController downloadDocument() allowedStatuses**

File: `app/Http/Controllers/Admin/ApprovalController.php:162`

```php
$allowedStatuses = ['Review Staff', 'Review Manager', 'Revision', 'Active', 'Closed'];
```

- [ ] **Step 4: Update Admin DashboardController**

File: `app/Http/Controllers/Admin/DashboardController.php`

Delete lines 40-47 (the `elseif ($role === 'senior-manager')` block).

Result: only `if ($role === 'staff')` and `elseif ($role === 'manager')` remain.

- [ ] **Step 5: Update Admin HistoryController**

File: `app/Http/Controllers/Admin/HistoryController.php:19`

Remove `'senior-manager' => 'Senior Manager'` from the role map array. Final array:

```php
$roleLabels = [
    'superadmin' => 'Super Admin',
    'divisi'     => 'Divisi',
    'staff'      => 'Staff',
    'manager'    => 'Manager',
];
```

- [ ] **Step 6: Update SuperAdmin PermitController**

File: `app/Http/Controllers/SuperAdmin/PermitController.php:15`

```php
$allowedStatuses = ['Review Staff', 'Review Manager', 'Revision', 'Active', 'Closed'];
```

- [ ] **Step 7: Update Divisi DashboardController**

File: `app/Http/Controllers/Divisi/DashboardController.php:19`

```php
->whereIn('status', ['Submitted', 'Review Staff', 'Review Manager'])
```

- [ ] **Step 8: Update AuthenticatedSessionController**

File: `app/Http/Controllers/Auth/AuthenticatedSessionController.php:39`

Remove `'senior-manager' => '/admin/dashboard'` line from the redirect map array. Final array:

```php
$redirectMap = [
    'superadmin' => '/superadmin/dashboard',
    'divisi'     => '/divisi/dashboard',
    'staff'      => '/admin/dashboard',
    'manager'    => '/admin/dashboard',
];
```

- [ ] **Step 9: Test manager approval flow**

```bash
php artisan serve
```

1. Login as `manager_hse` / `password`
2. Navigate to `/admin/approvals`
3. Open a `Review Manager` permit, approve it
4. Expected: status becomes `Active`, success message: "Permit berhasil disetujui dan kini berstatus ACTIVE."

- [ ] **Step 10: Commit**

```bash
git add app/Http/Controllers/Admin/ApprovalController.php
git add app/Http/Controllers/Admin/DashboardController.php
git add app/Http/Controllers/Admin/HistoryController.php
git add app/Http/Controllers/SuperAdmin/PermitController.php
git add app/Http/Controllers/Divisi/DashboardController.php
git add app/Http/Controllers/Auth/AuthenticatedSessionController.php
git commit -m "feat: manager approval now transitions to Active, remove senior-manager branches"
```

---

### Task 3: Routes

**Files:**
- Modify: `routes/web.php:25,62`

**Interfaces:**
- Consumes: no senior-manager users (from Task 1)
- Produces: routes block senior-manager role

- [ ] **Step 1: Remove senior-manager from redirect map**

File: `routes/web.php:25`

Remove `'senior-manager' => '/admin/dashboard'` line. Final map:

```php
$redirectMap = [
    'superadmin' => '/superadmin/dashboard',
    'divisi'     => '/divisi/dashboard',
    'staff'      => '/admin/dashboard',
    'manager'    => '/admin/dashboard',
];
```

- [ ] **Step 2: Remove senior-manager from admin route middleware**

File: `routes/web.php:62`

```php
Route::middleware(['auth', 'prevent-back-history', 'role:staff,manager'])->group(function () {
```

- [ ] **Step 3: Verify routes**

```bash
php artisan route:list --path=admin
```

Expected: all `/admin/*` routes show middleware `role:staff,manager` (NOT `senior-manager`)

- [ ] **Step 4: Commit**

```bash
git add routes/web.php
git commit -m "fix: remove senior-manager from routes and redirect map"
```

---

### Task 4: Views - Status Badges and Filters

**Files:**
- Modify: `resources/views/welcome.blade.php:445`
- Modify: `resources/views/admin/history/index.blade.php:12,64`
- Modify: `resources/views/divisi/cancellations/index.blade.php:61`
- Modify: `resources/views/admin/approvals/show.blade.php:10`
- Modify: `resources/views/divisi/history.blade.php:20,111`
- Modify: `resources/views/divisi/dashboard.blade.php:145`
- Modify: `resources/views/divisi/permits/show.blade.php:11`

**Interfaces:**
- Consumes: `Review Senior Manager` removed from enum (Task 1)
- Produces: no UI displays `Review Senior Manager` badge or filter option

- [ ] **Step 1: Remove badge from welcome.blade.php**

File: `resources/views/welcome.blade.php:445`

Delete the `'Review Senior Manager' => 'bg-orange-100 text-orange-700'` line from the status badge map.

- [ ] **Step 2: Remove from admin history index - filter dropdown**

File: `resources/views/admin/history/index.blade.php:12`

Remove `'Review Senior Manager'` from the `@foreach` array:

```php
@foreach(['Draft','Submitted','Review Staff','Review Manager','Revision','Active','Closed','Cancelled'] as $s)
```

- [ ] **Step 3: Remove from admin history index - badge map**

File: `resources/views/admin/history/index.blade.php:64`

Delete `'Review Senior Manager' => 'bg-orange-100 text-orange-700'` line.

- [ ] **Step 4: Remove from divisi cancellations index**

File: `resources/views/divisi/cancellations/index.blade.php:61`

Delete `'Review Senior Manager' => 'bg-orange-100 text-orange-700'` line.

- [ ] **Step 5: Remove from admin approvals show**

File: `resources/views/admin/approvals/show.blade.php:10`

Delete `'Review Senior Manager' => 'bg-orange-100 text-orange-700'` line.

- [ ] **Step 6: Remove from divisi history - filter dropdown**

File: `resources/views/divisi/history.blade.php:20`

```php
@foreach(['Draft','Submitted','Review Staff','Review Manager','Revision','Active','Closed','Cancelled'] as $s)
```

- [ ] **Step 7: Remove from divisi history - badge map**

File: `resources/views/divisi/history.blade.php:111`

Delete `'Review Senior Manager' => 'bg-orange-100 text-orange-700'` line.

- [ ] **Step 8: Remove from divisi dashboard**

File: `resources/views/divisi/dashboard.blade.php:145`

Delete `'Review Senior Manager' => 'bg-orange-100 text-orange-700'` line.

- [ ] **Step 9: Remove from divisi permits show**

File: `resources/views/divisi/permits/show.blade.php:11`

Delete `'Review Senior Manager' => 'bg-orange-100 text-orange-700'` line.

- [ ] **Step 10: Test UI**

```bash
php artisan serve
```

1. Visit `/` (welcome page), check status badges
2. Login as divisi, visit `/divisi/history`, check filter dropdown and badges
3. Login as staff, visit `/admin/history`, check filter dropdown
4. Expected: no `Review Senior Manager` visible anywhere

- [ ] **Step 11: Commit**

```bash
git add resources/views/welcome.blade.php
git add resources/views/admin/history/index.blade.php
git add resources/views/divisi/cancellations/index.blade.php
git add resources/views/admin/approvals/show.blade.php
git add resources/views/divisi/history.blade.php
git add resources/views/divisi/dashboard.blade.php
git add resources/views/divisi/permits/show.blade.php
git commit -m "ui: remove Review Senior Manager from status badges and filters"
```

---

### Task 5: Views - Dashboard and Layout

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php:8,44-60`
- Modify: `resources/views/layouts/app.blade.php:26,30,99,141`

**Interfaces:**
- Consumes: no senior-manager role in controllers (Task 2)
- Produces: dashboard shows only staff/manager widgets, layout excludes senior-manager checks

- [ ] **Step 1: Update admin dashboard role map**

File: `resources/views/admin/dashboard.blade.php:8`

Remove `'senior-manager' => 'Senior Manager'` from the array:

```php
$roleLabels = [
    'staff' => 'Staff HSE',
    'manager' => 'Manager HSE',
];
```

- [ ] **Step 2: Remove senior-manager dashboard widget**

File: `resources/views/admin/dashboard.blade.php:44-59`

Delete the entire `@if($role === 'senior-manager')` block (lines 44-59).

- [ ] **Step 3: Remove dashboard conditional wrapper**

File: `resources/views/admin/dashboard.blade.php:60`

Delete the `@if($role !== 'senior-manager')` line and its closing `@endif` (likely around line 75). Result: "Permit Menunggu Approval" card shows for all admin roles.

- [ ] **Step 4: Update layout pending count logic**

File: `resources/views/layouts/app.blade.php:26`

Change:

```php
if (in_array($role, ['staff', 'manager'])) {
```

- [ ] **Step 5: Remove senior-manager from pending status map**

File: `resources/views/layouts/app.blade.php:30`

Remove `'senior-manager' => 'Review Senior Manager'` line:

```php
$pendingStatus = [
    'staff'   => 'Review Staff',
    'manager' => 'Review Manager'
];
```

- [ ] **Step 6: Update layout nav check (first occurrence)**

File: `resources/views/layouts/app.blade.php:99`

```php
@if(in_array($role, ['staff', 'manager']))
```

- [ ] **Step 7: Update layout nav check (second occurrence)**

File: `resources/views/layouts/app.blade.php:141`

```php
@if(in_array($role, ['staff', 'manager']))
```

- [ ] **Step 8: Test dashboard**

```bash
php artisan serve
```

1. Login as `staff_hse` / `password`, check dashboard: shows "Permit Menunggu Approval"
2. Login as `manager_hse` / `password`, check dashboard: shows "Permit Menunggu Approval", no "Permit Aktif Hari Ini" widget
3. Check nav badge count
4. Expected: no senior-manager references, all widgets render correctly

- [ ] **Step 9: Commit**

```bash
git add resources/views/admin/dashboard.blade.php
git add resources/views/layouts/app.blade.php
git commit -m "ui: remove senior-manager from dashboard and layout"
```

---

### Task 6: PDF Template

**Files:**
- Modify: `resources/views/divisi/permits/pdf.blade.php:304,327`

**Interfaces:**
- Consumes: `approval_signatures` array with only Staff + Manager roles (from Task 2)
- Produces: PDF shows two signature blocks (Staff, Manager), not three

- [ ] **Step 1: Remove Senior Manager signature extraction**

File: `resources/views/divisi/permits/pdf.blade.php:304`

Change the line from:

```php
if($s['role'] == 'Manager' || $s['role'] == 'Senior Manager' || str_contains($s['role'], 'QM & SHE')) { $sm = $s['name']; $sm_sig = $s['signature']; }
```

To:

```php
if($s['role'] == 'Manager' || str_contains($s['role'], 'QM & SHE')) { $sm = $s['name']; $sm_sig = $s['signature']; }
```

- [ ] **Step 2: Remove Senior Manager GA signature block**

File: `resources/views/divisi/permits/pdf.blade.php:327`

Delete the entire `<div>` that contains "Senior Manager GA" (line 327 and surrounding signature block).

Expected: PDF now shows only two signature columns (Staff HSE, Manager).

- [ ] **Step 3: Test PDF generation**

```bash
php artisan serve
```

1. Login as divisi, create a permit, submit it
2. Login as staff, approve it
3. Login as manager, approve it (status → Active)
4. Download PDF from `/divisi/permits/{id}`
5. Expected: PDF shows 2 signatures (Staff, Manager), no Senior Manager block

- [ ] **Step 4: Commit**

```bash
git add resources/views/divisi/permits/pdf.blade.php
git commit -m "ui: remove Senior Manager signature block from PDF template"
```

---

### Task 7: Seeders

**Files:**
- Modify: `database/seeders/UserSeeder.php:~50-54`
- Modify: `database/seeders/PermitSeeder.php:141`

**Interfaces:**
- Consumes: no senior-manager role in system (Task 1-6)
- Produces: seeder creates no senior-manager users, no permits at `Review Senior Manager`

- [ ] **Step 1: Remove seniormanager_hse from UserSeeder**

File: `database/seeders/UserSeeder.php`

Find and delete the `seniormanager_hse` user entry (approximately lines 50-54):

```php
[
    'name' => 'Senior Manager HSE',
    'username' => 'seniormanager_hse',
    'email' => 'seniormanager_hse@inka.co.id',
    'password' => Hash::make('password'),
    'role' => 'senior-manager',
    'is_active' => true,
],
```

Delete this entire array entry.

- [ ] **Step 2: Update PermitSeeder test data**

File: `database/seeders/PermitSeeder.php:141`

Change any `'status' => 'Review Senior Manager'` to `'status' => 'Active'`.

- [ ] **Step 3: Test seeder**

```bash
php artisan migrate:fresh --seed
```

Expected output: no errors

- [ ] **Step 4: Verify seeded data**

```bash
mysql -u root workpermit -e "SELECT COUNT(*) FROM users WHERE role = 'senior-manager';"
```

Expected: `0`

```bash
mysql -u root workpermit -e "SELECT COUNT(*) FROM permits WHERE status = 'Review Senior Manager';"
```

Expected: `0`

```bash
mysql -u root workpermit -e "SELECT username, role FROM users;"
```

Expected: `superadmin`, `divisi_teknik`, `staff_hse`, `manager_hse` (no `seniormanager_hse`)

- [ ] **Step 5: Commit**

```bash
git add database/seeders/UserSeeder.php
git add database/seeders/PermitSeeder.php
git commit -m "seed: remove seniormanager_hse user and Review Senior Manager test data"
```

---

### Task 8: Documentation

**Files:**
- Modify: `AGENTS.md`
- Modify: `presentasi-sistem-permit.md`

**Interfaces:**
- Consumes: implementation complete (Task 1-7)
- Produces: docs reflect 2-tier approval (staff → manager → active)

- [ ] **Step 1: Update AGENTS.md role list**

File: `AGENTS.md`

Find the section listing roles. Change from:

```markdown
Roles (`users.role`): `superadmin`, `divisi`, `staff`, `manager`, `senior-manager`.
```

To:

```markdown
Roles (`users.role`): `superadmin`, `divisi`, `staff`, `manager`.
```

- [ ] **Step 2: Update AGENTS.md flow description**

Find permit flow descriptions. Change references from:

```markdown
staff → manager → senior-manager → active
```

To:

```markdown
staff → manager → active
```

- [ ] **Step 3: Update AGENTS.md redirect map**

Find post-login redirect map. Remove:

```markdown
'senior-manager' => '/admin/dashboard',
```

- [ ] **Step 4: Update AGENTS.md route middleware note**

Find route middleware notes. Change from:

```markdown
role:staff,manager,senior-manager
```

To:

```markdown
role:staff,manager
```

- [ ] **Step 5: Update presentasi-sistem-permit.md flow diagrams**

File: `presentasi-sistem-permit.md`

Search for all occurrences of:
- `senior-manager`
- `Senior Manager`
- `Review Senior Manager`

Replace flow diagrams to show: Staff → Manager → Active (not → Senior Manager)

Update status tables to remove `Review Senior Manager` row.

Update user flow section to show only 4 admin roles (remove senior-manager section).

- [ ] **Step 6: Commit**

```bash
git add AGENTS.md
git add presentasi-sistem-permit.md
git commit -m "docs: update flow diagrams and role descriptions to remove senior-manager"
```

---

## Self-Review Checklist

**Spec coverage:**
- ✓ Database: enum altered, data migrated (Task 1)
- ✓ Controllers: senior-manager branches removed, manager → Active (Task 2)
- ✓ Routes: senior-manager excluded from middleware (Task 3)
- ✓ Views: badges/filters updated (Task 4), dashboard/layout updated (Task 5), PDF updated (Task 6)
- ✓ Seeders: seniormanager_hse removed (Task 7)
- ✓ Docs: AGENTS.md + presentasi updated (Task 8)

**Placeholders:** None. All code blocks complete.

**Type consistency:**
- `nextStatus` = `'Active'` (string, matches enum)
- `role` checks use `'staff'`, `'manager'` (lowercase, matches DB)
- Status checks use `'Review Staff'`, `'Review Manager'`, `'Active'` (title case, matches enum)

**Testing:** Manual verification steps in each task, plus final integration test in Task 2 Step 9.

---

## Final Verification

After completing all tasks, run full test suite:

```bash
composer run test
```

Expected: all tests pass. If any test fails due to senior-manager references, update test data to match new flow.

Manual smoke test:
1. Fresh seed: `php artisan migrate:fresh --seed`
2. Login as divisi, create permit, submit
3. Login as staff, approve → should go to `Review Manager`
4. Login as manager, approve → should go to `Active`
5. Download PDF → should show 2 signatures
6. Check all dashboards, history pages, filters → no senior-manager references

---

**Plan complete. 8 tasks, ~21 files touched, atomic commits per task.**
