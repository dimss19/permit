<x-app-layout>
    <x-slot name="title">Dashboard — Manager</x-slot>

    {{-- ===== WIDGET RINGKASAN (2 KARTU KLIKABLE) ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

        {{-- Pending Approval --}}
        <a href="/manager/approvals?status=Review Manager"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-orange-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Pending Approval</p>
                <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center group-hover:bg-orange-100 transition-colors">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-orange-500">{{ $counts['pending'] }}</p>
            <p class="text-[10px] text-gray-400 mt-1">Menunggu persetujuan Anda</p>
        </a>

        {{-- Permit Hari Ini --}}
        <a href="/manager/approvals?date=today"
           class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-gray-200 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Permit Hari Ini</p>
                <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-700">{{ $counts['today'] }}</p>
            <p class="text-[10px] text-gray-400 mt-1">Masuk hari ini dari Staff</p>
        </a>

    </div>

    {{-- ===== SECTION: PERMIT MENUNGGU APPROVAL ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

        {{-- Header section --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Permit Menunggu Approval</h3>
                <p class="text-xs text-gray-400 mt-0.5">Urutan berdasarkan waktu masuk terlama</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/manager/approvals" class="text-sm font-semibold text-inka-navy hover:underline">
                    Lihat Semua →
                </a>
                <a href="/manager/approvals"
                   class="inline-flex items-center gap-2 bg-inka-navy text-white text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
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
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">Tidak ada permit yang menunggu persetujuan.</p>
                <p class="text-xs text-gray-400 mt-1 mb-6">Permit akan muncul di sini setelah disetujui oleh Staff.</p>
            </div>

        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50/60">
                            <th class="px-6 py-3 font-semibold">No. Permit</th>
                            <th class="px-6 py-3 font-semibold">Nama Pekerjaan</th>
                            <th class="px-6 py-3 font-semibold">Divisi</th>
                            <th class="px-6 py-3 font-semibold">Kontraktor</th>
                            <th class="px-6 py-3 font-semibold">Tgl. Masuk Manager</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($permits as $permit)
                        <tr class="hover:bg-orange-50/30 transition-colors cursor-pointer"
                            onclick="window.location='/manager/approvals/{{ $permit->id }}'">
                            <td class="px-6 py-3.5">
                                <a href="/manager/approvals/{{ $permit->id }}"
                                   class="font-semibold text-inka-navy hover:underline"
                                   onclick="event.stopPropagation()">
                                    {{ $permit->no_permit }}
                                </a>
                            </td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $permit->nama_pekerjaan }}</td>
                            <td class="px-6 py-3.5 text-gray-700">{{ optional($permit->user)->name ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-gray-500 text-xs">{{ $permit->kontraktor }}</td>
                            <td class="px-6 py-3.5 text-gray-500 text-xs">
                                {{ $permit->updated_at ? $permit->updated_at->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-right" onclick="event.stopPropagation()">
                                <a href="/manager/approvals/{{ $permit->id }}"
                                   class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-orange-200 transition-colors">
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
