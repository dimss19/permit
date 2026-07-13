<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $roleName = match($role) {
            'staff' => 'Staff',
            'manager' => 'Manager',
            'senior-manager' => 'Senior Manager',
            default => 'Admin'
        };
    @endphp

    {{-- ===== WIDGET RINGKASAN ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        {{-- Pending Approval (Semua Role) --}}
        <a href="/admin/approvals"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Pending Review</p>
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-blue-500">{{ $counts['pending'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu persetujuan Anda</p>
        </a>

        @if($role === 'staff')
        {{-- Permit Direvisi (Khusus Staff) --}}
        <a href="/admin/approvals?status=Revision"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-red-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Permit Direvisi</p>
                <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center group-hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-red-500">{{ $counts['revision'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Dikembalikan ke Divisi</p>
        </a>
        @endif

        @if($role === 'senior-manager')
        {{-- Permit Aktif Hari Ini (Khusus Senior Manager) --}}
        <a href="/admin/history?status=Active"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-green-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Active Hari Ini</p>
                <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-green-500">{{ $counts['active_today'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Permit disetujui hari ini</p>
        </a>
        @endif

        {{-- Permit Hari Ini (Semua Role, kalau Staff/Manager bisa pakai ini) --}}
        @if($role !== 'senior-manager')
        <a href="/admin/approvals?date=today"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-gray-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Masuk Hari Ini</p>
                <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-gray-700">{{ $counts['today'] ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Antrean masuk hari ini</p>
        </a>
        @endif

    </div>

    {{-- ===== SECTION: PERMIT MENUNGGU REVIEW ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

        {{-- Header section --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-base font-semibold text-gray-800">Permit Menunggu Approval ({{ $roleName }})</h3>
                <p class="text-sm text-gray-400 mt-0.5">Urutan berdasarkan waktu pengajuan terlama</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin/approvals" class="text-base font-semibold text-inka-navy hover:underline">
                    Lihat Semua
                </a>
                <a href="/admin/approvals"
                   class="inline-flex items-center gap-2 bg-inka-navy text-white text-base font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Review Permit
                </a>
            </div>
        </div>

        @if($permits->isEmpty())
            {{-- ===== EMPTY STATE ===== --}}
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-5">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-base font-semibold text-gray-700">Tidak ada permit yang menunggu review.</p>
                <p class="text-sm text-gray-400 mt-1 mb-6">Permit akan muncul di sini bila perlu ditindaklanjuti.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-base">
                    <thead>
                        <tr class="text-sm text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50/60">
                            <th class="px-6 py-3 font-semibold">No. Permit</th>
                            <th class="px-6 py-3 font-semibold">Nama Pekerjaan</th>
                            <th class="px-6 py-3 font-semibold">Divisi</th>
                            <th class="px-6 py-3 font-semibold">Kontraktor</th>
                            <th class="px-6 py-3 font-semibold">Tanggal Submit</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($permits as $permit)
                        <tr class="hover:bg-orange-50/30 transition-colors cursor-pointer"
                            onclick="window.location='/admin/approvals/{{ $permit->id }}'">
                            <td class="px-6 py-3.5">
                                <a href="/admin/approvals/{{ $permit->id }}"
                                   class="font-semibold text-inka-navy hover:underline"
                                   onclick="event.stopPropagation()">
                                    {{ $permit->no_permit }}
                                </a>
                            </td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $permit->nama_pekerjaan }}</td>
                            <td class="px-6 py-3.5 text-gray-700">{{ optional($permit->user)->name ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-gray-500 text-sm">{{ $permit->kontraktor }}</td>
                            <td class="px-6 py-3.5 text-gray-500 text-sm">
                                {{ $permit->submitted_at ? $permit->submitted_at->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-right" onclick="event.stopPropagation()">
                                <a href="/admin/approvals/{{ $permit->id }}"
                                   class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-orange-200 transition-colors">
                                    Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-app-layout>
