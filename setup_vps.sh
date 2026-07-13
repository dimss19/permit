#!/bin/bash

echo "=================================================="
echo "🚀 Memulai Setup Instan Work Permit di VPS..."
echo "=================================================="

# Pastikan script berhenti jika ada error
set -e

echo "[1/7] Menginstal dependensi PHP (Composer)..."
# Hapus vendor dan composer.lock jika ada masalah bentrok di server
# rm -rf vendor composer.lock
composer install --optimize-autoloader --no-dev

echo "[2/7] Menyiapkan file .env..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "File .env berhasil dibuat dari .env.example."
else
    echo "File .env sudah ada, melewati tahap ini."
fi

echo "[3/7] Mengonfigurasi APP_URL di .env..."
# Mengubah APP_URL agar sesuai dengan IP VPS dan Port
sed -i 's|^APP_URL=.*|APP_URL=http://82.153.226.85:8000|' .env

echo "[4/7] Menghasilkan Application Key..."
php artisan key:generate --force

echo "[5/7] Menautkan folder Storage (Storage Link)..."
php artisan storage:link || true

echo "[6/7] Menjalankan Migrasi Database dan Seeder..."
# Pastikan Anda sudah mengatur kredensial database (DB_DATABASE, DB_USERNAME, DB_PASSWORD) di file .env sebelum script ini dijalankan.
# Jika menggunakan SQLite, script ini akan otomatis membuat file database jika diperlukan.
php artisan migrate:fresh --seed --force

echo "[7/7] Menginstal dependensi NPM dan Build Assets (Tailwind/Vite)..."
npm install
npm run build

echo "=================================================="
echo "✅ Setup Berhasil Diselesaikan!"
echo "=================================================="
echo ""
echo "Untuk menjalankan aplikasi agar dapat diakses melalui internet, gunakan perintah berikut:"
echo ""
echo "    php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "Atau jika Anda ingin menjalankannya di background (agar tidak mati saat terminal ditutup), Anda bisa menggunakan screen/tmux atau nohup:"
echo ""
echo "    nohup php artisan serve --host=0.0.0.0 --port=8000 > storage/logs/server.log 2>&1 &"
echo "=================================================="
