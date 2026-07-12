<x-app-layout>
    <x-slot name="title">Review Permit ({{ $config['roleName'] }})</x-slot>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-5 py-3 rounded-2xl">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm font-medium px-5 py-3 rounded-2xl">
        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ===== FILTER BAR ===== --}}
    <form method="GET" action="/admin/approvals" class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 mb-5 flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari no. permit, pekerjaan, kontraktor..."
                class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-inka-navy/20 focus:border-inka-navy">
        </div>

        <select name="status" class="border border-gray-200 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-inka-navy/20 focus:border-inka-navy text-gray-600">
            <option value="">Status: {{ $config['expectedStatus'] }}</option>
            <option value="Revision" {{ request('status') === 'Revision' ? 'selected' : '' }}>Direvisi</option>
            <option value="Semua" {{ request('status') === 'Semua' ? 'selected' : '' }}>Semua Status</option>
        </select>

        <button type="submit"
            class="px-4 py-2 bg-inka-navy text-white text-sm font-semibold rounded-xl hover:opacity-90 transition-opacity">
            Cari
        </button>

        @if(request('search') || request('status') || request('date'))
        <a href="/admin/approvals" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
            Reset
        </a>
        @endif
    </form>

    {{-- ===== TABEL APPROVALS ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Daftar Antrean Review</h3>
                <p class="text-xs text-gray-400 mt-0.5">Permit yang menunggu persetujuan tingkat {{ $config['roleName'] }}.</p>
            </div>
        </div>

        @if($permits->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-5">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">Tidak ada permit yang perlu direview saat ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50/60">
                            <th class="px-6 py-3 font-semibold">No. Permit</th>
                            <th class="px-6 py-3 font-semibold">Nama Pekerjaan</th>
                            <th class="px-6 py-3 font-semibold">Divisi</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold">{{ $config['dateColumnLabel'] }}</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($permits as $permit)
                        <tr class="hover:bg-orange-50/30 transition-colors cursor-pointer" onclick="window.location='/admin/approvals/{{ $permit->id }}'">
                            <td class="px-6 py-3.5"><a href="/admin/approvals/{{ $permit->id }}" class="font-semibold text-inka-navy hover:underline" onclick="event.stopPropagation()">{{ $permit->no_permit }}</a></td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $permit->nama_pekerjaan }}</td>
                            <td class="px-6 py-3.5 text-gray-700">{{ optional($permit->user)->name ?? '—' }}</td>
                            <td class="px-6 py-3.5">
                                @if($permit->status === $config['expectedStatus'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-orange-100 text-orange-700">Menunggu Anda</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 text-red-700">{{ $permit->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-gray-500 text-xs">
                                @if($config['sortCol'] === 'submitted_at')
                                    {{ $permit->submitted_at ? $permit->submitted_at->format('d/m/Y') : '—' }}
                                @else
                                    {{ $permit->updated_at ? $permit->updated_at->format('d/m/Y') : '—' }}
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                @if($permit->status === $config['expectedStatus'])
                                <a href="/admin/approvals/{{ $permit->id }}" class="inline-flex items-center bg-inka-navy text-white text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 transition-colors">
                                    Review
                                </a>
                                @else
                                <a href="/admin/approvals/{{ $permit->id }}" class="inline-flex items-center text-inka-navy text-[11px] font-semibold hover:underline">
                                    Lihat
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($permits->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $permits->links() }}
            </div>
            @endif
        @endif
    </div>
</x-app-layout>
