<x-app-layout>
    <x-slot name="title">Dashboard — Staff (Reviewer Tahap 1)</x-slot>

    {{-- ===== WIDGETS: Pending Review | Permit Direvisi | Permit Hari Ini ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        {{-- Pending Review --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Pending Review</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
                <p class="text-[10px] text-gray-400">Menunggu review Anda</p>
            </div>
        </div>

        {{-- Permit Direvisi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Permit Direvisi</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
                <p class="text-[10px] text-gray-400">Dikembalikan ke Divisi</p>
            </div>
        </div>

        {{-- Permit Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Permit Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800 mt-0.5">0</p>
                <p class="text-[10px] text-gray-400">Masuk hari ini</p>
            </div>
        </div>
    </div>

    {{-- ===== SECTION: Permit Menunggu Review ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Permit Menunggu Review</h3>
                <p class="text-xs text-gray-400 mt-0.5">Permit yang perlu Anda review sebelum dilanjutkan ke Manager</p>
            </div>
            {{-- Quick Action: Review Permit --}}
            <a href="/staff/approvals" class="inline-flex items-center gap-2 bg-inka-navy text-white text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
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
                        <th class="px-6 py-3 font-semibold">Waktu Submit</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">
                            Tidak ada permit yang menunggu review saat ini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
