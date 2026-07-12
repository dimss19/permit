<x-app-layout>
    <x-slot name="title">Dashboard — Senior Manager (Final Approval)</x-slot>

    {{-- ===== WIDGETS: Pending Final Approval | Permit Active Hari Ini ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
        {{-- Pending Final Approval --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Pending Final Approval</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
                <p class="text-[10px] text-gray-400">Menunggu persetujuan akhir Anda</p>
            </div>
        </div>

        {{-- Permit Active Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Permit Active Hari Ini</p>
                <p class="text-2xl font-bold text-green-600 mt-0.5">0</p>
                <p class="text-[10px] text-gray-400">Disetujui dan sedang berlaku</p>
            </div>
        </div>
    </div>

    {{-- ===== SECTION: Permit Menunggu Final Approval ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Permit Menunggu Final Approval</h3>
                <p class="text-xs text-gray-400 mt-0.5">Permit yang telah disetujui Manager dan menunggu persetujuan akhir Anda</p>
            </div>
            {{-- Quick Action: Review Permit --}}
            <a href="/senior-manager/approvals" class="inline-flex items-center gap-2 bg-inka-navy text-white text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                Lihat Semua Permit
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                        <th class="px-6 py-3 font-semibold">No. Permit</th>
                        <th class="px-6 py-3 font-semibold">Divisi</th>
                        <th class="px-6 py-3 font-semibold">Nama Pekerjaan</th>
                        <th class="px-6 py-3 font-semibold">Disetujui Manager</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">
                            Tidak ada permit yang menunggu final approval saat ini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
