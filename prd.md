# Work Permit Management System

**Product Requirements Document (PRD)**

| | |
|---|---|
| **Document Type** | Product Requirements Document (PRD) |
| **Version** | 1.0 (Final) |
| **Status** | Approved |
| **Document Owner** | Product Manager |
| **Prepared By** | - |
| **Reviewed By** | - |
| **Approved By** | - |
| **Last Updated** | xx xxxx 2026 |

---

## Table of Contents

1. [Revision History](#revision-history)
2. [Executive Summary](#1-executive-summary)
3. [Background](#2-background)
4. [Problem Statement](#3-problem-statement)
5. [Objectives](#4-objectives)
6. [Scope](#5-scope)
7. [Stakeholder](#6-stakeholder)
8. [User Roles](#7-user-roles)
9. [Business Rules](#8-business-rules)
10. [Business Process](#9-business-process)
11. [Permit Lifecycle](#10-permit-lifecycle)
12. [Functional Requirements](#11-functional-requirements)
13. [Dashboard Requirements](#12-dashboard-requirements)
14. [Landing Page Requirements](#13-landing-page-requirements)
15. [User Management](#14-user-management)
16. [Email Notification](#15-email-notification)
17. [History](#16-history)
18. [UI & UX Requirements](#17-ui--ux-requirements)
19. [Global Layout](#18-global-layout)
20. [Landing Page](#19-landing-page)
21. [Dashboard](#20-dashboard)
22. [Halaman Pengajuan Permit](#21-halaman-pengajuan-permit)
23. [Halaman Approval](#22-halaman-approval)
24. [Halaman History](#23-halaman-history)
25. [User Management](#24-user-management)
26. [Profile](#25-profile)
27. [UI Components](#26-ui-components)
28. [Design Guidelines](#27-design-guidelines)
29. [UX Guidelines](#28-ux-guidelines)
30. [Validation Rules](#29-validation-rules)
31. [Permit Status](#30-permit-status)
32. [Non Functional Requirements](#31-non-functional-requirements)
33. [Acceptance Criteria](#32-acceptance-criteria)
34. [Access Matrix](#33-access-matrix)
35. [Error Handling](#34-error-handling)
36. [Future Enhancement](#35-future-enhancement)
37. [Design System](#36-design-system)
38. [AI UI Design Guidelines](#37-ai-ui-design-guidelines)
39. [Kesimpulan](#38-kesimpulan)
40. [UI Page Inventory](#ui-page-inventory)

---

## Revision History

| Version | Date | Author | Description |
|----------|------|--------|-------------|
| 1.0 | xx xxxx 2026 | - | Initial Draft |
| 1.0 Final | xx xxxx 2026 | - | Final version - removed profile customization |

---

## 1. Executive Summary

Work Permit Management System merupakan aplikasi berbasis web yang dirancang untuk mendigitalisasi proses pengajuan izin kerja (Work Permit) di lingkungan perusahaan.

Sistem ini menggantikan proses manual menggunakan formulir kertas menjadi proses digital yang lebih cepat, terdokumentasi, mudah dipantau, serta mempermudah proses persetujuan antar pihak yang terlibat.

Sistem menggunakan alur persetujuan (approval) yang bersifat berjenjang, dimulai dari Staff, kemudian Manager, hingga Senior Manager sebelum izin kerja dinyatakan aktif.

Aplikasi ini dikembangkan dengan pendekatan yang sederhana, mudah digunakan, dan sesuai bagi pengguna non-teknis.

---

## 2. Background

Saat ini proses pengajuan Work Permit masih menggunakan formulir fisik yang harus diisi secara manual.

Proses tersebut memiliki beberapa kendala, antara lain:

- Pengisian formulir membutuhkan waktu lebih lama.
- Dokumen fisik berpotensi hilang atau rusak.
- Sulit melakukan pencarian data lama.
- Status pengajuan tidak dapat dipantau secara real-time.
- Approval masih dilakukan secara manual.
- Riwayat pengajuan tidak terdokumentasi secara terpusat.

Untuk mengatasi permasalahan tersebut diperlukan sebuah sistem berbasis web yang mampu mendigitalisasi seluruh proses pengajuan hingga persetujuan Work Permit.

---

## 3. Problem Statement

Beberapa permasalahan utama yang ingin diselesaikan melalui sistem ini antara lain:

- Proses pengajuan masih menggunakan formulir kertas.
- Proses approval membutuhkan waktu cukup lama.
- Sulit mengetahui status permit yang sedang diproses.
- Penyimpanan dokumen belum terpusat.
- Riwayat permit sulit dicari kembali.
- Tidak tersedia sistem monitoring permit secara digital.

---

## 4. Objectives

Tujuan utama pengembangan sistem ini adalah:

- Mendigitalisasi proses pengajuan Work Permit.
- Mempermudah proses approval secara berjenjang.
- Mempercepat proses administrasi perizinan.
- Menyediakan penyimpanan dokumen secara digital.
- Memudahkan pencarian riwayat permit.
- Menyediakan dashboard monitoring sesuai hak akses pengguna.

---

## 5. Scope

### In Scope

Sistem yang akan dikembangkan meliputi modul berikut:

#### Landing Page

Halaman awal sistem yang berisi informasi singkat mengenai aplikasi serta akses menuju halaman login.

---

#### Dashboard

Dashboard yang berbeda untuk setiap role sesuai kebutuhan pengguna.

---

#### Pengajuan Permit

Divisi dapat membuat pengajuan Work Permit secara digital.

---

#### Approval

Approval dilakukan secara bertahap oleh:

- Staff
- Manager
- Senior Manager

---

#### History

Menyimpan seluruh riwayat permit yang telah diproses.

---

#### User Management

Pengelolaan akun Divisi oleh Super Admin.

---

#### Email Notification

Sistem mengirim email notifikasi kepada Staff ketika terdapat permit baru yang diajukan.

---

### Out of Scope

Fitur berikut tidak termasuk dalam pengembangan versi MVP:

- Mobile Application
- WhatsApp Notification
- QR Code Validation
- Integrasi ERP
- Integrasi HRIS
- Multi Company
- Multi Plant
- Digital Signature tersertifikasi
- Dashboard Analytics lanjutan

---

## 6. Stakeholder

| Stakeholder | Peran |
|-------------|-------|
| Super Admin | Mengelola akun pengguna |
| Divisi | Mengajukan Work Permit |
| Kontraktor | Mengisi permit menggunakan akun Divisi |
| Staff | Melakukan review dan approval tahap pertama |
| Manager | Melakukan approval tahap kedua |
| Senior Manager | Melakukan approval akhir |

---

## 7. User Roles

### Super Admin

Tanggung jawab:

- Membuat akun Divisi.
- Mengaktifkan atau menonaktifkan akun.
- Reset Password.

Super Admin tidak terlibat dalam proses approval maupun pembuatan permit.

---

### Divisi

Divisi bertanggung jawab sebagai pemilik permit.

Kontraktor menggunakan akun Divisi untuk melakukan pengajuan.

Hak akses:

- Dashboard
- Membuat Permit
- Edit Draft
- Upload Dokumen
- Submit Permit
- History Permit
- Closing Permit

---

### Staff

Merupakan approver pertama.

Hak akses:

- Dashboard
- Review Permit
- Approve
- Revisi
- History

Sistem akan mengirim email ketika terdapat permit baru yang menunggu review.

---

### Manager

Merupakan approver kedua.

Hak akses:

- Dashboard
- Review Permit
- Approve
- Revisi
- History

---

### Senior Manager

Merupakan approver terakhir.

Hak akses:

- Dashboard
- Review Permit
- Approve
- Revisi
- History

---

## 8. Business Rules

Sistem menggunakan aturan bisnis sebagai berikut.

### BR-001
Permit hanya dapat dibuat oleh Divisi.

---

### BR-002
Kontraktor tidak memiliki akun sendiri dan menggunakan akun Divisi.

---

### BR-003
Approval dilakukan secara berurutan:

Divisi → Staff → Manager → Senior Manager.

---

### BR-004
Permit yang direvisi akan dikembalikan kepada Divisi.

---

### BR-005
Permit hanya dapat berstatus Active apabila seluruh tahapan approval telah disetujui.

---

### BR-006
Permit yang telah di-submit tidak dapat diedit, kecuali berstatus Revision.

---

### BR-007
History permit tidak dapat diubah maupun dihapus.

---

### BR-008
Super Admin tidak memiliki hak melakukan approval.

---

### BR-009
Email notifikasi hanya dikirim kepada Staff ketika terdapat permit baru.

---

## 9. Business Process

### Alur Pengajuan Permit

1. Super Admin membuat akun Divisi.
2. Kontraktor menggunakan akun Divisi.
3. Divisi membuat Work Permit.
4. Sistem menyimpan permit sebagai Draft.
5. Divisi melengkapi seluruh data.
6. Divisi mengunggah dokumen pendukung.
7. PIC melakukan tanda tangan digital.
8. Divisi melakukan Submit.
9. Sistem mengirim email kepada Staff.
10. Staff melakukan Review.
    - Approve → Manager
    - Revisi → Divisi
11. Manager melakukan Review.
    - Approve → Senior Manager
    - Revisi → Divisi
12. Senior Manager melakukan Review.
    - Approve → Permit Active
    - Revisi → Divisi
13. Setelah pekerjaan selesai, Divisi melakukan Closing Permit.
14. Permit berpindah ke History.

---

## 10. Permit Lifecycle
Draft
│
▼
Submitted
│
▼
Review Staff
│
▼
Review Manager
│
▼
Review Senior Manager
│
▼
Active
│
▼
Closed

Apabila permit direvisi pada salah satu tahapan approval, status permit berubah menjadi **Revision** dan kembali kepada Divisi untuk dilakukan perbaikan sebelum diajukan kembali.

---

## 11. Functional Requirements

### FR-001 Login

#### Deskripsi
Sistem menyediakan halaman login sesuai role pengguna.

#### Actor
- Super Admin
- Divisi
- Staff
- Manager
- Senior Manager

#### Acceptance Criteria
- Pengguna dapat login menggunakan username dan password.
- Sistem mengarahkan pengguna ke dashboard sesuai role.
- Sistem menampilkan pesan apabila login gagal.

---

### FR-002 Dashboard

#### Deskripsi
Setiap role memiliki dashboard yang berbeda sesuai kebutuhan.

#### Acceptance Criteria
- Informasi ditampilkan berdasarkan role.
- Dashboard menampilkan ringkasan data.
- Dashboard menyediakan Quick Action menuju menu utama.

---

### FR-003 Create Permit

#### Deskripsi
Divisi dapat membuat pengajuan Work Permit baru.

#### Acceptance Criteria
- Permit dapat disimpan sebagai Draft.
- Permit dapat diedit selama masih Draft.
- Permit dapat di-submit setelah seluruh data lengkap.

---

### FR-004 Upload Dokumen

#### Deskripsi
Divisi dapat mengunggah dokumen pendukung.

#### Acceptance Criteria
- Sistem menerima file PDF dan gambar.
- File dapat di-preview.
- File dapat dihapus sebelum submit.

---

### FR-005 Submit Permit

#### Deskripsi
Divisi mengirim permit ke proses approval.

#### Acceptance Criteria
- Sistem melakukan validasi data.
- Status berubah menjadi Submitted.
- Email dikirim kepada Staff.

---

### FR-006 Review Permit

#### Deskripsi
Staff, Manager, dan Senior Manager dapat melihat detail permit.

#### Acceptance Criteria
- Seluruh informasi permit dapat dilihat.
- Dokumen dapat dibuka.
- Riwayat approval dapat dilihat.

---

### FR-007 Approval

#### Deskripsi
Approver dapat menyetujui permit.

#### Acceptance Criteria
- Approval mengikuti urutan.
- Status berubah sesuai tahapan.
- Riwayat approval tersimpan.

---

### FR-008 Revision

#### Deskripsi
Approver dapat meminta revisi.

#### Acceptance Criteria
- Approver wajib memberikan catatan revisi.
- Status berubah menjadi Revision.
- Permit kembali ke Divisi.

---

### FR-009 Digital Signature

#### Deskripsi
Permit menggunakan tanda tangan digital.

#### Acceptance Criteria
- PIC melakukan tanda tangan sebelum submit.
- Staff, Manager dan Senior Manager melakukan tanda tangan saat approval.

---

### FR-010 History

#### Deskripsi
Sistem menyimpan seluruh riwayat permit.

#### Acceptance Criteria
- Data dapat dicari.
- Data dapat difilter.
- Data hanya dapat dibaca.

---

### FR-011 Closing Permit

#### Deskripsi
Divisi menutup permit setelah pekerjaan selesai.

#### Acceptance Criteria
- Permit hanya dapat ditutup jika status Active.
- Status berubah menjadi Closed.
- Permit masuk ke History.

---

### FR-012 User Management

#### Deskripsi
Super Admin mengelola akun Divisi.

#### Acceptance Criteria
- Membuat akun.
- Reset Password.
- Aktif / Nonaktif akun.

---

### FR-013 Email Notification

#### Deskripsi
Sistem mengirim email kepada Staff.

#### Acceptance Criteria
- Email dikirim setelah permit di-submit.
- Email berisi informasi permit.
- Email berisi tautan menuju halaman review.

---

## 12. Dashboard Requirements

### Dashboard Super Admin

**Widget**
- Total Divisi
- Total Akun Aktif
- Total Akun Nonaktif

**Quick Action**
- Tambah Akun Divisi

---

### Dashboard Divisi

**Widget**
- Draft
- Submitted
- Active
- Closed

**Section**
- Permit Terbaru

**Quick Action**
- Buat Permit

---

### Dashboard Staff

**Widget**
- Pending Review
- Permit Direvisi
- Permit Hari Ini

**Section**
- Permit Menunggu Review

**Quick Action**
- Review Permit

---

### Dashboard Manager

**Widget**
- Pending Approval
- Permit Hari Ini

**Section**
- Permit Menunggu Approval

**Quick Action**
- Review Permit

---

### Dashboard Senior Manager

**Widget**
- Pending Final Approval
- Permit Active Hari Ini

**Section**
- Permit Menunggu Final Approval

**Quick Action**
- Review Permit

---

## 13. Landing Page Requirements

Landing Page merupakan halaman pertama sebelum login.

### Header
- Logo PT INKA
- Nama Sistem
- Tentang
- Alur Pengajuan
- Fitur
- Panduan
- FAQ
- Tombol Login

Header selalu berada di bagian atas.

---

### Hero Section
**Sebelah kiri**
- Judul Sistem
- Deskripsi singkat
- Tombol Login
- Tombol Panduan

**Sebelah kanan**
Mockup Dashboard.

---

### Alur Pengajuan
Ditampilkan dalam empat langkah.

1. Login
2. Buat Permit
3. Approval
4. Permit Aktif

---

### Fitur Utama
Card
- Pengajuan Permit
- Approval Berjenjang
- Riwayat Permit
- Email Notification

---

### Keunggulan Sistem
- Cepat
- Mudah
- Digital
- Terdokumentasi

---

### FAQ
Pertanyaan umum mengenai sistem.

---

### Footer
- Logo
- Nama Sistem
- Kontak
- Copyright
- Versi Sistem

---

## 14. User Management

### Super Admin

**Dapat**
- Membuat akun Divisi
- Mengubah password
- Reset password
- Mengaktifkan akun
- Menonaktifkan akun

**Tidak dapat**
- Membuat Permit
- Melakukan Approval
- Mengubah History

---

## 15. Email Notification

Email hanya dikirim kepada Staff.

**Trigger**
Permit berhasil di-submit.

**Isi Email**
- Nomor Permit
- Nama Pekerjaan
- Divisi
- Lokasi
- Waktu Submit
- Tombol Review Permit

---

## 16. History

History dapat dilihat oleh
- Divisi
- Staff
- Manager
- Senior Manager

Divisi hanya dapat melihat permit miliknya.
Admin dapat melihat seluruh permit.

**Filter**
- Draft
- Submitted
- Revision
- Active
- Closed

**Pencarian**
- Nomor Permit
- Nama Pekerjaan
- Divisi
- Kontraktor

**Sort**
- Terbaru
- Terlama
- Status

---

## 17. UI & UX Requirements

### Design Philosophy

Sistem dirancang menggunakan pendekatan **Apple Human Interface** dengan fokus pada kemudahan penggunaan, konsistensi, dan tampilan yang bersih.

Target pengguna merupakan Staff, Manager, Senior Manager, dan Kontraktor yang sebagian besar merupakan pengguna non-teknis.

Prinsip utama desain:

- Mudah dipelajari dalam waktu kurang dari 5 menit.
- Tampilan sederhana dan tidak membingungkan.
- Mengutamakan keterbacaan dibanding kepadatan informasi.
- Mengurangi kemungkinan salah input.
- Konsisten pada setiap halaman.
- Menggunakan whitespace yang cukup agar nyaman dibaca.
- Setiap aksi utama dapat dilakukan maksimal dalam tiga klik.

---

## 18. Global Layout

Seluruh halaman menggunakan layout yang sama.

Layout terdiri dari:

- Header
- Sidebar
- Content Area

Header selalu berada di bagian atas.
Sidebar berada di sisi kiri.
Content Area menjadi area utama untuk seluruh halaman.

Gunakan card sebagai pembungkus setiap section.

---

## 19. Landing Page

Landing Page berfungsi sebagai halaman informasi sebelum pengguna login.

Landing Page terdiri dari beberapa section.

---

### Header
Komponen:
- Logo PT INKA
- Nama Sistem
- Tentang
- Alur Pengajuan
- Fitur
- Panduan
- FAQ
- Tombol Login

Header bersifat sticky ketika halaman di-scroll.

---

### Hero Section
**Sebelah kiri:**
- Judul Sistem
- Deskripsi singkat
- Tombol Login
- Tombol Panduan

**Sebelah kanan:**
Mockup Dashboard.
Tidak menggunakan foto pekerja.

---

### Alur Pengajuan
Ditampilkan dalam empat langkah.

1. Login
2. Buat Permit
3. Approval
4. Permit Aktif

Menggunakan icon sederhana.

---

### Fitur Sistem
Menampilkan empat card.
- Pengajuan Permit
- Approval Berjenjang
- Riwayat Permit
- Email Notification

---

### Keunggulan
Menampilkan manfaat sistem.
- Cepat
- Mudah
- Digital
- Terdokumentasi

---

### FAQ
Menampilkan pertanyaan umum mengenai sistem.

---

### Footer
Berisi:
- Logo
- Nama Sistem
- Kontak
- Copyright
- Versi Sistem

---

## 20. Dashboard

Dashboard dibuat berbeda sesuai role.
Semua dashboard memiliki pola yang sama.

Bagian atas:
Ringkasan informasi.

Bagian tengah:
Data utama.

Bagian bawah:
Aktivitas terbaru.

Dashboard tidak menggunakan grafik yang berlebihan.
Fokus pada informasi yang benar-benar dibutuhkan.

---

### Dashboard Divisi
**Widget:**
- Draft
- Submitted
- Active
- Closed

**Section:**
Permit Terbaru.

**Quick Action:**
Buat Permit.

---

### Dashboard Staff
**Widget:**
- Pending Review
- Permit Direvisi
- Permit Hari Ini

**Section:**
Permit Menunggu Review.

**Quick Action:**
Review Permit.

---

### Dashboard Manager
**Widget:**
- Pending Approval
- Permit Hari Ini

**Section:**
Permit Menunggu Approval.

---

### Dashboard Senior Manager
**Widget:**
- Pending Final Approval
- Permit Active Hari Ini

**Section:**
Permit Menunggu Final Approval.

---

### Dashboard Super Admin
**Widget:**
- Total Divisi
- Total Akun
- Akun Aktif
- Akun Nonaktif

**Quick Action:**
Tambah Akun Divisi.

---

## 21. Halaman Pengajuan Permit

Halaman ini merupakan halaman utama yang digunakan Divisi untuk membuat permit.

Form dibuat menggunakan **Wizard** agar tidak terlalu panjang.

Progress Wizard berada di bagian atas.

Step terdiri dari:
1. Informasi Pekerjaan
2. Bahaya & Pencegahan
3. APD
4. Dokumen & Validasi
5. Review & Submit

Pengguna hanya melihat satu step dalam satu waktu.

---

### Step 1 — Informasi Pekerjaan
Mengacu pada bagian A dan B formulir fisik.

Komponen:
- Jenis Permit
- Nama Pekerjaan
- Lokasi
- Nama Kontraktor
- Penanggung Jawab
- Nomor Telepon
- Daftar Pekerja
- Daftar Peralatan
- Daftar Material
- Tanggal Mulai
- Tanggal Selesai

Gunakan:
- Text Field
- Dropdown
- Date Picker
- Dynamic Table

---

### Step 2 — Bahaya & Pencegahan
Mengacu pada bagian C dan D formulir fisik.

Section terdiri dari dua card.

**Card pertama:**
Bahaya Pekerjaan.
Menggunakan checkbox.

**Card kedua:**
Tindakan Pencegahan.
Menggunakan checkbox.

Tambahkan field:
"Lainnya"

---

### Step 3 — APD
Mengacu pada bagian E formulir fisik.

Gunakan checkbox grid.

Contoh:
- Helm
- Sepatu
- Sarung Tangan
- Kacamata
- Rompi
- Face Shield
- Masker
- Ear Plug

Tambahkan field:
"Lainnya"

---

### Step 4 — Dokumen
Komponen:
- Upload Dokumen
- Upload HIRADC
- Upload JSA
- Upload Sertifikat
- Upload Foto Pendukung

Tampilkan preview file.

Tambahkan area:
Tanda Tangan Digital PIC.

---

### Step 5 — Review
Sistem menampilkan seluruh data yang telah diisi.

Bagian bawah terdapat dua tombol.
- Simpan Draft
- Submit Permit

---

## 22. Halaman Approval

Digunakan oleh:
- Staff
- Manager
- Senior Manager

Layout terdiri dari dua kolom.

**Kolom kiri:**
Informasi Permit.

**Kolom kanan:**
Dokumen Pendukung.

Di bagian bawah terdapat area komentar.

Tersedia dua tombol utama.
- Revisi
- Approve

Tidak terdapat tombol lain.

---

## 23. Halaman History

History menampilkan seluruh permit yang telah diproses.

Bagian atas:
Filter.
- Draft
- Submitted
- Revision
- Active
- Closed

Tersedia kolom pencarian.

Filter berdasarkan:
- Nomor Permit
- Nama Pekerjaan
- Kontraktor
- Divisi

Bagian bawah:
Tabel Permit.

Setiap baris memiliki tombol:
- Detail

Tidak dapat diedit.

---

## 24. User Management

Halaman ini hanya dapat diakses Super Admin.

Menampilkan tabel akun Divisi.

Informasi:
- Nama Divisi
- Username
- Status
- Tanggal Dibuat

Aksi:
- Tambah Akun
- Reset Password
- Aktifkan
- Nonaktifkan

---

## 25. Profile

Setiap pengguna memiliki halaman Profile yang menampilkan informasi dasar akun.

Informasi yang ditampilkan:
- Nama
- Jabatan
- Divisi
- Email

Fitur yang tersedia:
- Ubah Password

Tidak terdapat fitur kustomisasi profil seperti ubah foto profil.

---

## 26. UI Components

Gunakan komponen berikut secara konsisten.

- Button
- Card
- Input
- Dropdown
- Checkbox
- Table
- Badge
- Modal
- Date Picker
- File Upload
- Pagination

---

## 27. Design Guidelines

Gunakan:
- Rounded Corner 12–16 px
- Shadow tipis
- Border halus
- Card sederhana
- Font Inter atau Plus Jakarta Sans

**Primary Color:**
Biru.

**Background:**
Putih atau abu-abu sangat terang.

Status menggunakan warna:
- Biru
- Hijau
- Oranye
- Merah

---

## 28. UX Guidelines

Target utama adalah pengguna non-teknis.

Oleh karena itu:
- Hindari halaman yang terlalu ramai.
- Hindari form yang sangat panjang.
- Gunakan Wizard.
- Gunakan tombol besar.
- Gunakan icon yang sederhana.
- Gunakan bahasa Indonesia yang mudah dipahami.
- Gunakan label yang jelas.
- Selalu tampilkan status permit.
- Gunakan konfirmasi sebelum aksi penting.
- Tampilkan pesan sukses atau gagal setelah setiap aksi.

---

## 29. Validation Rules

### Login
- Username wajib diisi.
- Password wajib diisi.
- Username atau password salah menampilkan pesan error.
- Akun nonaktif tidak dapat login.

---

### Create Permit
Permit tidak dapat di-submit apabila:
- Data wajib belum lengkap.
- Belum melakukan tanda tangan PIC.
- Dokumen wajib belum diupload.

Sistem akan menampilkan pesan validasi pada field yang belum lengkap.

---

### Upload Dokumen
- File hanya menerima format PDF, JPG, JPEG, dan PNG.
- Maksimal ukuran file mengikuti konfigurasi sistem.
- File dapat dihapus sebelum permit di-submit.

---

### Approval
Approval hanya dapat dilakukan sesuai urutan.

**Staff** → **Manager** → **Senior Manager**

Manager tidak dapat melakukan approval apabila Staff belum menyetujui permit.
Senior Manager tidak dapat melakukan approval apabila Manager belum menyetujui permit.

---

### Revision
Approver wajib memberikan alasan revisi.

Setelah revisi dikirim:
- Status berubah menjadi Revision.
- Permit kembali ke Divisi.
- Divisi dapat mengedit permit kembali.

---

### Closing Permit
Permit hanya dapat ditutup apabila status Active.
Permit yang sudah Closed tidak dapat diedit kembali.

---

## 30. Permit Status

| Status | Deskripsi |
|---------|-----------|
| Draft | Permit masih dalam proses pengisian |
| Submitted | Permit telah dikirim |
| Review Staff | Menunggu approval Staff |
| Review Manager | Menunggu approval Manager |
| Review Senior Manager | Menunggu approval Senior Manager |
| Revision | Memerlukan perbaikan |
| Active | Permit telah disetujui |
| Closed | Pekerjaan telah selesai |

---

## 31. Non Functional Requirements

### Performance
- Waktu membuka halaman maksimal 3 detik.
- Dashboard dapat memuat data dengan cepat.
- Form dapat disimpan tanpa delay yang signifikan.

---

### Security
- Login menggunakan username dan password.
- Hak akses berdasarkan role.
- User hanya dapat mengakses menu sesuai role.
- Session akan berakhir setelah periode tidak aktif.

---

### Availability
- Sistem dapat diakses melalui browser modern.
- Mendukung penggunaan pada desktop maupun tablet.

---

### Usability
Sistem harus:
- Mudah digunakan.
- Mudah dipelajari.
- Memiliki navigasi yang konsisten.
- Menggunakan bahasa Indonesia yang jelas.

---

### Responsive
Tampilan minimal mendukung:
- Desktop
- Laptop
- Tablet

Mobile bukan bagian dari MVP.

---

## 32. Acceptance Criteria

### Login
- Pengguna berhasil login.
- Sistem mengarahkan ke dashboard sesuai role.

---

### Create Permit
- Permit dapat disimpan sebagai Draft.
- Permit dapat di-submit.
- Permit dapat diedit selama Draft atau Revision.

---

### Approval
- Approval mengikuti urutan.
- Riwayat approval tersimpan.
- Status berubah sesuai tahapan.

---

### History
- Data permit dapat dicari.
- Data permit dapat difilter.
- History hanya bersifat read-only.

---

### Dashboard
Dashboard menampilkan informasi sesuai role.

---

## 33. Access Matrix

| Modul | Super Admin | Divisi | Staff | Manager | Senior Manager |
|---------|------------|--------|--------|----------|----------------|
| Dashboard | ✓ | ✓ | ✓ | ✓ | ✓ |
| Login | ✓ | ✓ | ✓ | ✓ | ✓ |
| Create Permit | ✗ | ✓ | ✗ | ✗ | ✗ |
| Edit Draft | ✗ | ✓ | ✗ | ✗ | ✗ |
| Upload Dokumen | ✗ | ✓ | ✗ | ✗ | ✗ |
| Submit Permit | ✗ | ✓ | ✗ | ✗ | ✗ |
| Approval | ✗ | ✗ | ✓ | ✓ | ✓ |
| Revision | ✗ | ✗ | ✓ | ✓ | ✓ |
| History | ✗ | ✓* | ✓ | ✓ | ✓ |
| User Management | ✓ | ✗ | ✗ | ✗ | ✗ |
| Profile | ✓ | ✓ | ✓ | ✓ | ✓ |

> **Catatan:** Divisi hanya dapat melihat History permit miliknya sendiri.

---

## 34. Error Handling

Sistem harus menampilkan pesan yang mudah dipahami.

Contoh:
- Login gagal.
- Data belum lengkap.
- Upload gagal.
- File tidak didukung.
- Permit berhasil disimpan.
- Permit berhasil di-submit.
- Approval berhasil.
- Revisi berhasil dikirim.

---

## 35. Future Enhancement

Fitur berikut tidak termasuk MVP dan dapat dipertimbangkan pada versi berikutnya:

- Mobile Application
- QR Code Permit
- WhatsApp Notification
- Dashboard Analytics
- Integrasi ERP
- Integrasi HRIS
- Export PDF
- Export Excel
- Digital Signature tersertifikasi
- Multi Company
- Multi Plant
- Audit Log
- Dark Mode

---

## 36. Design System

### Design Style
Menggunakan pendekatan Apple Human Interface.

Karakteristik:
- Minimalis
- Modern
- Profesional
- Banyak whitespace
- Fokus pada keterbacaan

---

### Warna
- **Primary:** Biru
- **Success:** Hijau
- **Warning:** Oranye
- **Danger:** Merah
- **Background:** Putih atau abu-abu sangat terang.

---

### Typography
Font:
- Inter atau Plus Jakarta Sans

---

### Radius
- Card: 12–16 px
- Button: 12 px
- Input: 10–12 px

---

### Komponen
Gunakan komponen yang konsisten:
- Card
- Button
- Text Field
- Dropdown
- Checkbox
- Badge
- Table
- Modal
- Stepper
- Date Picker
- Upload File
- Search
- Pagination

---

## 37. AI UI Design Guidelines

Dokumen ini akan digunakan sebagai acuan AI Designer (Gemini + Stitch).

Desain yang dihasilkan harus mengikuti prinsip berikut:

- Menggunakan layout modern dan minimalis.
- Mengutamakan kemudahan penggunaan dibanding dekorasi visual.
- Menggunakan card sebagai pembungkus informasi.
- Menggunakan form wizard untuk proses pengajuan permit.
- Menghindari halaman yang terlalu padat.
- Menggunakan ikon sederhana.
- Tombol utama dibuat besar dan mudah dijangkau.
- Sidebar sederhana dengan jumlah menu seminimal mungkin.
- Dashboard menampilkan informasi penting tanpa grafik yang berlebihan.
- Landing Page menggunakan mockup dashboard sebagai hero image.
- Konsisten menggunakan gaya Apple Human Interface.
- Fokus pada pengguna berusia 40–60 tahun yang mungkin kurang terbiasa menggunakan aplikasi digital.
- Setiap halaman harus memiliki hierarki visual yang jelas, whitespace yang cukup, serta navigasi yang intuitif.

---

## 38. Kesimpulan

Work Permit Management System dikembangkan untuk menggantikan proses pengajuan izin kerja secara manual menjadi sistem digital yang sederhana, cepat, dan mudah digunakan.

Versi MVP difokuskan pada proses utama, yaitu:
- Pengajuan Permit
- Approval Berjenjang
- Dashboard
- History
- User Management
- Email Notification

Pendekatan desain mengutamakan kemudahan penggunaan bagi pengguna non-teknis dengan tampilan modern, minimalis, dan konsisten. Dokumen PRD ini menjadi acuan utama dalam proses desain UI, pengembangan aplikasi, serta implementasi sistem.

---

## UI Page Inventory

### Public
- Landing Page
- Login

### Super Admin
- Dashboard
- User Management
- Profile

### Divisi
- Dashboard
- Create Permit (Wizard)
- Edit Permit
- Detail Permit
- History
- Profile

### Staff
- Dashboard
- Approval List
- Detail Approval
- History
- Profile

### Manager
- Dashboard
- Approval List
- Detail Approval
- History
- Profile

### Senior Manager
- Dashboard
- Approval List
- Detail Approval
- History
- Profile
