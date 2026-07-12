<x-app-layout>
    <x-slot name="title">
        Dashboard Divisi
    </x-slot>

    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Selamat datang, Divisi Teknik!</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola dan ajukan Work Permit untuk pekerjaan risiko tinggi.</p>
        </div>
        <button class="bg-inka-navy text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Permit Baru
        </button>
    </div>

    <!-- Widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Draft</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">3</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Submitted</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">5</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Active</p>
            <p class="text-3xl font-bold text-green-600 mt-2">12</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Closed</p>
            <p class="text-3xl font-bold text-gray-400 mt-2">48</p>
        </div>
    </div>

    <!-- Permit Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-inka-border">
        <div class="p-6 border-b border-inka-border">
            <h4 class="font-bold text-gray-800">Permit Terbaru</h4>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 font-semibold">No. Permit</th>
                            <th class="px-4 py-3 font-semibold">Pekerjaan</th>
                            <th class="px-4 py-3 font-semibold">Kontraktor</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">WP-2026-001</td>
                            <td class="px-4 py-3">Perbaikan Atap Gudang B</td>
                            <td class="px-4 py-3">PT Maju Mundur</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-bold rounded-full">SUBMITTED</span></td>
                            <td class="px-4 py-3 text-right">
                                <a href="#" class="text-inka-navy font-medium hover:underline text-xs">Detail</a>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">WP-2026-002</td>
                            <td class="px-4 py-3">Instalasi Listrik Panel Panel</td>
                            <td class="px-4 py-3">CV Terang Abadi</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 bg-green-100 text-green-800 text-[10px] font-bold rounded-full">ACTIVE</span></td>
                            <td class="px-4 py-3 text-right">
                                <a href="#" class="text-inka-navy font-medium hover:underline text-xs">Detail</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
