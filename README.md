<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Work Permit Management System

Sistem manajemen pengajuan izin kerja (Work Permit) berbasis web yang mendigitalisasi proses pengajuan, review, dan persetujuan izin kerja di lingkungan perusahaan.

---

## 📋 Prasyarat

Sebelum memulai instalasi, pastikan **local server** Anda sudah memiliki aplikasi berikut:

| Aplikasi | Versi Minimal | Keterangan |
|----------|--------------|------------|
| **PHP** | ^8.3 | Bahasa pemrograman |
| **Composer** | v2.x | Dependency manager PHP |
| **Node.js** | ^18.x | Runtime JavaScript |
| **NPM** | ^9.x | Package manager Node.js |
| **MySQL** | ^8.0 | Database server |
| **Web Server** | - | Apache / Nginx (bawaan Laragon/XAMPP) |

> 💡 **Rekomendasi:** Gunakan **[Laragon](https://laragon.org/)** untuk kemudahan instalasi di Windows. Laragon sudah menyertakan PHP, Composer, Apache/Nginx, dan MySQL sekaligus.

---

## 🚀 Panduan Instalasi (Local Server)

Berikut adalah langkah-langkah instalasi untuk menjalankan aplikasi di **local server** (Laragon / XAMPP / LAMP).


### 📦 1. Clone Repository

Buka terminal (Git Bash / CMD / PowerShell) dan jalankan:

```bash
git clone https://github.com/dimss19/permit.git
cd permit
```

Atau jika menggunakan Laragon, clone project ke folder `C:\laragon\www\`:

```bash
cd C:\laragon\www
git clone https://github.com/dimss19/permit.git
cd permit
```

Akses aplikasi melalui: `http://permit.test` (setelah setup Laragon).

---

### 🗄️ 2. Membuat Database di MySQL

Sebelum melanjutkan, Anda perlu membuat database terlebih dahulu.

#### Opsi A — Melalui phpMyAdmin

1. Buka **phpMyAdmin** di browser: `http://localhost/phpmyadmin`
2. Klik tab **"Databases"** (atau **"New"** di sidebar kiri)
3. Masukkan nama database, contoh: `work_permit`
4. Pilih **utf8mb4_general_ci** sebagai collation
5. Klik tombol **"Create"**

#### Opsi B — Melalui Command Line (MySQL CLI)

Buka terminal dan jalankan perintah berikut:

```bash
# Login ke MySQL
mysql -u root -p

# Masukkan password MySQL jika diminta

# Buat database baru
CREATE DATABASE work_permit CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Verifikasi database telah dibuat
SHOW DATABASES;

# Keluar dari MySQL
EXIT;
```

#### Opsi C — Melalui Laragon

1. Buka **Laragon** > Klik kanan > **MySQL** > **Open MySQL CLI**
2. Jalankan perintah SQL:

```sql
CREATE DATABASE work_permit CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

> ✅ Database `work_permit` berhasil dibuat dan siap digunakan.

---

### ⚙️ 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi database dari **SQLite** (default) menjadi **MySQL**:

```env
# Ubah dari:
DB_CONNECTION=sqlite

# Menjadi:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=work_permit
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ **Catatan:** Jika Anda menggunakan password MySQL, isikan pada `DB_PASSWORD=`. Default Laragon/XAMPP biasanya tanpa password (`root` dengan password kosong).

Sesuaikan juga `APP_URL` jika diperlukan:

```env
APP_URL=http://localhost
```

---

### 🔧 4. Install Dependensi PHP (Composer)

```bash
composer install
```

Untuk environment production (tanpa dev dependencies):

```bash
composer install --optimize-autoloader --no-dev
```

---

### 🔑 5. Generate Application Key

```bash
php artisan key:generate
```

---

### 🔗 6. Storage Link

Buat symlink storage agar file yang diunggah dapat diakses publik:

```bash
php artisan storage:link
```

---

### 📊 7. Migrasi Database & Seeder

Jalankan migrasi untuk membuat tabel-tabel beserta data awal (seeder):

```bash
php artisan migrate:fresh --seed
```

Perintah di atas akan:
- Membuat seluruh tabel yang dibutuhkan
- Mengisi data awal (seperti user, roles, dll.)

> ⚠️ **Peringatan:** Perintah `migrate:fresh` akan menghapus semua tabel yang ada dan membuatnya kembali. Untuk migrasi biasa tanpa menghapus data, gunakan `php artisan migrate`.

---

### 🎨 8. Install & Build Assets (Tailwind / Vite)

```bash
npm install
npm run build
```

Untuk development, Anda bisa menjalankan Vite dev server agar perubahan asset langsung terlihat:

```bash
npm run dev
```

---

### ▶️ 9. Menjalankan Aplikasi

#### Opsi A — Menggunakan Laragon

1. Buka **Laragon**
2. Klik tombol **"Start All"**
3. Akses: `http://permit.test` (atau sesuai folder project Anda)

#### Opsi B — Menggunakan PHP Artisan Serve

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

#### Opsi C — Menggunakan Laravel Sail (Docker)

Jika Anda lebih suka menggunakan Docker:

```bash
composer require laravel/sail --dev
php artisan sail:install
./vendor/bin/sail up
```

---

## 🔄 Ringkasan Perintah Instalasi

Untuk memudahkan, berikut ringkasan semua perintah dalam satu blok:

```bash
# 1. Clone repository
git clone https://github.com/dimss19/permit.git
cd permit

# 2. Copy .env dan sesuaikan DB_CONNECTION ke mysql
cp .env.example .env
# >> Edit file .env: DB_CONNECTION=mysql, DB_DATABASE=work_permit

# 3. Install dependencies
composer install
php artisan key:generate
php artisan storage:link

# 4. Jalankan migrasi dan seeder
php artisan migrate:fresh --seed

# 5. Build assets
npm install
npm run build

# 6. Jalankan server
php artisan serve
```

---

## 🐳 Menjalankan Aplikasi (Development Mode)

Untuk development, Anda bisa menjalankan semua service sekaligus:

```bash
composer run dev
```

Perintah ini akan menjalankan secara bersamaan:
- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail` (log viewer)
- `npm run dev` (Vite dev server)

---

## 🧪 Menjalankan Test

```bash
composer run test
```

Atau secara langsung:

```bash
php artisan test
```

---

## 👤 Akun Default (Setelah Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat login menggunakan akun berikut:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | (sesuai seeder) | (sesuai seeder) |
| Staff | (sesuai seeder) | (sesuai seeder) |
| Divisi | (sesuai seeder) | (sesuai seeder) |

> 🔍 Cek file seeder di `database/seeders/` untuk melihat detail akun yang dibuat.

---

## 📁 Struktur Direktori Utama

```
├── app/                   # Kode aplikasi (Controllers, Models, dll.)
├── bootstrap/             # Bootstrap aplikasi
├── config/                # File konfigurasi
├── database/              # Migrations, seeders, factories
├── public/                # Public directory (entry point)
├── resources/             # Views, CSS, JS (Vue/React)
├── routes/                # Route definitions
├── storage/               # File storage (logs, cache, uploads)
├── tests/                 # Unit & Feature tests
├── vendor/                # Composer dependencies
└── package.json           # Node.js dependencies
```

---

## 🛠️ Troubleshooting

| Masalah | Solusi |
|---------|--------|
| **Port 8000 sudah digunakan** | Gunakan port lain: `php artisan serve --port=8080` |
| **Error koneksi database** | Pastikan MySQL sudah berjalan dan kredensial di `.env` sudah benar |
| **Error "Class not found"** | Jalankan `composer dump-autoload` |
| **Error 419 (CSRF)** | Pastikan session table sudah termigrasi: `php artisan migrate` |
| **File upload tidak muncul** | Jalankan `php artisan storage:link` |
| **Error Vite manifest** | Jalankan `npm install && npm run build` |

---

## 📄 License

Project ini dibangun menggunakan framework [Laravel](https://laravel.com) yang merupakan open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).