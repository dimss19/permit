# Permit Internal / Eksternal — Design Spec

**Date:** 2026-08-01
**Status:** Approved
**Feature:** Permit type differentiation (Internal vs External) with document upload for external permits

---

## Goal

Split permits into two types — Internal and External. Both share the same form fields. External permits require supporting documents (name, description, file upload) before submission. The type is visible across all role dashboards, tables, and approval views.

---

## Database Changes

### New column on `permits`

```php
$table->enum('tipe', ['Internal', 'Eksternal'])->default('Internal');
```

### New table `permit_documents`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT PK | Auto-increment |
| permit_id | BIGINT FK → permits.id | Cascade on delete |
| nama_dokumen | VARCHAR | User-provided document name |
| deskripsi | TEXT NULL | Optional description |
| file_path | VARCHAR | Storage path (`permits/{permit_id}/{timestamp}-{random8chars}.{ext}`) |
| file_type | VARCHAR | File extension (pdf, jpg, docx, etc.) |
| file_size | UNSIGNED BIGINT | File size in bytes |
| created_at | TIMESTAMP | — |
| updated_at | TIMESTAMP | — |

### Relasi

```php
// Permit model
public function documents()
{
    return $this->hasMany(PermitDocument::class);
}

// PermitDocument model
public function permit()
{
    return $this->belongsTo(Permit::class);
}
```

---

## Wizard Flow

### Step 0 — Pilih Tipe Permit

Two large buttons side-by-side:

- **Internal** — icon factory/building, description: "Permit untuk pekerjaan internal perusahaan"
- **Eksternal** — icon person/construction, description: "Permit untuk pekerjaan oleh kontraktor eksternal"

Selected button highlighted with `inka-navy` border. User must choose before proceeding. Stored in `tipe` field.

### Step 1 — Dokumen Pendukung (External only)

Only shown when tipe = Eksternal. Form section with:

- "+ Tambah Dokumen" button
- Each document entry:
  - Field: Nama Dokumen (text, required)
  - Field: Deskripsi (textarea, optional)
  - Upload File (drag & drop or click, max 10MB, any file type)
  - Preview: filename + size + remove button
- Unlimited documents
- Warning shown if no documents: "Minimal upload 1 dokumen pendukung"
- "Lanjutkan" button disabled if zero documents

### Steps 2–5 — Existing Form (unchanged)

- Step 2: Informasi Pekerjaan
- Step 3: Bahaya & Pencegahan
- Step 4: APD
- Step 5: Tanda Tangan & Review

Internal permits: jump directly to Step 2 after Step 0.
External permits: go to Step 1 after Step 0, then Step 2.

### Step 6 — Review (modified)

Add "Dokumen Pendukung" section above the signature area (external only), listing all uploaded documents with name, description, file info.

---

## File Storage

### Location

`storage/app/permits/{permit_id}/`

### Naming

Original file renamed to `{timestamp}-{random8chars}.{ext}` to avoid collision. Original filename stored in `nama_dokumen` column.

### Validation (server-side)

- Max 10MB per file
- MIME type validation (not just extension)
- Validation failure returns error to view without crash

### Deletion

- When permit is deleted (cascade): all files in `storage/app/permits/{permit_id}/` deleted
- Individual document removal (before submit): delete file from storage + delete record

---

## Edit Rules

### Draft / Revision status

- Tipe selector (two big buttons) shown at top of edit form, same as create
- User can switch between Internal / External

### Status > Revision (locked)

- Tipe displayed as read-only text, not clickable

### When switching type in Draft/Revision

- Internal → External: show document upload form, user must upload ≥1 document before submit
- Eksternal → Internal: show confirmation modal ("Yakin? Semua dokumen pendukung akan dihapus."), then delete all permit documents + files

---

## All Role Tables & Dashboards — Tipe Column

| Halaman | Komponen | Penambahan |
|---------|----------|------------|
| Dashboard Divisi | Widget + tabel permit terbaru | Badge Internal/Eksternal |
| Dashboard Staff | Widget + tabel permit menunggu review | Badge Internal/Eksternal |
| Dashboard Manager | Widget + tabel permit menunggu approval | Badge Internal/Eksternal |
| Dashboard Senior Manager | Widget + tabel permit menunggu final approval | Badge Internal/Eksternal |
| Dashboard Super Admin | — | Tidak ada perubahan |
| History Divisi | Tabel | Badge + filter Tipe |
| History Admin | Tabel | Badge + filter Tipe |
| Approval List | Tabel | Badge Internal/Eksternal |

### Badge Style

- Internal: gray badge
- External: blue badge

### Filter

History and Approval pages get a "Tipe" filter: Semua / Internal / Eksternal.

---

## Approval View — Document Display

### Approval page (`/admin/approvals/{id}`)

Add "Dokumen Pendukung" section below permit info:

- Card layout listing each document
- Each card: document name, description, file type icon, file size
- **Download** button for file download
- **Preview** button to open file in new tab (PDF/images)
- Non-previewable files (Word, Excel, etc.): Download button only

### Divisi detail page (`/divisi/permits/{id}`)

Same layout: document list with download + preview.

### History list

No document details. Small indicator icon showing permit has supporting documents.

---

## Impact on Existing Features

### No changes

- Approval flow: same chain (Staff → Manager → Senior Manager) for both types
- Closing permit: unchanged
- Permit cancellation: unchanged

### Updates required

- **Email notification**: add tipe info (Internal/Eksternal) to email body
- **PDF permit**: add "Tipe Permit" line in generated PDF
- **Dashboard widgets**: no separate counts per tipe, just badge display

---

## Relationships to Existing Code

### Files to modify

- `database/migrations/` — new migration for `tipe` column + `permit_documents` table
- `app/Models/Permit.php` — add `documents()` relationship, add `tipe` to fillable/casts
- `app/Models/PermitDocument.php` — new model
- `app/Http/Controllers/Divisi/PermitController.php` — handle tipe selection + document upload in store/update
- `resources/views/divisi/permits/create.blade.php` — add Step 0 (type buttons) + Step 1 (document upload)
- `resources/views/divisi/permits/edit.blade.php` — same + editability rules
- `resources/views/divisi/permits/show.blade.php` — show documents
- `resources/views/admin/approvals/show.blade.php` — show documents with preview/download
- `resources/views/divisi/dashboard.blade.php` — badge tipe
- `resources/views/admin/dashboard.blade.php` — badge tipe
- `resources/views/divisi/history.blade.php` — badge + filter
- `resources/views/admin/history/` — badge + filter
- `resources/views/admin/approvals/index.blade.php` — badge + filter
- `app/Http/Controllers/Divisi/PermitShowController.php` — pass documents to view
- `app/Http/Controllers/Admin/ApprovalController.php` — pass documents to view
- `app/Http/Controllers/Divisi/HistoryController.php` — filter by tipe
- `app/Http/Controllers/Admin/HistoryController.php` — filter by tipe

### Files to create

- `database/migrations/xxxx_add_tipe_to_permits_table.php`
- `database/migrations/xxxx_create_permit_documents_table.php`
- `app/Models/PermitDocument.php`
- `resources/views/components/permit-tipe-badge.blade.php`
- `resources/views/divisi/permits/document-upload.blade.php`

### No changes needed

- `routes/web.php` — no new routes
- `app/Http/Controllers/Admin/ApprovalController.php` — only pass documents data, no route changes
- Approval flow logic — unchanged
- Auth system — unchanged
