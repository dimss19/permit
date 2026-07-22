# LAPORAN BUG — SAFETY PERMIT INKA MADIUN

**Total Bug:** 20  
**Critical:** 5 | **High:** 4 | **Medium:** 7 | **Low:** 4

---

## Bug #1 — IDOR — User bisa akses/edit permit milik user lain

**Severity:** Critical  
**Prioritas:** P1  
**Lokasi:** Semua route `/divisi/permits/{id}` dan `/divisi/cancellations/{id}`

**Langkah Reproduksi:**
1. Login sebagai divisi A, buat permit, catat ID (misal: 5)
2. Login sebagai divisi B
3. Buka `/divisi/permits/5/edit` atau `/divisi/permits/5`

**Hasil Saat Ini:**
`PermitController::edit()` dan `PermitShowController::show()` pakai `Auth::id() ?? User::where('role','divisi')->first()->id` — jika Auth gagal (null), fallback ke user divisi pertama di DB, membuka akses semua permit.

**Penyebab:**
`PermitController.php:70` dan `PermitShowController.php:14` — fallback `??` ketika `Auth::id()` null mengambil user pertama, bukan gagal.

**File:**
- `app/Http/Controllers/Divisi/PermitController.php` — baris 70
- `app/Http/Controllers/Divisi/PermitShowController.php` — baris 14
- `app/Http/Controllers/Divisi/CancellationController.php` — baris 17
- `app/Http/Controllers/Divisi/DashboardController.php` — baris 13
- `app/Http/Controllers/Divisi/HistoryController.php` — baris 14

**Solusi:**
Hapus fallback `?? \App\Models\User::where('role', 'divisi')->first()`. Ganti dengan abort(403) atau redirect login jika Auth null. Middleware auth sudah required di route, jadi `Auth::id()` never null — fallback ini dead code sekaligus security hole.

---

## Bug #2 — No. Permit race condition — duplicate mungkin

**Severity:** Critical  
**Prioritas:** P1  
**Lokasi:** `PermitController::store()`

**Langkah Reproduksi:**
1. Dua user click "Submit Permit" bersamaan
2. `Permit::count() + 1` bisa return value sama untuk keduanya
3. DB unique constraint violation → error 500

**Hasil Saat Ini:**
`PermitController.php:31`: `$lastNo = Permit::count() + 1;` tidak atomic.

**File:**
`app/Http/Controllers/Divisi/PermitController.php` — baris 31

**Solusi:**
Gunakan DB transaction + lock, atau generate UUID-based no_permit (`Str::uuid()`), atau gunakan DB sequence/auto-increment.

---

## Bug #3 — Cancel route untuk staff/manager/senior-manager tidak terdaftar di web.php

**Severity:** Critical  
**Prioritas:** P1  
**Lokasi:** Route admin cancel

**Langkah Reproduksi:**
1. Login sebagai staff/manager/senior-manager
2. Coba akses `/admin/approvals/{id}/cancel`

**Hasil Saat Ini:**
`ApprovalController` punya method `showCancelForm()` dan `cancel()` (baris 138, 150) tapi route tidak terdaftar di `web.php`. Halaman cancel tidak bisa diakses.

**Penyebab:**
Route missing di `routes/web.php`.

**File:**
- `routes/web.php`
- `app/Http/Controllers/Admin/ApprovalController.php` — baris 138 dan 150

**Solusi:**
Tambah di `routes/web.php` di dalam group `auth`:
```php
Route::get('/admin/approvals/{id}/cancel', [\App\Http\Controllers\Admin\ApprovalController::class, 'showCancelForm']);
Route::post('/admin/approvals/{id}/cancel', [\App\Http\Controllers\Admin\ApprovalController::class, 'cancel']);
```

---

## Bug #4 — `active_at` field tidak ada di fillable & migration

**Severity:** Critical  
**Prioritas:** P1  
**Lokasi:** `ApprovalController::update()`

**Langkah Reproduksi:**
1. Login sebagai senior-manager
2. Approve permit → status jadi Active
3. Controller set `$updateData['active_at'] = now()`

**Hasil Saat Ini:**
Field `active_at` tidak ada di `$fillable` model Permit, tidak ada di migration manapun. Mass assignment error atau data hilang.

**File:**
`app/Http/Controllers/Admin/ApprovalController.php` — baris 117

**Solusi:**
Hapus `'active_at' => now()` dari update data. Tidak ada kode lain yang membaca `active_at`, jadi field ini tidak diperlukan. Alternatif: buat migration baru + tambahkan ke `$fillable`.

---

## Bug #5 — Submit Permit tanpa tanda tangan — tidak divalidasi server-side

**Severity:** High  
**Prioritas:** P1  
**Lokasi:** `PermitController::store()` dan `update()`

**Langkah Reproduksi:**
1. Buka `/divisi/permits/create`
2. Isi form, skip step 4 (tanda tangan)
3. Bypass JS validation — submit via curl / postman / devtools langsung ke POST `/divisi/permits`

**Hasil Saat Ini:**
JS validasi `changeStep()` mencegah maju ke step 5 tanpa tanda tangan di browser normal. Tapi tidak ada validasi server-side bahwa `tanda_tangan` required saat submit (`action=submit`). User bisa bypass JS.

**File:**
`app/Http/Controllers/Divisi/PermitController.php` — baris 21-25 (store) dan 90-94 (update)

**Solusi:**
Tambah validation rules:
```php
'tanda_tangan' => 'required_if:action,submit|string'
```
atau lakukan pengecekan terpisah:
```php
if ($request->input('action') === 'submit' && !$request->filled('tanda_tangan')) {
    return back()->withErrors(['tanda_tangan' => 'Tanda tangan wajib diisi untuk submit.']);
}
```

---

## Bug #6 — Tidak ada validasi `tanggal_mulai` <= `tanggal_selesai`

**Severity:** High  
**Prioritas:** P1  
**Lokasi:** `PermitController::store()` dan `update()`

**Langkah Reproduksi:**
1. Buka `/divisi/permits/create`
2. Isi Tanggal Mulai: `2026-12-31`, Tanggal Selesai: `2026-01-01`

**Hasil Saat Ini:**
Data tersimpan tanpa error. Tanggal selesai sebelum tanggal mulai tidak valid.

**File:**
`app/Http/Controllers/Divisi/PermitController.php` — baris 21-24 dan 90-93

**Solusi:**
Tambah validasi conditional jika kedua field diisi:
```php
'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai'
```

---

## Bug #7 — `perusahaan` field di controller tapi tidak ada di view

**Severity:** Medium  
**Prioritas:** P2  
**Lokasi:** `PermitController` vs `create.blade.php` / `edit.blade.php`

**Langkah Reproduksi:**
1. Buka form create atau edit
2. Tidak ada input field bernama `perusahaan`

**Hasil Saat Ini:**
Controller `store()` dan `update()` baca `$request->perusahaan` (line 44 dan 104) tapi view tidak punya input `name="perusahaan"`. Data field ini selalu null di DB.

**File:**
- `app/Http/Controllers/Divisi/PermitController.php` — baris 44, 104
- `resources/views/divisi/permits/create.blade.php`
- `resources/views/divisi/permits/edit.blade.php`

**Solusi:**
Hapus `'perusahaan' => $request->perusahaan` dari controller store/update, atau tambahkan input field `perusahaan` di view. Field `kontraktor` di view sudah diberi label "Perusahaan", jadi `perusahaan` redundant.

---

## Bug #8 — Edit permit tidak load existing signature (tanda tangan)

**Severity:** Medium  
**Prioritas:** P3  
**Lokasi:** `edit.blade.php` Step 4

**Langkah Reproduksi:**
1. Buat draft baru, isi tanda tangan di step 4, simpan sebagai draft
2. Edit draft yang sama — canvas signature kosong lagi

**Hasil Saat Ini:**
Hidden input `tanda_tangan` diisi `value="{{ old('tanda_tangan', '') }}"` — selalu string kosong. Field `tanda_tangan` tidak disimpan di tabel `permits` (tidak ada di migration atau model). Hanya disimpan sebagai `approval_signatures` setelah approve oleh staff/manager.

**File:**
`resources/views/divisi/permits/edit.blade.php` — baris 360-361

**Solusi:**
Opsi 1: Tambah field `tanda_tangan_pemohon` (longtext/base64) di migration + model. Opsi 2: Simpan di session saat create draft, restore di edit. Opsi 3 (sederhana): Accept bahwa edit akan reset tanda tangan — tambah informasi ke user.

---

## Bug #9 — Tidak ada limit ukuran data untuk tanda tangan base64

**Severity:** Medium  
**Prioritas:** P2  
**Lokasi:** Semua canvas signature (create, edit, cancel, approval)

**Langkah Reproduksi:**
1. Buka halaman yang ada canvas signature
2. Gambar dengan banyak detail (goresan panjang, klik berkali-kali)
3. `canvas.toDataURL('image/png')` bisa menghasilkan string > 2MB

**Hasil Saat Ini:**
Tidak ada kompresi gambar. Data base64 PNG mentah dikirim via POST. Bisa exceed `post_max_size` PHP atau `upload_max_filesize`, menyebabkan silent failure (error 500 atau redirect tanpa pesan).

**File:**
Semua file dengan canvas signature:
- `resources/views/divisi/permits/create.blade.php`
- `resources/views/divisi/permits/edit.blade.php`
- `resources/views/divisi/cancellations/show.blade.php`
- `resources/views/admin/approvals/show.blade.php`
- `resources/views/admin/approvals/cancel.blade.php`

**Solusi:**
Gunakan `canvas.toDataURL('image/jpeg', 0.5)` untuk kompresi, dan/atau validasi server-side max length (misal: 500KB base64). Tambah error handling di controller.

---

## Bug #10 — History filter "Submitted" tidak match status real

**Severity:** Medium  
**Prioritas:** P3  
**Lokasi:** `divisi/dashboard.blade.php`

**Langkah Reproduksi:**
1. Dashboard Divisi — widget "Submitted" link ke `/divisi/history?status=Submitted`
2. History filter status "Submitted"

**Hasil Saat Ini:**
Submit permit via controller langsung set status `Review Staff`, bukan `Submitted`. Status `Submitted` tidak pernah dipakai di flow. Widget dashboard hitung `whereIn(['Submitted', 'Review Staff', 'Review Manager', 'Review Senior Manager'])` — count mungkin ok, tapi filter history ke `Submitted` selalu return 0.

**File:**
- `app/Http/Controllers/Divisi/PermitController.php` — baris 34
- `app/Http/Controllers/Divisi/DashboardController.php` — baris 18-19
- `resources/views/divisi/dashboard.blade.php` — baris 33

**Solusi:**
Hapus `Submitted` dari flow atau ubah transisi: Draft → Submitted → Review Staff. Saat ini Draft langsung ke Review Staff tanpa melalui Submitted.

---

## Bug #11 — Step 4 canvas signature draw hilang saat resize setelah back-forward

**Severity:** Medium  
**Prioritas:** P3  
**Lokasi:** `edit.blade.php` dan `create.blade.php` JS `changeStep()`

**Langkah Reproduksi:**
1. Di create/edit, maju ke step 4
2. Gambar tanda tangan
3. Klik "Kembali" ke step 1, lalu maju lagi ke step 4

**Hasil Saat Ini:**
`resizeCanvas()` dipanggil saat masuk step 4. `ctx.scale(dpr, dpr)` di canvas yang sudah ada drawing-nya akan merubah context dan menghapus drawing. Ukuran canvas juga berubah. Drawing sebelumnya hilang.

**File:**
`resources/views/divisi/permits/edit.blade.php` — baris 497-499 (resize on step 4)

**Solusi:**
Simpan `ImageData` sebelum resize dan restore setelah resize, atau resize hanya sekali saat init (jangan ulang setiap masuk step 4), atau resize manual di window resize saja.

---

## Bug #12 — Admin sidebar notifikasi bell — N+1 query di layout

**Severity:** Medium  
**Prioritas:** P2  
**Lokasi:** `layouts/app.blade.php`

**Langkah Reproduksi:**
1. Login sebagai staff/manager/senior-manager
2. Buka halaman admin manapun

**Hasil Saat Ini:**
Setiap page load menjalankan 2 query: `Permit::where('status', $expectedStatus)->count()` dan `Permit::with('user')->where('status', $expectedStatus)->take(5)->get()`. Query ini ada di layout (`app.blade.php`), jadi dijalankan di setiap request admin.

**File:**
`resources/views/layouts/app.blade.php` — baris 26-41

**Solusi:**
Cache query selama 60 detik, atau pindahkan logika ke ViewComposer + cache.

---

## Bug #13 — Admin tidak bisa navigasi ke form pembatalan permit

**Severity:** High  
**Prioritas:** P2  
**Lokasi:** Sidebar + missing route (terkait Bug #3)

**Langkah Reproduksi:**
1. Login sebagai staff/manager/senior-manager
2. Cari menu untuk batalkan permit — tidak ada di sidebar

**Hasil Saat Ini:**
Sidebar admin: Dashboard, Review Permit, History. Tidak ada menu "Pembatalan Permit". Route juga missing di web.php (Bug #3).

**File:**
`resources/views/layouts/app.blade.php` — baris 93-107

**Solusi:**
Tambah menu sidebar "Pembatalan Permit" → `/admin/history?status=Active` atau halaman khusus. Fix route (Bug #3) terlebih dahulu.

---

## Bug #14 — Error handling tidak ada di PDF download

**Severity:** Low  
**Prioritas:** P4  
**Lokasi:** `PermitShowController::downloadPdf()`

**Langkah Reproduksi:**
1. Coba download PDF permit
2. Jika library DomPDF error (memory limit, font tidak ada, dll)

**Hasil Saat Ini:**
Error dari `Pdf::loadView()` tidak di-handle. Jika gagal, user lihat error 500 tanpa pesan yang jelas.

**File:**
`app/Http/Controllers/Divisi/PermitShowController.php` — baris 33-35

**Solusi:**
Wrap dengan try-catch dan return error redirect dengan pesan:
```php
try {
    $pdf = Pdf::loadView(...);
    return $pdf->stream(...);
} catch (\Exception $e) {
    return redirect()->back()->with('error', 'Gagal mengunduh PDF. Silakan coba lagi.');
}
```

---

## Bug #15 — `CancellationController::index()` tidak handle `Auth::id()` null

**Severity:** Low  
**Prioritas:** P4  
**Lokasi:** `CancellationController.php`

**Langkah Reproduksi:**
1. Jika session expired saat di halaman cancellation

**Hasil Saat Ini:**
`Permit::byDivisi(Auth::id())` — jika Auth null, `byDivisi(null)` → query `where('user_id', null)` → return kosong. Tidak crash, tapi jika ada user dengan `user_id = null` di DB (tidak mungkin karena FK), hasil salah.

**File:**
`app/Http/Controllers/Divisi/CancellationController.php` — baris 17

**Solusi:**
Konsisten dengan controller lain: gunakan `Auth::id()` langsung tanpa fallback (middleware auth sudah menjamin ada user).

---

## Bug #16 — Component `danger-button` tidak dipakai di UI

**Severity:** Low  
**Prioritas:** P4  
**Lokasi:** `resources/views/components/danger-button.blade.php`

**Hasil Saat Ini:**
File blade component `danger-button` ada tapi tidak direferensikan di view manapun. Dead code.

**File:**
`resources/views/components/danger-button.blade.php`

**Solusi:**
Hapus file jika tidak diperlukan, atau gunakan di halaman cancel.

---

## Bug #17 — Soft delete tidak ada — penghapusan user cascade hapus permit

**Severity:** Medium  
**Prioritas:** P2  
**Lokasi:** Migration permits + UserController

**Langkah Reproduksi:**
1. SuperAdmin hapus user divisi yang punya permit
2. Query dijalankan: `DELETE FROM permits WHERE user_id = ?`

**Hasil Saat Ini:**
Migration `create_permits_table.php` baris 14: `$table->foreignId('user_id')->constrained()->cascadeOnDelete()`. Jika user dihapus, SEMUA permit user tersebut juga terhapus permanen. Data hilang.

**File:**
- `database/migrations/2026_07_12_161036_create_permits_table.php` — baris 14
- `app/Http/Controllers/SuperAdmin/UserController.php` — baris 130-144

**Solusi:**
Ganti `cascadeOnDelete()` jadi `nullOnDelete()`, atau tambah soft delete ke User & Permit. SuperAdmin sebaiknya nonaktifkan user saja (toggle `is_active`), bukan hapus.

---

## Bug #18 — Flow review approval — staff bisa edit permit yang sudah di-review Manager (tidak ada kunci)

**Severity:** Medium  
**Prioritas:** P3  
**Lokasi:** Approval flow

**Langkah Reproduksi:**
1. Staff approve permit → status jadi `Review Manager`
2. Manager approve permit → status jadi `Review Senior Manager`
3. Senior Manager belum approve — status `Review Senior Manager`
4. Staff/Manager bisa akses `/admin/approvals/{id}` dengan filter `Semua Status`

**Hasil Saat Ini:**
Index approvals bisa filter status selain expectedStatus (via opsi "Semua Status"). Staff bisa lihat detail permit yang sudah di-review Manager. Tidak bisa approve lagi (validasi `$permit->status !== $config['expectedStatus']` sudah benar), tapi bisa lihat data.

**File:**
`app/Http/Controllers/Admin/ApprovalController.php` — baris 48-72

**Solusi:**
Validasi tambahan: di `show()`, jika status sudah bukan lagi `expectedStatus` untuk role user, set `canReview = false` (sudah dilakukan di baris 80). Tidak critical karena tidak bisa approve ulang.

---

## Bug #19 — Tombol submit form approval ghost jika user langsung click tombol berkali-kali

**Severity:** Low  
**Prioritas:** P3  
**Lokasi:** `admin/approvals/show.blade.php`

**Langkah Reproduksi:**
1. Login sebagai staff
2. Approve permit
3. Click tombol approve 2-3 kali cepat
4. Form ter-submit berkali-kali, record approval_signatures terduplikasi (permit disetujui multiple times oleh role yang sama)

**Hasil Saat Ini:**
Tidak ada proteksi double-submit di client-side atau server-side.

**File:**
`resources/views/admin/approvals/show.blade.php` — baris 373-380

**Solusi:**
Tambah `disabled` pada button saat submit pertama (JavaScript `e.target.disabled = true`), atau tambah unique constraint di DB untuk `(permit_id, role)` di `approval_signatures` (lebih kompleks karena JSON).

---

## Bug #20 — Missing `enctype="multipart/form-data"` di form create/edit

**Severity:** Low  
**Prioritas:** P4  
**Lokasi:** `create.blade.php` dan `edit.blade.php`

**Hasil Saat Ini:**
Form tidak punya `enctype="multipart/form-data"`. Saat ini tidak ada upload file jadi tidak masalah, tapi jika di masa depan upload file ditambahkan, ini akan gagal.

**File:**
- `resources/views/divisi/permits/create.blade.php` — baris 39
- `resources/views/divisi/permits/edit.blade.php` — baris 39

**Solusi:**
Tidak perlu diperbaiki sekarang (YAGNI). Catatan untuk pengembangan selanjutnya.

---

## Ringkasan Perbaikan Prioritas

| Prioritas | Bug | Dampak |
|-----------|-----|--------|
| **P1** | #1 IDOR fallback Auth | Security — akses data antar user |
| **P1** | #3 Missing route cancel admin | Feature broken — admin tidak bisa cancel |
| **P1** | #4 active_at ghost field | Error 500 saat Senior Manager approve |
| **P1** | #2 Race condition no_permit | Error 500 saat concurrent submit |
| **P1** | #5 Server-side TTD validation | Bypass JS, submit tanpa tanda tangan |
| **P1** | #6 Tanggal validasi | Data integrity — tanggal tidak logis |
| **P2** | #7 Field perusahaan orphan | Data selalu null |
| **P2** | #9 Limit upload TTD base64 | Silent failure data loss |
| **P2** | #13 Menu cancel admin hilang | UX broken |
| **P2** | #17 Cascade delete user | Data hilang permanen |
| **P3** | #8 Edit signature tidak restore | UX frustrasi |
| **P3** | #10 Status Submitted orphan | UX membingungkan |
| **P3** | #11 Canvas hilang saat resize | UX frustrasi |
| **P3** | #18 Staff bisa lihat permit role lain | Minor info disclosure |
| **P3** | #19 Double submit approval | Duplicate data |
| **P4** | Sisa bug low priority | Minor |