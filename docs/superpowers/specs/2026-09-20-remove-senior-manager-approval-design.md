# Remove Senior Manager Approval Step

**Date:** 2026-09-20  
**Status:** Approved  
**Owner:** Work Permit System

## Goal

Remove senior-manager role and `Review Senior Manager` status from permit approval flow. Manager approval becomes terminal approval.

## Current State

Approval chain: Draft → Review Staff → Review Manager → **Review Senior Manager** → Active → Closed

- 5 roles: `superadmin`, `divisi`, `staff`, `manager`, `senior-manager`
- `permits.status` enum includes `Review Senior Manager`
- `seniormanager_hse` demo user exists in seeder
- Senior Manager signature block in PDF
- ApprovalController has `senior-manager` branch
- Routes allow `senior-manager` in auth middleware

## Target State

Approval chain: Draft → Review Staff → Review Manager → **Active** → Closed

- 4 roles: `superadmin`, `divisi`, `staff`, `manager`
- `permits.status` enum excludes `Review Senior Manager`
- No senior-manager users
- No Senior Manager signature in PDF
- ApprovalController has only `staff` and `manager` branches
- Routes exclude `senior-manager`

## Changes

### 1. Database

**Migration: Remove enum value + data migration**
- Create migration `YYYY_MM_DD_remove_senior_manager_status`
- `up()`: 
  - Update any `Review Senior Manager` permits → `Active`
  - Alter enum: remove `Review Senior Manager` from list
  - Delete users where `role = 'senior-manager'` (or set to `manager`)
- `down()`: reverse (re-add enum, restore role — data loss expected)

### 2. Backend Controllers

**ApprovalController** (`app/Http/Controllers/Admin/ApprovalController.php`)
- `getRoleConfig()`: delete `elseif ($role === 'senior-manager')` block (lines 37-46)
- Manager config: change `nextStatus` from `'Review Senior Manager'` to `'Active'`
- Manager config: change `nextRoleName` from `'Senior Manager'` to `'Aktif'`

**DashboardController** (`app/Http/Controllers/Admin/DashboardController.php`)
- Remove `elseif ($role === 'senior-manager')` block (lines 40-47)
- Keep only `staff` and `manager` branches

**HistoryController** (`app/Http/Controllers/Admin/HistoryController.php`)
- Remove `'senior-manager' => 'Senior Manager'` from role map (line 19)

**SuperAdmin PermitController** (`app/Http/Controllers/SuperAdmin/PermitController.php`)
- Remove `'Review Senior Manager'` from `$allowedStatuses` array (line 15)

**Divisi DashboardController** (`app/Http/Controllers/Divisi/DashboardController.php`)
- Remove `'Review Senior Manager'` from `whereIn('status', ...)` (line 19)

**AuthenticatedSessionController** (`app/Http/Controllers/Auth/AuthenticatedSessionController.php`)
- Remove `'senior-manager' => '/admin/dashboard'` from redirect map (line 39)

### 3. Routes

**web.php** (`routes/web.php`)
- Line 25: remove `'senior-manager' => '/admin/dashboard'`
- Line 62: change middleware from `role:staff,manager,senior-manager` to `role:staff,manager`

### 4. Views

**Status badge maps** (remove `'Review Senior Manager'` entry from these files):
- `resources/views/welcome.blade.php` line 445
- `resources/views/admin/history/index.blade.php` line 64
- `resources/views/divisi/cancellations/index.blade.php` line 61
- `resources/views/admin/approvals/show.blade.php` line 10
- `resources/views/divisi/history.blade.php` line 111
- `resources/views/divisi/dashboard.blade.php` line 145
- `resources/views/divisi/permits/show.blade.php` line 11

**Status filter dropdowns** (remove `'Review Senior Manager'` from loop):
- `resources/views/admin/history/index.blade.php` line 12
- `resources/views/divisi/history.blade.php` line 20

**Dashboard** (`resources/views/admin/dashboard.blade.php`)
- Remove `'senior-manager' => 'Senior Manager'` from role map (line 8)
- Remove `@if($role === 'senior-manager')` block (lines 44-59)
- Remove `@if($role !== 'senior-manager')` conditional wrapper (line 60) — show "Permit Menunggu Approval" for all admin roles

**Layout** (`resources/views/layouts/app.blade.php`)
- Line 26: remove `'senior-manager'` from `in_array()` check
- Line 30: remove `'senior-manager' => 'Review Senior Manager'` from map
- Lines 99, 141: change `in_array($role, ['staff', 'manager', 'senior-manager'])` to `in_array($role, ['staff', 'manager'])`

**PDF template** (`resources/views/divisi/permits/pdf.blade.php`)
- Line 304: remove Senior Manager signature extraction logic (the `|| $s['role'] == 'Senior Manager'` part)
- Line 327: remove entire `<div>Senior Manager GA</div>` signature block

### 5. Seeders

**UserSeeder** (`database/seeders/UserSeeder.php`)
- Delete `seniormanager_hse` user entry (line 50-54 approximately)

**PermitSeeder** (`database/seeders/PermitSeeder.php`)
- Change any `'status' => 'Review Senior Manager'` to `'status' => 'Active'` (line 141)

### 6. Documentation

**AGENTS.md**
- Update role list: remove `senior-manager`
- Update flow description: Manager → Active (not → Senior Manager)
- Remove senior-manager from redirect map
- Remove senior-manager from route middleware note

**presentasi-sistem-permit.md** (if exists)
- Update all flow diagrams, user flow descriptions, status tables to remove senior-manager

## Non-Goals

- Do not migrate existing senior-manager users to manager — delete them (demo accounts only)
- Do not preserve `Review Senior Manager` permits as a separate archived status — migrate to `Active`
- Do not add config toggle for optional senior-manager step (YAGNI)

## Testing Strategy

**Manual verification:**
1. Run migration, check DB: no `Review Senior Manager` in enum, no senior-manager users
2. Login as manager, approve a permit → should go straight to `Active`
3. Check all views: no `Review Senior Manager` in dropdowns or badges
4. Generate PDF: no Senior Manager signature block
5. Check dashboard for manager: shows pending `Review Manager`, not senior-manager count

**Existing tests:**
- Run `composer run test` — if any test seeds senior-manager or expects `Review Senior Manager` status, update test data

## Rollback Plan

Run migration `down()` — restores enum value. Senior-manager users and in-flight permits at that status are lost (acceptable for demo data). Production rollback needs data snapshot.

## Success Criteria

- Manager approval transitions permit to `Active` directly
- No UI references to Senior Manager or `Review Senior Manager` status
- No backend code branches on `senior-manager` role
- All tests pass
- PDF shows only Staff + Manager signatures

## Files Changed Summary

**Migrations:** 1 new  
**Controllers:** 5 files  
**Routes:** 1 file  
**Views:** 10 files  
**Seeders:** 2 files  
**Docs:** 2 files (AGENTS.md, presentasi-sistem-permit.md)

Total: ~21 files touched
