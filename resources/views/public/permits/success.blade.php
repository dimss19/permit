<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permit Terkirim — INKA Madiun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center px-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm max-w-md w-full p-8 text-center">
        <div class="w-14 h-14 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-xl font-extrabold">Permit Berhasil Diajukan</h1>
        <p class="text-sm text-gray-500 mt-2">Nomor permit Anda:</p>
        <p class="text-lg font-bold text-inka-navy mt-1">{{ $permit->no_permit }}</p>
        <p class="text-sm text-gray-500 mt-3">
            Divisi <span class="font-semibold text-gray-700">{{ $permit->divisi_pengaju }}</span> —
            status <span class="font-semibold text-orange-600">Review Staff</span>.
            Pantau di tabel Monitoring Beranda dan simpan nomor permit ini.
        </p>
        <div class="mt-6 flex gap-3 justify-center">
            <a href="/" class="px-5 py-2.5 rounded-xl bg-inka-navy text-white text-sm font-semibold hover:opacity-90">Kembali ke Beranda</a>
            <a href="/ajukan-permit" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:border-gray-300">Ajukan Lagi</a>
        </div>
    </div>
</body>
</html>
