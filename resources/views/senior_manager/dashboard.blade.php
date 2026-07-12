<x-app-layout>
    <x-slot name="title">
        Dashboard Senior Manager (Final Approval)
    </x-slot>

    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Selamat datang, Senior Manager!</h3>
            <p class="text-sm text-gray-500 mt-1">Lakukan final approval untuk mengaktifkan Work Permit.</p>
        </div>
    </div>

    <!-- Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 border-l-4 border-l-yellow-500">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending Final Approval</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">1</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-inka-border p-6 border-l-4 border-l-green-500">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Permit Active Hari Ini</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">4</p>
        </div>
    </div>

    <!-- Permit Menunggu Final Approval -->
    <div class="bg-white rounded-xl shadow-sm border border-inka-border">
        <div class="p-6 border-b border-inka-border flex justify-between items-center">
            <h4 class="font-bold text-gray-800">Permit Menunggu Final Approval</h4>
            <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded-full">1 Perlu Tindakan</span>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 font-semibold">No. Permit</th>
                            <th class="px-4 py-3 font-semibold">Divisi</th>
                            <th class="px-4 py-3 font-semibold">Pekerjaan</th>
                            <th class="px-4 py-3 font-semibold">Disetujui Oleh (Manager)</th>
                            <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">WP-2026-005</td>
                            <td class="px-4 py-3">Divisi Produksi</td>
                            <td class="px-4 py-3">Pembersihan Tangki</td>
                            <td class="px-4 py-3">Agus Manager HSE</td>
                            <td class="px-4 py-3 text-right">
                                <a href="#" class="bg-inka-navy text-white px-3 py-1.5 rounded text-xs font-semibold hover:bg-opacity-90">Review Permit</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
