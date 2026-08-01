# Relasi Database — Work Permit

**Database:** `workpermit` (MySQL) | **Host:** 127.0.0.1 | **Port:** 3306

---

## Tabel & Relasi

```
users (1) ────────── (N) permits
users (1) ────────── (N) sessions
divisions (1)        (N) permits   ← belum ada FK di migrations
```

---

### 1. `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT (PK) | Auto-increment |
| name | VARCHAR | - |
| email | VARCHAR (UNIQUE) | - |
| email_verified_at | TIMESTAMP (NULL) | - |
| password | VARCHAR | - |
| remember_token | VARCHAR (NULL) | - |
| role | VARCHAR (default: 'divisi') | ditambah via migrate |
| username | VARCHAR (UNIQUE, NULL) | ditambah via migrate |
| is_active | BOOLEAN (default: true) | ditambah via migrate |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

### 2. `divisions`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT (PK) | Auto-increment |
| name | VARCHAR (UNIQUE) | - |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

### 3. `permits`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT (PK) | Auto-increment |
| no_permit | VARCHAR (UNIQUE) | Nomor permit |
| **user_id** | BIGINT (FK → users.id) | Pemilik / divisi |
| nama_pekerjaan | VARCHAR | - |
| kontraktor | VARCHAR | - |
| lokasi | VARCHAR (NULL) | - |
| penanggung_jawab | VARCHAR (NULL) | - |
| telepon | VARCHAR (NULL) | - |
| tanggal_mulai | DATE (NULL) | - |
| tanggal_selesai | DATE (NULL) | - |
| status | ENUM | Draft, Submitted, Review Staff, Review Manager, Review Senior Manager, Revision, Active, Closed, Cancelled |
| submitted_at | TIMESTAMP (NULL) | - |
| closed_at | TIMESTAMP (NULL) | - |
| catatan_revisi | TEXT (NULL) | - |
| klasifikasi_pekerjaan | JSON (NULL) | - |
| perusahaan | VARCHAR (NULL) | - |
| daftar_pekerja | JSON (NULL) | - |
| peralatan_kerja | JSON (NULL) | - |
| bahaya_pekerjaan | JSON (NULL) | - |
| bahaya_lainnya | VARCHAR (NULL) | - |
| tindakan_pencegahan | JSON (NULL) | - |
| pencegahan_lainnya | VARCHAR (NULL) | - |
| apd | JSON (NULL) | - |
| apd_lainnya | VARCHAR (NULL) | - |
| cancelled_at | TIMESTAMP (NULL) | - |
| cancellation_reason | TEXT (NULL) | - |
| cancellation_signatures | JSON (NULL) | - |
| approval_signatures | JSON (NULL) | - |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

### 4. `sessions`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | VARCHAR (PK) | - |
| **user_id** | BIGINT (FK → users.id, NULL) | - |
| ip_address | VARCHAR (45, NULL) | - |
| user_agent | TEXT (NULL) | - |
| payload | LONGTEXT | - |
| last_activity | INT (INDEX) | - |

### 5. `password_reset_tokens`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| email | VARCHAR (PK) | - |
| token | VARCHAR | - |
| created_at | TIMESTAMP (NULL) | - |

---

## Relasi Detail

| Relasi | Tipe | FK | ON DELETE | Keterangan |
|--------|------|----|-----------|------------|
| `users` → `permits` | **1 : N** | `permits.user_id` | `nullOnDelete` | Satu user bisa punya banyak permit |
| `users` → `sessions` | **1 : N** | `sessions.user_id` | CASCADE (implicit) | Satu user bisa punya banyak sesi aktif |
| `users` → `divisions` | **N : N** (konseptual) | — | — | Belum ada FK; `role='divisi'` pada users menunjukkan user mewakili divisi |

---

## Ketentuan ENUM `permits.status` (terbaru)
1. Draft
2. Submitted
3. Review Staff
4. Review Manager
5. Review Senior Manager
6. Revision
7. Active
8. Closed
9. Cancelled