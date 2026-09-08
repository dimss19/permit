<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Permit INKA Madiun — Sistem Monitoring K3 Kontraktor</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --navy-950: #0d1526;
            --navy-900: #122040;
            --navy-800: #1a2c52;
            --navy-700: #243a68;
            --steel-500: #5b6b8c;
            --steel-300: #a7b1c9;
            --paper: #f5f6f9;
            --card: #ffffff;
            --ink-900: #141a2b;
            --ink-600: #4b5470;
            --amber-500: #f5a623;
            --amber-600: #d9861a;
            --amber-100: #fdf1da;
            --teal-500: #1f9d8a;
            --border: #e4e7ef;
        }

        html { scroll-behavior: smooth; }

        [x-cloak] { display: none !important; }

        ::selection { background: var(--amber-500); color: var(--navy-950); }

        .nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .hero {
            position: relative;
            background: linear-gradient(180deg, rgba(13,21,38,.94), rgba(18,32,64,.97)), var(--navy-950);
            color: #fff; overflow: hidden; padding: 86px 0; text-align: center;
        }

        .hero::before {
            content: ''; position: absolute; inset: 0;
            background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 30%, #000 40%, transparent 90%);
            -webkit-mask-image: radial-gradient(ellipse 70% 70% at 50% 30%, #000 40%, transparent 90%);
            pointer-events: none;
        }

        .hero-inner { position: relative; z-index: 1; }

        .eyebrow {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(245,166,35,.13); border: 1px solid rgba(245,166,35,.35);
            color: var(--amber-500); font-size: 12px; font-weight: 600;
            padding: 6px 14px; border-radius: 20px; margin-bottom: 20px;
            letter-spacing: .02em;
        }

        .eyebrow .dot {
            width: 6px; height: 6px; border-radius: 50%; background: var(--teal-500);
            box-shadow: 0 0 0 3px rgba(31,157,138,.25);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 3px rgba(31,157,138,.25); }
            50% { box-shadow: 0 0 0 6px rgba(31,157,138,.1); }
        }

        .sec-tag {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 11.5px; font-weight: 700; color: var(--amber-600);
            text-transform: uppercase; letter-spacing: .06em; margin-bottom: 12px;
        }

        .sec-tag::before {
            content: ''; width: 16px; height: 2px; background: var(--amber-500);
        }

        .flow { position: relative; }

        .flow-line {
            position: absolute; top: 24px; left: 9%; right: 9%; height: 2px;
            background: repeating-linear-gradient(90deg, var(--border) 0 8px, transparent 8px 14px);
            z-index: 0; 
        }

        .flow-step { text-align: center; }

        .flow-step .sn {
            width: 48px; height: 48px; border-radius: 50%; background: var(--navy-900); color: #fff;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;
            font-weight: 700; font-size: 16px;
            border: 4px solid var(--paper);
            transition: transform .3s cubic-bezier(.16,1,.3,1), background .3s ease;
        }

        .flow-step:hover .sn {
            transform: scale(1.1);
        }

        .flow-step:nth-child(5) .sn { background: var(--amber-500); color: var(--navy-950); }

        .flow-step h4 { font-size: 13.5px; font-weight: 700; margin-bottom: 7px; color: var(--navy-950); }
        .flow-step p { font-size: 12px; color: var(--ink-600); line-height: 1.5; }

        .flow-note {
            display: flex; gap: 12px; align-items: flex-start;
            background: var(--amber-100); border: 1px solid #f0d090;
            border-radius: 11px; padding: 16px 20px; margin-top: 44px;
            font-size: 13px; color: #7a4d0d; line-height: 1.6;
        }

        .flow-note strong { color: #5a3a08; }

        .about-visual {
            background: var(--paper); border: 1px solid var(--border); border-radius: 16px;
            padding: 32px; position: relative; overflow: hidden;
        }

        .about-visual::before {
            content: ''; position: absolute; top: -30px; right: -30px; width: 150px; height: 150px;
            background: repeating-linear-gradient(135deg, var(--amber-500) 0 12px, transparent 12px 24px);
            opacity: .12;
        }

        .about-stat {
            background: #fff; border: 1px solid var(--border); border-radius: 11px; padding: 18px;
            transition: transform .3s cubic-bezier(.16,1,.3,1), box-shadow .3s ease;
        }

        .about-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.06);
        }

        .about-stat .v { font-weight: 700; font-size: 26px; color: var(--navy-950); }
        .about-stat .l { font-size: 11.5px; color: var(--ink-600); margin-top: 4px; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            border-radius: 9px; padding: 10px 18px; font-size: 13.5px; font-weight: 600;
            cursor: pointer; border: 1.5px solid transparent; transition: all .2s;
            font-family: inherit;
        }

        .btn-ghost { color: var(--navy-950); border-color: var(--border); background: #fff; }
        .btn-ghost:hover { border-color: var(--navy-700); }
        .btn-solid { background: var(--navy-900); color: #fff; }
        .btn-solid:hover { background: var(--navy-800); }
        .btn-amber { background: var(--amber-500); color: var(--navy-950); }
        .btn-amber:hover { background: var(--amber-600); transform: translateY(-1px); }
        .btn-lg { padding: 13px 24px; font-size: 14.5px; }

        .btn-outline-light {
            background: rgba(255,255,255,.06); border-color: rgba(255,255,255,.18); color: #fff;
        }
        .btn-outline-light:hover { background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.3); }

        /* Scroll Animations */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: all .7s cubic-bezier(.16,1,.3,1);
        }
        .reveal.is-visible {
            opacity: 1; transform: translateY(0);
        }
        .reveal-left {
            opacity: 0; transform: translateX(-30px);
            transition: all .7s cubic-bezier(.16,1,.3,1);
        }
        .reveal-left.is-visible {
            opacity: 1; transform: translateX(0);
        }
        .reveal-right {
            opacity: 0; transform: translateX(30px);
            transition: all .7s cubic-bezier(.16,1,.3,1);
        }
        .reveal-right.is-visible {
            opacity: 1; transform: translateX(0);
        }
        .reveal-scale {
            opacity: 0; transform: scale(.92);
            transition: all .7s cubic-bezier(.16,1,.3,1);
        }
        .reveal-scale.is-visible {
            opacity: 1; transform: scale(1);
        }

        /* Hero entrance animations */
        .hero-animate {
            opacity: 0; transform: translateY(20px);
        }
        .hero-visible {
            animation: heroFadeUp .6s cubic-bezier(.16,1,.3,1) forwards;
        }
        @keyframes heroFadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 860px) {
            .flow-grid { grid-template-columns: repeat(2,1fr); gap: 28px; }
            .flow-line { display: none; }
            .about-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 560px) {
            .flow-grid { grid-template-columns: 1fr; }
            .about-stats { grid-template-columns: 1fr 1fr; }
        }

        /* Staggered animation delays for flow steps */
        .flow-step:nth-child(1) .sn { transition-delay: 0ms; }
        .flow-step:nth-child(2) .sn { transition-delay: 80ms; }
        .flow-step:nth-child(3) .sn { transition-delay: 160ms; }
        .flow-step:nth-child(4) .sn { transition-delay: 240ms; }
        .flow-step:nth-child(5) .sn { transition-delay: 320ms; }
    </style>
</head>
<body class="bg-[var(--paper)] text-[var(--ink-900)]">

<!-- ================= HEADER ================= -->
<header class="nav">
    <div class="max-w-[1080px] mx-auto px-6 flex items-center h-[68px] gap-5">
        <div class="flex items-center gap-3 mr-auto">
            <img src="{{ asset('assets/images/logoinka.svg') }}" alt="INKA" class="h-7 w-auto">
            <div class="border-l border-[var(--border)] pl-3">
                <span class="text-sm font-bold">Safety Permit</span>
                <span class="block text-[10px] font-semibold text-[var(--ink-600)] tracking-wide">INKA MADIUN</span>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-6 mr-4 text-sm font-semibold text-[var(--ink-600)]">
            <a href="#monitoring" class="hover:text-[var(--navy-950)] transition-colors flex items-center gap-1.5 text-inka-navy font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Monitoring Permit
            </a>
            <a href="#alur" class="hover:text-[var(--navy-950)] transition-colors">Alur Pengajuan</a>
            <a href="#tentang" class="hover:text-[var(--navy-950)] transition-colors">Tentang Kami</a>
        </div>
        <div class="flex items-center gap-2.5">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-solid">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-solid">Masuk</a>
            @endauth
        </div>
    </div>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="max-w-[660px] mx-auto px-6 hero-inner">
        <div class="eyebrow hero-animate" id="hero-eyebrow"><span class="dot"></span> SISTEM ONLINE · MONITORING REAL-TIME</div>
        <h1 class="text-[38px] md:text-[42px] leading-[1.2] font-extrabold tracking-[-.01em] mb-4 hero-animate" id="hero-title">
            Kelola Izin Kerja Risiko Tinggi <span class="text-[var(--amber-500)]">Kontraktor Anda</span> dengan Lebih Aman
        </h1>
        <p class="text-[15.5px] text-[var(--steel-300)] mb-8 hero-animate" id="hero-sub">
            Safety Permit INKA Madiun membantu setiap divisi mengelola data kontraktor, mengajukan, memverifikasi, dan memantau izin kerja risiko tinggi secara digital — cepat, tercatat rapi, dan sesuai standar K3.
        </p>
        <div class="flex gap-3 justify-center flex-wrap hero-animate" id="hero-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-amber btn-lg">
                    Ke Dashboard
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-amber btn-lg">
                    Masuk
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            @endauth
            <a href="#monitoring" class="btn btn-outline-light btn-lg">
                Monitoring Permit
            </a>
            <a href="#alur" class="btn btn-outline-light btn-lg">
                Alur Pengajuan
            </a>
        </div>
        <p class="text-xs text-[var(--steel-300)] mt-[18px] hero-animate" id="hero-note">
            Untuk pegawai/admin divisi INKA. Kontraktor yang ingin bekerja sama tidak mendaftar sendiri — silakan hubungi divisi terkait di INKA Madiun.
        </p>
    </div>
</section>

<!-- ================= MONITORING PERMIT TERKINI ================= -->
<section class="py-20 px-6 bg-[var(--paper)]" id="monitoring" x-data="{
    isModalOpen: false,
    activePermit: {
        no_permit: '',
        tipe: '',
        nama_pekerjaan: '',
        divisi: '',
        kontraktor: '',
        lokasi: '',
        tanggal_permit: '',
        penanggung_jawab: '',
        status: '',
        status_badge: '',
        klasifikasi: []
    },
    openModal(data) {
        this.activePermit = data;
        this.isModalOpen = true;
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.isModalOpen = false;
        document.body.style.overflow = '';
    }
}">
    <div class="max-w-[1140px] mx-auto">
        <div class="max-w-[640px] mx-auto text-center mb-10 reveal">
            <div class="sec-tag inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Monitoring Real-Time
            </div>
            <h2 class="text-[28px] font-extrabold tracking-[-.01em] mb-3 text-[var(--navy-950)]">
                Monitoring Izin Kerja (Safety Permit)
            </h2>
            <p class="text-[14.5px] text-[var(--ink-600)]">
                Pantau daftar izin kerja risiko tinggi di lingkungan PT INKA (Persero) Madiun secara transparan dan mudah.
            </p>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-[var(--border)] shadow-sm overflow-hidden reveal">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-inka-navy border border-blue-100 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-inka-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Daftar Permit Kerja Terkini</h3>
                        <p class="text-xs text-gray-500">Menampilkan 10 permit terbaru dengan update berkala</p>
                    </div>
                </div>
                <div class="text-xs font-semibold text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200">
                    Total: <span class="text-inka-navy font-bold">{{ $permits->total() }}</span> Permit Terdaftar
                </div>
            </div>

            @if($permits->isEmpty())
                <div class="py-16 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm font-semibold text-gray-600">Belum ada permit kerja yang terdaftar.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100 bg-gray-50/70">
                                <th class="px-5 py-3.5 font-semibold">No. Permit</th>
                                <th class="px-5 py-3.5 font-semibold">Tipe</th>
                                <th class="px-5 py-3.5 font-semibold">Klasifikasi Pekerjaan</th>
                                <th class="px-5 py-3.5 font-semibold">Divisi</th>
                                <th class="px-5 py-3.5 font-semibold">Kontraktor</th>
                                <th class="px-5 py-3.5 font-semibold">Lokasi</th>
                                <th class="px-5 py-3.5 font-semibold">Tanggal Permit</th>
                                <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($permits as $permit)
                            @php
                                $klasArr = [];
                                if ($permit->relationLoaded('classifications') && $permit->classifications->isNotEmpty()) {
                                    $klasArr = $permit->classifications->pluck('name')->toArray();
                                } elseif (!empty($permit->klasifikasi_pekerjaan) && is_array($permit->klasifikasi_pekerjaan)) {
                                    $nameMap = [
                                        'panas'          => 'Pekerjaan Panas',
                                        'ketinggian'     => 'Pekerjaan Ketinggian',
                                        'ruang_terbatas' => 'Ruang Terbatas',
                                        'galian'         => 'Pekerjaan Galian',
                                        'tegangan_tinggi'=> 'Pekerjaan Tegangan Tinggi',
                                        'radiasi'        => 'Radiasi',
                                    ];
                                    foreach ($permit->klasifikasi_pekerjaan as $k) {
                                        $klasArr[] = $nameMap[$k] ?? ucwords(str_replace('_', ' ', $k));
                                    }
                                }

                                $mulai = $permit->tanggal_mulai ? $permit->tanggal_mulai->format('d/m/Y') : null;
                                $selesai = $permit->tanggal_selesai ? $permit->tanggal_selesai->format('d/m/Y') : null;
                                if ($mulai && $selesai) {
                                    $tglDisplay = ($mulai === $selesai) ? $mulai : "$mulai – $selesai";
                                } elseif ($mulai) {
                                    $tglDisplay = $mulai;
                                } elseif ($selesai) {
                                    $tglDisplay = $selesai;
                                } elseif ($permit->submitted_at) {
                                    $tglDisplay = $permit->submitted_at->format('d/m/Y');
                                } else {
                                    $tglDisplay = '—';
                                }

                                $statusMap = [
                                    'Draft'                    => 'bg-gray-100 text-gray-600',
                                    'Submitted'                => 'bg-blue-100 text-blue-700',
                                    'Review Staff'             => 'bg-orange-100 text-orange-700',
                                    'Review Manager'           => 'bg-orange-100 text-orange-700',
                                    'Review Senior Manager'    => 'bg-orange-100 text-orange-700',
                                    'Revision'                 => 'bg-red-100 text-red-700',
                                    'Active'                   => 'bg-green-100 text-green-700',
                                    'Closed'                   => 'bg-slate-100 text-slate-600',
                                    'Cancelled'                => 'bg-red-600 text-white',
                                ];
                                $statusBadge = $statusMap[$permit->status] ?? 'bg-gray-100 text-gray-600';

                                $modalPayload = [
                                    'no_permit' => $permit->no_permit,
                                    'tipe' => $permit->tipe,
                                    'nama_pekerjaan' => $permit->nama_pekerjaan,
                                    'divisi' => optional($permit->user)->name ?? '—',
                                    'kontraktor' => $permit->kontraktor,
                                    'lokasi' => $permit->lokasi ?? '—',
                                    'tanggal_permit' => $tglDisplay,
                                    'penanggung_jawab' => $permit->penanggung_jawab ?? '—',
                                    'status' => $permit->status,
                                    'status_badge' => $statusBadge,
                                    'klasifikasi' => $klasArr,
                                ];
                            @endphp
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap font-bold text-inka-navy">
                                    {{ $permit->no_permit }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <x-permit-tipe-badge :tipe="$permit->tipe" />
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-permit-klasifikasi-badges :permit="$permit" />
                                </td>
                                <td class="px-5 py-3.5 font-medium text-gray-800 whitespace-nowrap">
                                    {{ optional($permit->user)->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 text-xs">
                                    {{ $permit->kontraktor }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 text-xs">
                                    {{ $permit->lokasi ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <x-permit-tanggal :permit="$permit" />
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <button type="button"
                                            @click="openModal({{ json_encode($modalPayload) }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-inka-navy text-white hover:opacity-90 transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($permits->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                        {{ $permits->fragment('monitoring')->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Modal Pratinjau Publik (Read-Only) -->
    <div x-show="isModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
         role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="isModalOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
             @click="closeModal()"></div>

        <!-- Dialog Window -->
        <div x-show="isModalOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-10 my-8">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/90 flex items-center justify-between">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="font-bold text-gray-900 text-lg" x-text="activePermit.no_permit"></span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700" x-text="activePermit.tipe"></span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                          :class="activePermit.status_badge || 'bg-gray-100 text-gray-600'"
                          x-text="activePermit.status"></span>
                </div>
                <button @click="closeModal()" class="w-8 h-8 rounded-lg hover:bg-gray-200/60 text-gray-400 hover:text-gray-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                <!-- Klasifikasi Badges -->
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Klasifikasi Pekerjaan</p>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="k in activePermit.klasifikasi" :key="k">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/70" x-text="k"></span>
                        </template>
                        <template x-if="!activePermit.klasifikasi || activePermit.klasifikasi.length === 0">
                            <span class="text-sm text-gray-400 italic">Tidak ada klasifikasi pekerjaan</span>
                        </template>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50/70 rounded-xl p-4 border border-gray-100 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Uraian / Nama Pekerjaan</p>
                        <p class="font-semibold text-gray-800 mt-0.5" x-text="activePermit.nama_pekerjaan || '—'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Divisi Pengaju</p>
                        <p class="font-semibold text-gray-800 mt-0.5" x-text="activePermit.divisi"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Kontraktor Pelaksana</p>
                        <p class="font-semibold text-gray-800 mt-0.5" x-text="activePermit.kontraktor"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Lokasi Pekerjaan</p>
                        <p class="font-semibold text-gray-800 mt-0.5" x-text="activePermit.lokasi"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Tanggal Permit (Periode Pengerjaan)</p>
                        <p class="font-semibold text-gray-800 mt-0.5" x-text="activePermit.tanggal_permit"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Penanggung Jawab Lapangan</p>
                        <p class="font-semibold text-gray-800 mt-0.5" x-text="activePermit.penanggung_jawab"></p>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200/70 rounded-xl p-3.5 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Data di atas adalah ringkasan monitoring publik. Dokumen teknis (JSA/HIRADC/APD) dan tanda tangan verifikasi dapat diakses oleh user berwenang melalui dashboard sistem.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
                <button type="button" @click="closeModal()" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-200/60 transition-colors">
                    Tutup
                </button>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-inka-navy text-white hover:opacity-90 transition-opacity">
                    <span>Login Sistem</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ================= ALUR PENGAJUAN ================= -->
<section class="py-20 px-6" id="alur">
    <div class="max-w-[1080px] mx-auto">
        <div class="max-w-[560px] mx-auto text-center mb-[46px] reveal">
            <div class="sec-tag">Alur Pengajuan</div>
            <h2 class="text-[28px] font-extrabold tracking-[-.01em] mb-3 text-[var(--navy-950)]">Bagaimana Kontraktor Bisa Bekerja Sama dengan INKA</h2>
            <p class="text-[14.5px] text-[var(--ink-600)]">Kontraktor tidak mendaftar sendiri — seluruh data dan pengajuan izin kerja diinput oleh divisi terkait melalui dashboard internal.</p>
        </div>

        <div class="flow">
            <div class="flow-line"></div>
            <div class="flow-grid grid grid-cols-1 md:grid-cols-5 gap-4 relative z-10">
                <div class="flow-step reveal" style="transition-delay:0ms">
                    <div class="sn">1</div>
                    <h4>Kontraktor Hubungi Divisi</h4>
                    <p>Kontraktor yang ingin bekerja sama menghubungi PIC divisi INKA yang bersangkutan.</p>
                </div>
                <div class="flow-step reveal" style="transition-delay:80ms">
                    <div class="sn">2</div>
                    <h4>Divisi Input Data Kontraktor</h4>
                    <p>Admin divisi menambahkan data perusahaan &amp; PIC kontraktor lewat dashboard.</p>
                </div>
                <div class="flow-step reveal" style="transition-delay:160ms">
                    <div class="sn">3</div>
                    <h4>Identifikasi Pekerjaan</h4>
                    <p>Klasifikasi pekerjaan, bahaya, serta APD didiskusikan bersama kontraktor.</p>
                </div>
                <div class="flow-step reveal" style="transition-delay:240ms">
                    <div class="sn">4</div>
                    <h4>Lengkapi Berkas K3</h4>
                    <p>Divisi melampirkan dokumen pendukung seperti HIRADC, JSA, atau HSE Plan.</p>
                </div>
                <div class="flow-step reveal" style="transition-delay:320ms">
                    <div class="sn">5</div>
                    <h4>Ajukan &amp; Verifikasi</h4>
                    <p>Divisi mengajukan izin kerja untuk diverifikasi Safety Officer sebelum pekerjaan dimulai.</p>
                </div>
            </div>
        </div>

        <div class="flow-note reveal-scale" style="transition-delay:200ms">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 mt-0.5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
            <span><strong>Penting:</strong> kontraktor tidak memiliki akun dan tidak mendaftar sendiri di sistem ini. Seluruh data kontraktor serta pengajuan izin kerja risiko tinggi diinput dan dikelola oleh divisi INKA yang bekerja sama dengannya.</span>
        </div>
    </div>
</section>

<!-- ================= TENTANG KAMI ================= -->
<section class="py-20 px-6 bg-white border-y border-[var(--border)]" id="tentang">
    <div class="max-w-[1080px] mx-auto about-grid grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="reveal-left">
            <div class="sec-tag">Tentang Kami</div>
            <h2 class="text-[26px] font-extrabold tracking-[-.01em] mb-[14px] text-[var(--navy-950)]">Dibangun untuk Tim HSE yang Ingin Bekerja Lebih Cepat dan Aman</h2>
            <p class="text-sm text-[var(--ink-600)] mb-3.5 leading-relaxed">Safety Permit INKA Madiun adalah sistem monitoring keselamatan kerja kontraktor yang menggantikan proses manual berbasis kertas dengan alur digital yang lebih rapi dan mudah diaudit.</p>
            <p class="text-sm text-[var(--ink-600)] mb-3.5 leading-relaxed">Kami percaya keselamatan kerja seharusnya tidak menghambat kecepatan operasional. Karena itu Safety Permit INKA Madiun dirancang agar divisi bisa mengelola data kontraktor dan mengajukan izin kerja dengan cepat, sementara tim HSE tetap punya kendali penuh atas setiap persetujuan.</p>
            <a href="#" class="btn btn-solid" style="margin-top:8px;">Pelajari Lebih Lanjut</a>
        </div>
        <div class="about-visual reveal-right">
            <div class="about-stats grid grid-cols-2 gap-4 relative">
                <div class="about-stat"><div class="v">42+</div><div class="l">Kontraktor aktif dipantau</div></div>
                <div class="about-stat"><div class="v">91%</div><div class="l">Skor kepatuhan K3</div></div>
                <div class="about-stat"><div class="v">1.240+</div><div class="l">Izin kerja diproses</div></div>
                <div class="about-stat"><div class="v">&lt;2 mnt</div><div class="l">Rata-rata waktu persetujuan</div></div>
            </div>
        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="bg-[var(--navy-950)] text-white px-6 pt-12 pb-6">
    <div class="max-w-[1080px] mx-auto">
        <div class="flex justify-between items-start flex-wrap gap-8 mb-8">
            <div>
                <div class="flex items-center gap-3" style="color:#fff;">
                    <img src="{{ asset('assets/images/logoinka.svg') }}" alt="INKA" class="h-7 w-auto brightness-0 invert opacity-80">
                    <div class="border-l border-white/20 pl-3">
                        <span class="text-sm font-bold">Safety Permit</span>
                        <span class="block text-[10px] font-semibold text-[var(--steel-300)] tracking-wide">INKA MADIUN</span>
                    </div>
                </div>
                <p class="text-[12.5px] text-[var(--steel-300)] max-w-[280px] mt-3">Platform digital untuk monitoring kontraktor dan izin kerja risiko tinggi dalam satu sistem terpadu.</p>
            </div>
            <div class="flex gap-12 flex-wrap">
                <div>
                    <h5 class="text-[11.5px] font-bold uppercase tracking-wide text-[var(--steel-300)] mb-3.5">Navigasi</h5>
                    <ul class="flex flex-col gap-2.5">
                        <li><a href="#monitoring" class="text-sm text-white/85 hover:text-white transition-opacity">Monitoring Permit</a></li>
                        <li><a href="#alur" class="text-sm text-white/85 hover:text-white transition-opacity">Alur Pengajuan</a></li>
                        <li><a href="#tentang" class="text-sm text-white/85 hover:text-white transition-opacity">Tentang Kami</a></li>
                        <li><a href="{{ route('login') }}" class="text-sm text-white/85 hover:text-white transition-opacity">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-[11.5px] font-bold uppercase tracking-wide text-[var(--steel-300)] mb-3.5">Kontak</h5>
                    <ul class="flex flex-col gap-2.5">
                        <li><a href="mailto:hse@inkamadiun.co.id" class="text-sm text-white/85 hover:text-white transition-opacity">hse@inkamadiun.co.id</a></li>
                        <li><a href="tel:+622112345678" class="text-sm text-white/85 hover:text-white transition-opacity">(021) 1234-5678</a></li>
                        <li><span class="text-sm text-white/85">Madiun, Indonesia</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-white/12 pt-5 flex justify-between items-center flex-wrap gap-2.5 text-xs text-[var(--steel-300)]">
            <span>&copy; 2026 Safety Permit INKA Madiun. Seluruh hak cipta dilindungi.</span>
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logok3.png') }}" alt="Logo K3" class="h-8 w-auto opacity-70">
                <span>SHE PT. INKA (Persero) Madiun</span>
            </div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hero entrance animation with staggered delays
        const heroEls = document.querySelectorAll('.hero-animate');
        heroEls.forEach((el, i) => {
            setTimeout(() => {
                el.classList.add('hero-visible');
            }, 200 + i * 150);
        });

        // Scroll reveal animations
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.15
        });

        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
            revealObserver.observe(el);
        });
    });
</script>

</body>
</html>