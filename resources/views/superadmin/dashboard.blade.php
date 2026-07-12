<x-app-layout>
    <x-slot name="title">Dashboard — Super Admin</x-slot>

    {{-- ===== WIDGETS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        {{-- Total Divisi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Divisi</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
            </div>
        </div>

        {{-- Total Akun --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Akun</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
            </div>
        </div>

        {{-- Akun Aktif --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Akun Aktif</p>
                <p class="text-2xl font-bold text-green-600 mt-0.5">0</p>
            </div>
        </div>

        {{-- Akun Nonaktif --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Akun Nonaktif</p>
                <p class="text-2xl font-bold text-red-500 mt-0.5">0</p>
            </div>
        </div>
    </div>

    {{-- ===== QUICK ACTION + TABLE ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Daftar Akun Divisi</h3>
                <p class="text-xs text-gray-400 mt-0.5">Kelola akun pengguna yang bertanggung jawab atas pengajuan permit</p>
            </div>
            {{-- Quick Action: Tambah Akun Divisi --}}
            <a href="/superadmin/users/create" class="inline-flex items-center gap-2 bg-inka-navy text-white text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Akun Divisi
            </a>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                        <th class="px-6 py-3 font-semibold">Nama Divisi</th>
                        <th class="px-6 py-3 font-semibold">Email / Username</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Tanggal Dibuat</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">
                            Belum ada akun divisi yang dibuat.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
