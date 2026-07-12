<x-app-layout>
    <x-slot name="title">
        Dashboard Super Admin
    </x-slot>

    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800">Selamat datang, Super Admin!</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola akun divisi dan pantau status akun pengguna sistem.</p>
    </div>

    <!-- Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Divisi</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">12</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Akun Aktif</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">10</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 flex items-center">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Akun Nonaktif</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">2</p>
            </div>
        </div>
    </div>

    <!-- Quick Action & List -->
    <div class="bg-white rounded-xl shadow-sm border border-inka-border">
        <div class="p-6 border-b border-inka-border flex justify-between items-center">
            <h4 class="font-bold text-gray-800">Daftar Akun Divisi</h4>
            <button class="bg-accent-orange text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                + Tambah Akun Divisi
            </button>
        </div>
        <div class="p-6">
            <div class="text-center py-10 text-gray-500 text-sm">
                Belum ada data divisi yang ditambahkan.
            </div>
        </div>
    </div>
</x-app-layout>
