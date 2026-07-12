<x-app-layout>
    <x-slot name="title">History Permit</x-slot>

    {{-- ===== FILTER BAR ===== --}}
    <form method="GET" action="/divisi/history" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 mb-5 flex flex-wrap items-center gap-3">

        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px]">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari no. permit, nama pekerjaan, kontraktor..."
                class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-inka-navy/20 focus:border-inka-navy">
        </div>

        {{-- Status Filter --}}
        <select name="status" class="border border-gray-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-inka-navy/20 focus:border-inka-navy text-gray-600">
            <option value="">Semua Status</option>
            @foreach(['Draft','Submitted','Review Staff','Review Manager','Review Senior Manager','Revision','Active','Closed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>

        <button type="submit"
            class="px-4 py-2 bg-inka-navy text-white text-sm font-semibold rounded-xl hover:opacity-90 transition-opacity">
            Cari
        </button>

        @if(request('search') || request('status'))
        <a href="/divisi/history" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
            Reset
        </a>
        @endif

        <div class="ml-auto">
            <a href="/divisi/permits/create"
               class="inline-flex items-center gap-2 bg-inka-navy text-white text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Permit
            </a>
        </div>
    </form>

    {{-- ===== TABEL HISTORY ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Seluruh Permit</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if($permits->total() > 0)
                        Menampilkan {{ $permits->firstItem() }}–{{ $permits->lastItem() }} dari {{ $permits->total() }} permit
                    @else
                        Tidak ada permit ditemukan
                    @endif
                </p>
            </div>
        </div>

        @if($permits->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">Belum ada pengajuan permit.</p>
                <p class="text-xs text-gray-400 mt-1 mb-5">Mulai dengan membuat permit baru.</p>
                <a href="/divisi/permits/create"
                   class="inline-flex items-center gap-2 bg-inka-navy text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Permit
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50/60">
                            <th class="px-6 py-3 font-semibold">No. Permit</th>
                            <th class="px-6 py-3 font-semibold">Nama Pekerjaan</th>
                            <th class="px-6 py-3 font-semibold">Kontraktor</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold">Tgl. Dibuat</th>
                            <th class="px-6 py-3 font-semibold">Tgl. Submit</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($permits as $permit)
                        @php
                            $statusMap = [
                                'Draft'                    => 'bg-gray-100 text-gray-600',
                                'Submitted'                => 'bg-blue-100 text-blue-700',
                                'Review Staff'             => 'bg-orange-100 text-orange-700',
                                'Review Manager'           => 'bg-orange-100 text-orange-700',
                                'Review Senior Manager'    => 'bg-orange-100 text-orange-700',
                                'Revision'                 => 'bg-red-100 text-red-700',
                                'Active'                   => 'bg-green-100 text-green-700',
                                'Closed'                   => 'bg-slate-100 text-slate-600',
                            ];
                            $badge = $statusMap[$permit->status] ?? 'bg-gray-100 text-gray-600';
                            $canEdit = in_array($permit->status, ['Draft', 'Revision']);
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors cursor-pointer"
                            onclick="window.location='/divisi/permits/{{ $permit->id }}'">
                            <td class="px-6 py-3.5">
                                <a href="/divisi/permits/{{ $permit->id }}"
                                   class="font-semibold text-inka-navy hover:underline"
                                   onclick="event.stopPropagation()">
                                    {{ $permit->no_permit }}
                                </a>
                            </td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $permit->nama_pekerjaan }}</td>
                            <td class="px-6 py-3.5 text-gray-500 text-xs">{{ $permit->kontraktor }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $badge }}">
                                    {{ $permit->status }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-400 text-xs">
                                {{ $permit->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-3.5 text-gray-400 text-xs">
                                {{ $permit->submitted_at ? $permit->submitted_at->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-right" onclick="event.stopPropagation()">
                                <a href="/divisi/permits/{{ $permit->id }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-inka-navy hover:underline">
                                    {{ $canEdit ? 'Edit' : 'Lihat Detail' }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($permits->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $permits->links() }}
            </div>
            @endif
        @endif
    </div>

</x-app-layout>
