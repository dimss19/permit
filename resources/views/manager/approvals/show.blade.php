<x-app-layout>
    <x-slot name="title">Detail Permit — {{ $permit->no_permit }}</x-slot>

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
    @endphp

    {{-- Breadcrumb + aksi --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2 text-sm text-gray-400">
            <a href="/manager/dashboard" class="hover:text-gray-600 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="/manager/approvals" class="hover:text-gray-600 transition-colors">Review Permit</a>
            <span>/</span>
            <span class="font-semibold text-gray-700">{{ $permit->no_permit }}</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="/manager/approvals"
               class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:border-gray-300 transition-colors">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Header kartu --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5 mb-5 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xs text-gray-400 mb-1">Nomor Permit</p>
            <p class="text-xl font-bold text-gray-800">{{ $permit->no_permit }}</p>
            <p class="text-xs text-gray-500 mt-1">Divisi: <span class="font-semibold text-gray-700">{{ optional($permit->user)->name ?? '—' }}</span></p>
        </div>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $badge }}">
            {{ $permit->status }}
        </span>
    </div>

    @if($permit->status === 'Revision' && $permit->catatan_revisi)
    <div class="bg-red-50 border border-red-200 rounded-2xl px-6 py-4 mb-5 flex gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-red-700">Catatan Revisi</p>
            <p class="text-sm text-red-600 mt-1">{{ $permit->catatan_revisi }}</p>
        </div>
    </div>
    @endif

    {{-- Grid detail --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- A. Klasifikasi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">A</span>
                <h3 class="text-sm font-semibold text-gray-800 mt-0.5">Klasifikasi Pekerjaan</h3>
            </div>
            <div class="px-6 py-4">
                @if($permit->klasifikasi_pekerjaan && count($permit->klasifikasi_pekerjaan))
                    <div class="flex flex-wrap gap-2">
                        @foreach($permit->klasifikasi_pekerjaan as $k)
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg capitalize">{{ str_replace('_', ' ', $k) }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Tidak ada klasifikasi dipilih.</p>
                @endif
            </div>
        </div>

        {{-- B. Informasi Pekerjaan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">B</span>
                <h3 class="text-sm font-semibold text-gray-800 mt-0.5">Informasi Pekerjaan</h3>
            </div>
            <div class="px-6 py-4 space-y-3 text-sm">
                @php
                    $info = [
                        'Nama Pekerjaan'    => $permit->nama_pekerjaan,
                        'Lokasi'            => $permit->lokasi,
                        'Kontraktor'        => $permit->kontraktor,
                        'Penanggung Jawab'  => $permit->penanggung_jawab,
                        'No. Telepon'       => $permit->telepon,
                        'Tanggal Mulai'     => $permit->tanggal_mulai?->format('d/m/Y'),
                        'Tanggal Selesai'   => $permit->tanggal_selesai?->format('d/m/Y'),
                    ];
                @endphp
                @foreach($info as $label => $value)
                <div class="flex gap-3">
                    <span class="text-xs text-gray-400 w-36 shrink-0 pt-0.5">{{ $label }}</span>
                    <span class="text-gray-700 font-medium">{{ $value ?: '—' }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- C. Bahaya Pekerjaan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">C</span>
                <h3 class="text-sm font-semibold text-gray-800 mt-0.5">Bahaya Pekerjaan</h3>
            </div>
            <div class="px-6 py-4">
                @if($permit->bahaya_pekerjaan && count($permit->bahaya_pekerjaan))
                    <div class="flex flex-wrap gap-2">
                        @foreach($permit->bahaya_pekerjaan as $b)
                            <span class="px-2.5 py-1 bg-orange-50 text-orange-700 text-xs font-semibold rounded-lg capitalize">{{ str_replace('_', ' ', $b) }}</span>
                        @endforeach
                    </div>
                    @if($permit->bahaya_lainnya)
                        <p class="text-xs text-gray-500 mt-2">Lainnya: {{ $permit->bahaya_lainnya }}</p>
                    @endif
                @else
                    <p class="text-sm text-gray-400 italic">Tidak ada bahaya dipilih.</p>
                @endif
            </div>
        </div>

        {{-- D. Tindakan Pencegahan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">D</span>
                <h3 class="text-sm font-semibold text-gray-800 mt-0.5">Tindakan Pencegahan</h3>
            </div>
            <div class="px-6 py-4">
                @if($permit->tindakan_pencegahan && count($permit->tindakan_pencegahan))
                    <div class="flex flex-wrap gap-2">
                        @foreach($permit->tindakan_pencegahan as $t)
                            <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-lg capitalize">{{ str_replace('_', ' ', $t) }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Tidak ada tindakan pencegahan dipilih.</p>
                @endif
            </div>
        </div>

        {{-- E. APD --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm md:col-span-2">
            <div class="px-6 py-4 border-b border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">E</span>
                <h3 class="text-sm font-semibold text-gray-800 mt-0.5">Alat Pelindung Diri (APD)</h3>
            </div>
            <div class="px-6 py-4">
                @if($permit->apd && count($permit->apd))
                    <div class="flex flex-wrap gap-2">
                        @foreach($permit->apd as $a)
                            <span class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-lg capitalize">{{ str_replace('_', ' ', $a) }}</span>
                        @endforeach
                    </div>
                    @if($permit->apd_lainnya)
                        <p class="text-xs text-gray-500 mt-2">Lainnya: {{ $permit->apd_lainnya }}</p>
                    @endif
                @else
                    <p class="text-sm text-gray-400 italic">Tidak ada APD dipilih.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- Timeline status --}}
    <div class="mt-5 bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Informasi Waktu</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400">Dibuat</p>
                <p class="font-semibold text-gray-700 mt-1">{{ $permit->created_at->format('d/m/Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Diajukan</p>
                <p class="font-semibold text-gray-700 mt-1">{{ $permit->submitted_at ? $permit->submitted_at->format('d/m/Y, H:i') : '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Ditutup</p>
                <p class="font-semibold text-gray-700 mt-1">{{ $permit->closed_at ? $permit->closed_at->format('d/m/Y, H:i') : '—' }}</p>
            </div>
        </div>
    </div>

    @if($canReview)
    {{-- Aksi Review Manager --}}
    <div class="mt-5 bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Tindak Lanjut Review (Manager)</h3>
        
        <form action="/manager/approvals/{{ $permit->id }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Catatan Revisi (Hanya jika perlu perbaikan)</label>
                <textarea name="catatan_revisi" rows="3" class="w-full form-input text-sm rounded-xl border-gray-200 focus:border-inka-navy focus:ring-inka-navy/20" placeholder="Opsional jika menyetujui. Wajib jika meminta revisi..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" name="action" value="approve" class="px-6 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors">
                    Setujui & Lanjutkan ke Senior Manager
                </button>
                <button type="submit" name="action" value="revise" class="px-6 py-2.5 bg-red-100 text-red-700 text-sm font-semibold rounded-xl hover:bg-red-200 transition-colors">
                    Kembalikan ke Divisi (Revisi)
                </button>
            </div>
        </form>
    </div>
    @endif

</x-app-layout>
