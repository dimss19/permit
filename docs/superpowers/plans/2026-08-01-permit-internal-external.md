# Permit Internal / Eksternal — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add permit type (Internal/External) with document upload for external permits, visible across all role dashboards and tables.

**Architecture:** Single form with conditional wizard steps. New `tipe` column on permits, new `permit_documents` table for file storage. One Blade component for badge reuse.

**Tech Stack:** Laravel 13, PHP 8.3, MySQL, Blade + Tailwind CSS v3 + Alpine.js, Vite

## Global Constraints

- UI text is Indonesian
- Tailwind theme: `inka-navy`, `inka-light-gray`, `inka-text-muted`, `inka-border`, `accent-orange`
- Font: Inter
- Design: minimal/Apple-like, card-based, rounded-2xl, border-gray-100
- Tests run on sqlite `:memory:` while dev runs MySQL
- Login by username, not email

---

### Task 1: Database Migration — `tipe` column + `permit_documents` table

**Files:**
- Create: `database/migrations/2026_08_01_000001_add_tipe_to_permits_table.php`
- Create: `database/migrations/2026_08_01_000002_create_permit_documents_table.php`

**Step 1: Create migration for `tipe` column**

```php
<?php
// database/migrations/2026_08_01_000001_add_tipe_to_permits_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->enum('tipe', ['Internal', 'Eksternal'])->default('Internal')->after('no_permit');
        });
    }

    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
```

**Step 2: Create migration for `permit_documents` table**

```php
<?php
// database/migrations/2026_08_01_000002_create_permit_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permit_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permit_id')->constrained()->cascadeOnDelete();
            $table->string('nama_dokumen');
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permit_documents');
    }
};
```

**Step 3: Run migration**

Run: `php artisan migrate`
Expected: Migrations complete without error

**Step 4: Commit**

```bash
git add database/migrations/2026_08_01_000001_add_tipe_to_permits_table.php database/migrations/2026_08_01_000002_create_permit_documents_table.php
git commit -m "feat: add tipe column to permits and create permit_documents table"
```

---

### Task 2: Models — PermitDocument + update Permit

**Files:**
- Create: `app/Models/PermitDocument.php`
- Modify: `app/Models/Permit.php`

**Step 1: Create PermitDocument model**

```php
<?php
// app/Models/PermitDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitDocument extends Model
{
    protected $fillable = [
        'permit_id',
        'nama_dokumen',
        'deskripsi',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function permit()
    {
        return $this->belongsTo(Permit::class);
    }
}
```

**Step 2: Update Permit model — add `tipe` to fillable/casts, add `documents()` relationship**

```php
// app/Models/Permit.php — additions
// Add to $fillable array:
'tipe',

// Add to $casts array:
'tipe' => 'string',

// Add relationship:
public function documents()
{
    return $this->hasMany(PermitDocument::class);
}
```

**Step 3: Commit**

```bash
git add app/Models/PermitDocument.php app/Models/Permit.php
git commit -m "feat: add PermitDocument model and tipe relationship to Permit"
```

---

### Task 3: PermitController — handle tipe + document upload in store/update

**Files:**
- Modify: `app/Http/Controllers/Divisi/PermitController.php`

**Step 1: Update `store()` method**

Add to validation rules:
```php
'tipe' => 'required|in:Internal,Eksternal',
'dokumen' => 'required_if:tipe,Eksternal|array',
'dokumen.*.nama' => 'required|string|max:255',
'dokumen.*.deskripsi' => 'nullable|string|max:500',
'dokumen.*.file' => 'required|file|max:10240', // 10MB
```

After `Permit::create()`, if `tipe === 'Eksternal'` and `dokumen` provided:
```php
if ($request->input('tipe') === 'Eksternal' && $request->hasFile('dokumen')) {
    foreach ($request->file('dokumen') as $idx => $doc) {
        $file = $doc['file'];
        $ext = $file->getClientOriginalExtension();
        $filename = time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $path = $file->storeAs('permits/' . $permit->id, $filename);

        PermitDocument::create([
            'permit_id'   => $permit->id,
            'nama_dokumen' => $doc['nama'],
            'deskripsi'    => $doc['deskripsi'] ?? null,
            'file_path'    => $path,
            'file_type'    => $ext,
            'file_size'    => $file->getSize(),
        ]);
    }
}
```

**Step 2: Update `update()` method**

Same pattern — add `tipe` validation, handle document CRUD:
- If tipe changed to Eksternal: process new documents from request
- If tipe changed to Internal: delete existing documents + files
- If tipe stays Eksternal: allow adding/removing documents

**Step 3: Add import**

Add at top of file:
```php
use App\Models\PermitDocument;
use Illuminate\Support\Facades\Storage;
```

**Step 4: Commit**

```bash
git add app/Http/Controllers/Divisi/PermitController.php
git commit -m "feat: handle tipe and document upload in PermitController store/update"
```

---

### Task 4: Create permit-tipe-badge Blade component

**Files:**
- Create: `resources/views/components/permit-tipe-badge.blade.php`

**Step 1: Create component**

```blade
{{-- resources/views/components/permit-tipe-badge.blade.php --}}
@props(['tipe' => 'Internal'])

@if($tipe === 'Eksternal')
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
        Eksternal
    </span>
@else
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
        Internal
    </span>
@endif
```

**Step 2: Commit**

```bash
git add resources/views/components/permit-tipe-badge.blade.php
git commit -m "feat: add permit-tipe-badge Blade component"
```

---

### Task 5: Wizard Step 0 — Type Selection (create.blade.php)

**Files:**
- Modify: `resources/views/divisi/permits/create.blade.php`

**Step 1: Add Step 0 to step indicator**

Update the `$steps` array and totalSteps to include Step 0:
```php
$steps = [
    0 => 'Tipe Permit',
    1 => 'Klasifikasi & Info',
    2 => 'Bahaya & Pencegahan',
    3 => 'APD',
    4 => 'Validasi Kerja',
    5 => 'Review & Submit',
];
```
Update `totalSteps = 6` in JS. Adjust step indicator to render 6 steps.

**Step 2: Add Step 0 content (before step-1 div)**

```blade
{{-- ========================================================
     STEP 0 — PILIH TIPE PERMIT
     ======================================================== --}}
<div id="step-0" class="space-y-5">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">Pilih Tipe Permit</h3>
            <p class="text-sm text-gray-400 mt-0.5">Tentukan apakah permit ini untuk pekerjaan internal atau eksternal</p>
        </div>
        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <button type="button" onclick="selectTipe('Internal')" id="btn-internal"
                class="tipe-btn group p-6 rounded-2xl border-2 border-gray-200 hover:border-inka-navy text-left transition-all">
                <div class="w-12 h-12 rounded-xl bg-gray-100 group-hover:bg-inka-navy/10 flex items-center justify-center mb-3 transition-colors">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-inka-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-800">Internal</p>
                <p class="text-sm text-gray-400 mt-1">Pekerjaan internal perusahaan</p>
            </button>
            <button type="button" onclick="selectTipe('Eksternal')" id="btn-eksternal"
                class="tipe-btn group p-6 rounded-2xl border-2 border-gray-200 hover:border-inka-navy text-left transition-all">
                <div class="w-12 h-12 rounded-xl bg-gray-100 group-hover:bg-blue-50 flex items-center justify-center mb-3 transition-colors">
                    <svg class="w-6 h-6 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-800">Eksternal</p>
                <p class="text-sm text-gray-400 mt-1">Pekerjaan oleh kontraktor eksternal</p>
            </button>
        </div>
        <input type="hidden" name="tipe" id="tipe-input" value="{{ old('tipe', 'Internal') }}">
    </div>
</div>
```

**Step 3: Add Step 1 — Document Upload (conditional, only for Eksternal)**

Insert between Step 0 and current Step 1 (renamed to Step 2):

```blade
{{-- ========================================================
     STEP 1 — DOKUMEN PENDUKUNG (Eksternal Only)
     ======================================================== --}}
<div id="step-1" class="hidden space-y-5">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">D</span>
            <h3 class="text-base font-semibold text-gray-800 mt-0.5">Dokumen Pendukung</h3>
            <p class="text-sm text-gray-400 mt-0.5">Upload dokumen pendukung untuk permit eksternal (max 10MB per file)</p>
        </div>
        <div class="px-6 py-5">
            <div id="documents-list" class="space-y-4">
                {{-- Document entries will be added here by JS --}}
            </div>
            <button type="button" onclick="addDocument()" id="btn-add-doc"
                class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-inka-navy border border-inka-navy rounded-xl hover:bg-inka-navy/5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Dokumen
            </button>
            <p id="doc-required-warning" class="text-sm text-red-500 mt-3 hidden">Minimal upload 1 dokumen pendukung.</p>
        </div>
    </div>
</div>
```

**Step 4: Update JS — selectTipe function, document management, step routing**

```javascript
// Add after let currentStep = 0; const totalSteps = 6;

let selectedTipe = 'Internal';

function selectTipe(tipe) {
    selectedTipe = tipe;
    document.getElementById('tipe-input').value = tipe;

    // Update button styles
    document.querySelectorAll('.tipe-btn').forEach(btn => {
        btn.classList.remove('border-inka-navy', 'bg-inka-navy/5');
        btn.classList.add('border-gray-200');
    });
    const activeBtn = document.getElementById('btn-' + tipe.toLowerCase());
    activeBtn.classList.add('border-inka-navy', 'bg-inka-navy/5');
    activeBtn.classList.remove('border-gray-200');
}

// Initialize default
selectTipe('Internal');

// In changeStep(), after hiding current step and before showing next:
// If going forward from step 0 and tipe is Internal, skip step 1
// If going forward from step 0 and tipe is Eksternal, go to step 1
function getNextStep(current, direction) {
    let next = current + direction;
    if (direction > 0 && current === 0 && selectedTipe === 'Internal') {
        next = 2; // Skip document upload step
    }
    if (direction < 0 && current === 2 && selectedTipe === 'Internal') {
        next = 0; // Skip back to step 0
    }
    return next;
}

// Document management JS
let docCount = 0;
function addDocument() {
    const list = document.getElementById('documents-list');
    const idx = docCount;
    const html = `
        <div class="doc-entry p-4 border border-gray-100 rounded-xl space-y-3" id="doc-${idx}">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-gray-600">Dokumen ${idx + 1}</p>
                <button type="button" onclick="removeDocument(${idx})" class="text-sm text-red-500 hover:underline">Hapus</button>
            </div>
            <div>
                <label class="form-label">Nama Dokumen <span class="text-red-500">*</span></label>
                <input type="text" name="dokumen[${idx}][nama]" class="form-input" placeholder="Contoh: HIRADC" required>
            </div>
            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="dokumen[${idx}][deskripsi]" class="form-input" rows="2" placeholder="Deskripsi singkat dokumen..."></textarea>
            </div>
            <div>
                <label class="form-label">File <span class="text-red-500">*</span></label>
                <input type="file" name="dokumen[${idx}][file]" class="form-input" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv">
                <p class="text-xs text-gray-400 mt-1">Maks 10MB. Format: PDF, gambar, Office, dll.</p>
            </div>
        </div>
    `;
    list.insertAdjacentHTML('beforeend', html);
    docCount++;
    updateDocWarning();
}

function removeDocument(idx) {
    const el = document.getElementById('doc-' + idx);
    if (el) el.remove();
    updateDocWarning();
}

function updateDocWarning() {
    const docs = document.querySelectorAll('.doc-entry');
    const warning = document.getElementById('doc-required-warning');
    if (docs.length === 0) {
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
}
```

**Step 5: Update `changeStep()` to handle new step flow**

Update step validation: when leaving step 1 (documents), check at least 1 document exists if tipe is Eksternal.

**Step 6: Commit**

```bash
git add resources/views/divisi/permits/create.blade.php
git commit -m "feat: add Step 0 type selection and Step 1 document upload to create wizard"
```

---

### Task 6: Edit form — tipe selector + document management

**Files:**
- Modify: `resources/views/divisi/permits/edit.blade.php`

**Step 1: Add Step 0 (tipe selector) — same as create, but pre-selected based on `$permit->tipe`**

Key difference: if `$permit->status` is not Draft/Revision, show tipe as read-only text instead of buttons.

```blade
<div id="step-0" class="space-y-5">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">Tipe Permit</h3>
        </div>
        <div class="px-6 py-5">
            @if(in_array($permit->status, ['Draft', 'Revision']))
                {{-- Editable buttons --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button type="button" onclick="selectTipe('Internal')" id="btn-internal"
                        class="tipe-btn group p-6 rounded-2xl border-2 {{ $permit->tipe === 'Internal' ? 'border-inka-navy bg-inka-navy/5' : 'border-gray-200' }} text-left transition-all">
                        {{-- Same content as create --}}
                    </button>
                    <button type="button" onclick="selectTipe('Eksternal')" id="btn-eksternal"
                        class="tipe-btn group p-6 rounded-2xl border-2 {{ $permit->tipe === 'Eksternal' ? 'border-inka-navy bg-inka-navy/5' : 'border-gray-200' }} text-left transition-all">
                        {{-- Same content as create --}}
                    </button>
                </div>
                <input type="hidden" name="tipe" id="tipe-input" value="{{ $permit->tipe }}">
            @else
                {{-- Read-only display --}}
                <div class="flex items-center gap-3">
                    <x-permit-tipe-badge :tipe="$permit->tipe" />
                    <span class="text-sm text-gray-400">Tipe tidak dapat diubah setelah submit.</span>
                </div>
                <input type="hidden" name="tipe" value="{{ $permit->tipe }}">
            @endif
        </div>
    </div>
</div>
```

**Step 2: Add Step 1 (document upload) — with existing documents pre-loaded**

Show existing documents from `$permit->documents` with delete buttons. New documents can be added.

**Step 3: Update JS — same as create, plus document delete handling**

**Step 4: Handle tipe switch logic in JS**

When user switches from Eksternal to Internal: show confirmation, then remove all document entries and delete existing documents via AJAX or form submission.

**Step 5: Commit**

```bash
git add resources/views/divisi/permits/edit.blade.php
git commit -m "feat: add tipe selector and document management to edit wizard"
```

---

### Task 7: Update all dashboard views — badge tipe

**Files:**
- Modify: `resources/views/divisi/dashboard.blade.php` (permit terbaru table)
- Modify: `resources/views/admin/dashboard.blade.php` (permit table)
- Modify: `resources/views/admin/approvals/index.blade.php` (approval table)

**Step 1: Add `<x-permit-tipe-badge :tipe="$permit->tipe" />` column in each table**

In the "Permit Terbaru" table section, add a Tipe column after the status column:

```blade
<td class="px-4 py-3">
    <x-permit-tipe-badge :tipe="$permit->tipe" />
</td>
```

**Step 2: Add table header for Tipe column**

```blade
<th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Tipe</th>
```

**Step 3: Update controllers to eager-load `documents` relationship where needed**

In Divisi DashboardController, Admin DashboardController, and ApprovalController index: add `->with('documents')` or at minimum ensure `tipe` is accessible (it's a column, so it's always available).

**Step 4: Commit**

```bash
git add resources/views/divisi/dashboard.blade.php resources/views/admin/dashboard.blade.php resources/views/admin/approvals/index.blade.php
git commit -m "feat: add tipe badge to all dashboard and approval tables"
```

---

### Task 8: Update show/detail views — document display

**Files:**
- Modify: `resources/views/divisi/permits/show.blade.php`
- Modify: `resources/views/admin/approvals/show.blade.php`
- Modify: `app/Http/Controllers/Divisi/PermitShowController.php`
- Modify: `app/Http/Controllers/Admin\ApprovalController.php` (show method)

**Step 1: Update controllers to pass documents**

PermitShowController::show():
```php
$permit = Permit::with('documents')->where('user_id', Auth::id())->where('id', $id)->firstOrFail();
```

ApprovalController::show():
```php
$permit = Permit::with('documents')->findOrFail($id);
```

**Step 2: Add document section to divisi show.blade.php**

After existing detail sections, before the closing tag:
```blade
{{-- Dokumen Pendukung --}}
@if($permit->tipe === 'Eksternal' && $permit->documents->count())
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="px-6 py-4 border-b border-gray-100">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">G</span>
        <h3 class="text-sm font-semibold text-gray-800 mt-0.5">Dokumen Pendukung</h3>
    </div>
    <div class="px-6 py-4 space-y-3">
        @foreach($permit->documents as $doc)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">{{ $doc->nama_dokumen }}</p>
                    <p class="text-xs text-gray-400">{{ strtoupper($doc->file_type) }} • {{ round($doc->file_size / 1024) }} KB</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                   class="text-sm font-semibold text-inka-navy hover:underline">Preview</a>
                <a href="{{ Storage::url($doc->file_path) }}" download
                   class="text-sm font-semibold text-gray-500 hover:underline">Download</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
```

**Step 3: Add same document section to admin approvals show.blade.php**

Same pattern, placed after the existing detail grid and before the approval action buttons.

**Step 4: Add import to controllers**

```php
use Illuminate\Support\Facades\Storage;
```

**Step 5: Commit**

```bash
git add resources/views/divisi/permits/show.blade.php resources/views/admin/approvals/show.blade.php app/Http/Controllers/Divisi/PermitShowController.php app/Http/Controllers/Admin/ApprovalController.php
git commit -m "feat: display permit documents in detail and approval views"
```

---

### Task 9: Update history views — badge + tipe filter

**Files:**
- Modify: `resources/views/divisi/history.blade.php`
- Modify: `resources/views/admin/history/` (all files)
- Modify: `app/Http/Controllers/Divisi/HistoryController.php`
- Modify: `app/Http/Controllers/Admin/HistoryController.php`

**Step 1: Add tipe filter to controllers**

In both HistoryController::index(), add:
```php
if ($request->filled('tipe') && $request->tipe !== 'Semua') {
    $query->where('tipe', $request->tipe);
}
```

**Step 2: Add filter dropdown to history views**

After existing status filter, add:
```blade
<div class="flex items-center gap-2">
    <label class="text-sm font-semibold text-gray-500">Tipe:</label>
    <select onchange="window.location.href=updateParam('tipe', this.value)"
        class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-inka-navy">
        <option value="Semua" {{ request('tipe', 'Semua') === 'Semua' ? 'selected' : '' }}>Semua</option>
        <option value="Internal" {{ request('tipe') === 'Internal' ? 'selected' : '' }}>Internal</option>
        <option value="Eksternal" {{ request('tipe') === 'Eksternal' ? 'selected' : '' }}>Eksternal</option>
    </select>
</div>
```

**Step 3: Add tipe badge column to history tables**

Same as Task 7 — add `<x-permit-tipe-badge :tipe="$permit->tipe" />` in table rows.

**Step 4: Commit**

```bash
git add resources/views/divisi/history.blade.php resources/views/admin/history/ app/Http/Controllers/Divisi/HistoryController.php app/Http/Controllers/Admin/HistoryController.php
git commit -m "feat: add tipe filter and badge to history views"
```

---

### Task 10: Bug fixes — tanda tangan + other known bugs

**Files:**
- Modify: `resources/views/divisi/permits/create.blade.php` (signature canvas)
- Modify: `resources/views/divisi/permits/edit.blade.php` (signature canvas)
- Modify: `app/Http/Controllers/Divisi/PermitController.php`

**Step 1: Fix signature (tanda tangan) — ensure background is transparent/white**

The canvas signature currently saves as JPEG with white background. The issue is the canvas context may have incorrect default background. In the `resizeCanvas()` function, after scaling, explicitly set white background:

```javascript
function resizeCanvas() {
    const rect = container.getBoundingClientRect();
    if (rect.width === 0) return;
    const dpr = window.devicePixelRatio || 1;
    canvas.width  = rect.width  * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
    // Set white background
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, rect.width, rect.height);
    // Set drawing style
    ctx.strokeStyle = '#111d33';
    ctx.lineWidth   = 2;
    ctx.lineCap     = 'round';
    ctx.lineJoin    = 'round';
}
```

Also change `toDataURL` from JPEG to PNG for better quality:
```javascript
hiddenInput.value = canvas.toDataURL('image/png');
```

**Step 2: Fix clearSignature to also reset background**

```javascript
window.clearSignature = function () {
    const dpr = window.devicePixelRatio || 1;
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, canvas.width / dpr, canvas.height / dpr);
    hasDrawn = false;
    hiddenInput.value = '';
    placeholder.classList.remove('hidden');
    container.classList.add('border-dashed', 'border-gray-300');
    container.classList.remove('border-solid', 'border-inka-navy/30');
};
```

**Step 3: Add server-side validation for tanda_tangan on submit (Bug #5)**

In PermitController::store() and update(), ensure signature is validated when action is submit (already present in current code — verify it's still there after modifications).

**Step 4: Commit**

```bash
git add resources/views/divisi/permits/create.blade.php resources/views/divisi/permits/edit.blade.php
git commit -m "fix: resolve tanda tangan black/transparent background issue"
```

---

### Task 11: Build & verify

**Step 1: Run migration**

```bash
php artisan migrate:fresh --seed
```

**Step 2: Build assets**

```bash
npm run build
```

**Step 3: Start dev server and manually test**

```bash
php artisan serve
```

Test checklist:
- [ ] Create new Internal permit — no document upload step
- [ ] Create new External permit — document upload step appears
- [ ] Upload documents — files stored in storage/app/permits/{id}/
- [ ] Submit external permit — documents visible in approval view
- [ ] Edit draft permit — can switch tipe
- [ ] Approval view — documents with preview/download
- [ ] History — tipe badge and filter work
- [ ] Dashboard — tipe badge visible
- [ ] Signature canvas — white background, not black

**Step 4: Commit**

```bash
git add -A
git commit -m "feat: complete permit internal/external feature with bug fixes"
```
