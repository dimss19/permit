#!/bin/bash

echo "=================================================="
echo "  Setup Work Permit - Local Server"
echo "=================================================="
echo ""

set -e

# --- Input Konfigurasi Database ---
echo "Masukkan konfigurasi database MySQL:"
echo "(Database harus sudah dibuat sebelumnya)"
echo ""

read -p "Database name [work_permit]: " DB_NAME
DB_NAME=${DB_NAME:-work_permit}

read -p "Database username [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -sp "Database password (kosongkan jika tanpa password): " DB_PASS
DB_PASS=${DB_PASS:-}
echo ""

read -p "APP_URL [http://localhost]: " APP_URL
APP_URL=${APP_URL:-http://localhost}

echo ""
echo "=================================================="
echo "  Memulai instalasi..."
echo "=================================================="
echo ""

# --- 1. Copy .env ---
echo "[1/7] Menyiapkan file .env..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "File .env berhasil dibuat dari .env.example."
else
    echo "File .env sudah ada, melewati tahap ini."
fi

# --- 2. Konfigurasi Database di .env ---
echo "[2/7] Mengonfigurasi database MySQL di .env..."
sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=mysql|" .env
sed -i "s|^# DB_HOST=.*|DB_HOST=127.0.0.1|" .env
sed -i "s|^# DB_PORT=.*|DB_PORT=3306|" .env
sed -i "s|^# DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
sed -i "s|^# DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
sed -i "s|^# DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|" .env
sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env

# Uncomment DB lines
sed -i "s|^DB_CONNECTION=sqlite|DB_CONNECTION=mysql|" .env

echo "Database dikonfigurasi: ${DB_NAME}"

# --- 3. Install Composer ---
echo "[3/7] Menginstal dependensi PHP (Composer)..."
composer install

# --- 4. Generate Key ---
echo "[4/7] Menghasilkan Application Key..."
php artisan key:generate --force

# --- 5. Storage Link ---
echo "[5/7] Membuat Storage Link..."
php artisan storage:link || true

# --- 6. Migrate & Seed ---
echo "[6/7] Menjalankan migrasi database dan seeder..."
php artisan migrate:fresh --seed --force

# --- 7. NPM Install & Build ---
echo "[7/7] Menginstal dependensi NPM dan build assets..."
npm install
npm run build

echo ""
echo "=================================================="
echo "  Setup selesai!"
echo "=================================================="
echo ""
echo "Jalankan aplikasi:"
echo "  php artisan serve"
echo ""
echo "Atau akses via Laragon: http://${APP_URL#http://}"
echo "=================================================="
