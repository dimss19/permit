<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Permit INKA Madiun — Sistem Izin Kerja Digital</title>
    <meta name="description" content="Safety Permit INKA Madiun membantu tim HSE dan kontraktor mengajukan, memverifikasi, dan memantau izin kerja risiko tinggi secara digital.">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #111d33;
            --navy-light: #1a2b4a;
            --orange: #f2994a;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #fff; color: #111827; }
        .bg-navy { background-color: var(--navy); }
        .bg-navy-light { background-color: var(--navy-light); }
        .text-orange { color: var(--orange); }
        .bg-orange { background-color: var(--orange); }
        .border-orange { border-color: var(--orange); }

        /* Sticky nav */
        #main-nav { position: sticky; top: 0; z-index: 50; }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* FAQ */
        .faq-body { display: none; }
        .faq-body.open { display: block; }
        .faq-icon { transition: transform .2s; }
        .faq-icon.open { transform: rotate(45deg); }

        /* Fade in animations */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<!-- ===================== NAV ===================== -->
<nav id="main-nav" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 md:px-10 h-16 flex items-center justify-between">

        <!-- Logo + Nama -->
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('assets/images/logoinka.svg') }}" alt="Logo PT INKA" class="h-8 w-auto">
            <div class="border-l border-gray-200 pl-3 hidden sm:block">
                <p class="text-xs font-bold text-gray-800 leading-tight tracking-tight">SAFETY PERMIT INKA MADIUN</p>
                <p class="text-[10px] text-gray-400 tracking-wide">SHE PT. INKA (Persero) Madiun</p>
            </div>
        </a>

        <!-- Nav links -->
        <div class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-500">
            <a href="#tentang" class="hover:text-gray-900 transition-colors">Tentang</a>
            <a href="#alur" class="hover:text-gray-900 transition-colors">Alur Pengajuan</a>
            <a href="#fitur" class="hover:text-gray-900 transition-colors">Fitur</a>
            <a href="#keunggulan" class="hover:text-gray-900 transition-colors">Keunggulan</a>
            <a href="#faq" class="hover:text-gray-900 transition-colors">FAQ</a>
        </div>

        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-navy hover:opacity-90 transition-opacity" style="background-color: var(--navy)">
            Masuk
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</nav>

<!-- ===================== HERO ===================== -->
<section class="bg-navy text-white py-20 px-6 md:px-10 overflow-hidden" style="background-color: var(--navy)">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

        <!-- Kiri -->
        <div class="fade-in">
            <span class="inline-block text-xs font-bold tracking-widest uppercase text-orange mb-5 opacity-80">— Sistem Izin Kerja Digital</span>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-6">
                Kelola Izin Kerja<br>
                Risiko Tinggi <span class="text-orange">Lebih Cepat</span><br>dan Lebih Aman
            </h1>
            <p class="text-gray-300 text-sm md:text-base leading-relaxed max-w-lg mb-8 opacity-80">
                Safety Permit INKA Madiun menggantikan proses manual berbasis kertas dengan alur digital yang terstruktur — dari pengajuan, approval berjenjang, hingga monitoring real-time.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-xl font-bold text-sm text-navy bg-orange hover:brightness-110 transition-all" style="color:var(--navy);background-color:var(--orange)">
                    Masuk Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#alur" class="inline-flex items-center justify-center gap-2 px-7 py-3 rounded-xl font-bold text-sm text-white border border-white/20 hover:bg-white/10 transition-all">
                    Lihat Panduan
                </a>
            </div>
        </div>

        <!-- Kanan — Mockup Dashboard -->
        <div class="fade-in">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-1 shadow-2xl">
                <div class="bg-white rounded-xl overflow-hidden">
                    <!-- Mock browser bar -->
                    <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="flex-1 bg-white rounded-md h-5 mx-4 flex items-center px-3">
                            <span class="text-[10px] text-gray-400">safetypermit.inka.co.id/dashboard</span>
                        </div>
                    </div>
                    <!-- Mock Dashboard UI -->
                    <div class="p-4 bg-slate-50">
                        <!-- Header bar -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-gray-200"></div>
                                <div class="h-3 w-20 bg-gray-200 rounded"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-gray-200"></div>
                                <div class="h-2.5 w-16 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                        <!-- Stats cards -->
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            @foreach(['Draft','Submitted','Active','Closed'] as $s)
                            <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                                <p class="text-[8px] text-gray-400 uppercase">{{ $s }}</p>
                                <p class="text-lg font-bold text-gray-700 mt-1">{{ rand(1,9) }}</p>
                            </div>
                            @endforeach
                        </div>
                        <!-- Table rows mock -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="h-2.5 w-24 bg-gray-200 rounded"></div>
                                <div class="h-5 w-14 bg-green-100 rounded-full"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="h-2.5 w-36 bg-gray-200 rounded"></div>
                                <div class="h-5 w-14 bg-yellow-100 rounded-full"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="h-2.5 w-28 bg-gray-200 rounded"></div>
                                <div class="h-5 w-14 bg-slate-100 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== ALUR PENGAJUAN ===================== -->
<section id="alur" class="py-20 px-6 md:px-10 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14 fade-in">
            <span class="text-orange text-xs font-bold tracking-widest uppercase">— ALUR PENGAJUAN</span>
            <h2 class="text-3xl font-extrabold mt-3 mb-3 text-gray-900">Empat Langkah Mudah</h2>
            <p class="text-gray-500 text-sm max-w-lg mx-auto">Dari login hingga permit aktif — seluruh proses dapat diselesaikan secara digital tanpa perlu kertas.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12 fade-in">
            @php
                $steps = [
                    ['icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1', 'num' => '1', 'title' => 'Login', 'desc' => 'Masuk menggunakan akun Divisi yang telah dibuat oleh Super Admin.'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'num' => '2', 'title' => 'Buat Permit', 'desc' => 'Isi formulir izin kerja secara digital: klasifikasi, bahaya, APD, dan dokumen pendukung.'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'num' => '3', 'title' => 'Approval', 'desc' => 'Permit diverifikasi secara berjenjang: Staff → Manager → Senior Manager.'],
                    ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'num' => '4', 'title' => 'Permit Aktif', 'desc' => 'Setelah disetujui, permit berstatus Active dan pekerjaan dapat dimulai.'],
                ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="relative flex flex-col items-center text-center">
                @if($i < 3)
                <div class="hidden md:block absolute top-6 left-1/2 w-full h-px border-t-2 border-dashed border-gray-300"></div>
                @endif
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-5 z-10 shadow-sm {{ $i === 3 ? 'bg-orange' : 'bg-navy' }}" style="{{ $i === 3 ? 'background-color:var(--orange)' : 'background-color:var(--navy)' }}">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Langkah {{ $step['num'] }}</span>
                <h3 class="font-bold text-gray-800 mb-2">{{ $step['title'] }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- Warning box -->
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 flex items-start gap-3 max-w-4xl mx-auto fade-in">
            <svg class="w-5 h-5 text-orange shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--orange)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm text-orange-800"><strong>Penting:</strong> Pastikan seluruh data dan dokumen K3 sudah lengkap sebelum permit diajukan agar proses verifikasi tidak tertunda.</p>
        </div>
    </div>
</section>

<!-- ===================== FITUR UTAMA ===================== -->
<section id="fitur" class="py-20 px-6 md:px-10 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14 fade-in">
            <span class="text-orange text-xs font-bold tracking-widest uppercase">— FITUR SISTEM</span>
            <h2 class="text-3xl font-extrabold mt-3 mb-3 text-gray-900">Semua yang Anda Butuhkan</h2>
            <p class="text-gray-500 text-sm max-w-lg mx-auto">Dirancang khusus untuk kebutuhan pengelolaan izin kerja berisiko tinggi di lingkungan industri.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 fade-in">
            @php
                $features = [
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Pengajuan Permit', 'desc' => 'Formulir digital 5 langkah mengikuti standar Surat Izin Pekerjaan Beresiko Tinggi.', 'color' => 'bg-blue-50 text-blue-600'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Approval Berjenjang', 'desc' => 'Alur persetujuan Staff → Manager → Senior Manager sesuai business process.', 'color' => 'bg-green-50 text-green-600'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Riwayat Permit', 'desc' => 'Seluruh history permit tersimpan terpusat, dapat dicari dan difilter kapan saja.', 'color' => 'bg-purple-50 text-purple-600'],
                    ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Email Notifikasi', 'desc' => 'Notifikasi otomatis ke Staff HSE saat ada permit baru yang perlu diverifikasi.', 'color' => 'bg-orange-50 text-orange-600'],
                ];
            @endphp
            @foreach($features as $f)
            <div class="bg-slate-50 rounded-2xl p-6 border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all">
                <div class="w-11 h-11 rounded-xl {{ $f['color'] }} flex items-center justify-center mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ $f['title'] }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== TENTANG + STATS ===================== -->
<section id="tentang" class="py-20 px-6 md:px-10 bg-slate-50">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        <!-- Kiri -->
        <div class="fade-in">
            <span class="text-orange text-xs font-bold tracking-widest uppercase">— TENTANG KAMI</span>
            <h2 class="text-3xl font-extrabold mt-3 mb-5 leading-tight text-gray-900">Dibangun untuk Tim HSE yang Ingin Bekerja Lebih Efisien</h2>
            <div class="space-y-4 text-gray-500 text-sm leading-relaxed mb-8">
                <p>Safety Permit INKA Madiun adalah sistem monitoring keselamatan kerja kontraktor yang menggantikan proses manual berbasis kertas dengan alur digital yang rapi, terstruktur, dan mudah diaudit.</p>
                <p>Kami percaya keselamatan kerja seharusnya tidak menghambat kecepatan operasional. Karena itu sistem ini dirancang agar kontraktor dapat mengajukan izin kerja dengan cepat, sementara tim HSE tetap memiliki kendali penuh atas setiap persetujuan.</p>
            </div>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-xl font-bold text-sm text-white bg-navy hover:opacity-90 transition-opacity" style="background-color:var(--navy)">
                Mulai Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <!-- Kanan — Stats -->
        <div class="grid grid-cols-2 gap-4 fade-in">
            @php
                $stats = [
                    ['value' => '40+',    'label' => 'Kontraktor Aktif Dipantau'],
                    ['value' => '90%',    'label' => 'Skor Kepatuhan K3'],
                    ['value' => '1.000+', 'label' => 'Izin Kerja Diproses'],
                    ['value' => '< 2 mnt','label' => 'Rata-rata Waktu Persetujuan'],
                ];
            @endphp
            @foreach($stats as $s)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <p class="text-3xl font-extrabold text-gray-800">{{ $s['value'] }}</p>
                <p class="text-xs text-gray-400 uppercase font-medium mt-1 leading-tight">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== KEUNGGULAN ===================== -->
<section id="keunggulan" class="py-20 px-6 md:px-10 bg-navy text-white" style="background-color:var(--navy)">
    <div class="max-w-5xl mx-auto text-center fade-in">
        <span class="text-orange text-xs font-bold tracking-widest uppercase">— KEUNGGULAN SISTEM</span>
        <h2 class="text-3xl font-extrabold mt-3 mb-14 text-white">Mengapa Memilih Safety Permit INKA?</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
                $keunggulan = [
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Cepat', 'desc' => 'Pengajuan selesai dalam hitungan menit, bukan hari.'],
                    ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'Mudah', 'desc' => 'Dirancang untuk pengguna non-teknis tanpa pelatihan khusus.'],
                    ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H4a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-1', 'title' => 'Digital', 'desc' => 'Tidak ada lagi formulir kertas yang bisa hilang atau rusak.'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Terdokumentasi', 'desc' => 'Riwayat lengkap tersimpan dan siap diaudit kapan saja.'],
                ];
            @endphp
            @foreach($keunggulan as $k)
            <div class="flex flex-col items-center text-center p-5 rounded-2xl bg-white/5 border border-white/10">
                <div class="w-12 h-12 rounded-2xl bg-orange/10 flex items-center justify-center mb-4" style="background-color:rgba(242,153,74,0.15)">
                    <svg class="w-6 h-6 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--orange)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $k['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-bold text-white mb-2">{{ $k['title'] }}</h3>
                <p class="text-xs text-gray-400 leading-relaxed">{{ $k['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== FAQ ===================== -->
<section id="faq" class="py-20 px-6 md:px-10 bg-white">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-12 fade-in">
            <span class="text-orange text-xs font-bold tracking-widest uppercase">— FAQ</span>
            <h2 class="text-3xl font-extrabold mt-3 text-gray-900">Pertanyaan yang Sering Diajukan</h2>
        </div>

        <div class="space-y-3 fade-in">
            @php
                $faqs = [
                    ['q' => 'Siapa yang dapat menggunakan sistem ini?', 'a' => 'Sistem ini digunakan oleh tim Divisi sebagai pengaju permit, Staff HSE, Manager, dan Senior Manager sebagai approver, serta Super Admin untuk mengelola akun pengguna.'],
                    ['q' => 'Apakah kontraktor memiliki akun sendiri?', 'a' => 'Tidak. Kontraktor menggunakan akun Divisi yang telah dibuat oleh Super Admin untuk mengajukan Work Permit.'],
                    ['q' => 'Berapa lama proses approval berlangsung?', 'a' => 'Seluruh proses approval dilakukan secara digital sehingga jauh lebih cepat dari proses manual. Rata-rata waktu persetujuan kurang dari 2 menit setelah approver menerima notifikasi.'],
                    ['q' => 'Apakah data permit saya aman?', 'a' => 'Ya. Seluruh data tersimpan di server yang aman. Akses dibatasi berdasarkan role, dan setiap pengguna hanya dapat melihat data sesuai hak aksesnya.'],
                    ['q' => 'Dokumen apa saja yang dapat diunggah?', 'a' => 'Sistem menerima file dalam format PDF, JPG, JPEG, dan PNG untuk dokumen pendukung seperti HIRADC, JSA, Sertifikat Kompetensi, dan Foto Pendukung.'],
                    ['q' => 'Apakah ada notifikasi saat ada permit baru?', 'a' => 'Ya. Sistem secara otomatis mengirimkan notifikasi email kepada Staff HSE ketika ada permit baru yang diajukan dan menunggu review.'],
                ];
            @endphp
            @foreach($faqs as $i => $faq)
            <div class="border border-gray-100 rounded-2xl overflow-hidden bg-white shadow-sm">
                <button type="button" onclick="toggleFaq({{ $i }})" class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                    <span class="text-sm font-semibold text-gray-800 pr-6">{{ $faq['q'] }}</span>
                    <svg id="faq-icon-{{ $i }}" class="faq-icon w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
                <div id="faq-body-{{ $i }}" class="faq-body px-5 pb-4">
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="py-14 px-6 bg-slate-50">
    <div class="max-w-xl mx-auto text-center fade-in">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-3">Siap Memulai?</h2>
        <p class="text-sm text-gray-500 mb-7">Masuk sekarang dan mulai kelola izin kerja secara digital bersama tim HSE Anda.</p>
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-bold text-sm text-white bg-navy hover:opacity-90 transition-opacity" style="background-color:var(--navy)">
            Masuk ke Sistem
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>

<!-- ===================== FOOTER ===================== -->
<footer class="bg-navy text-white py-10 px-6 md:px-10" style="background-color:var(--navy)">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">

        <div class="flex items-center gap-4">
            <img src="{{ asset('assets/images/logoinka.svg') }}" alt="Logo PT INKA" class="h-8 w-auto brightness-0 invert">
            <div class="border-l border-white/20 pl-4">
                <p class="font-bold text-sm text-white">PT INKA (Persero) MADIUN</p>
                <p class="text-[11px] text-gray-400 mt-0.5">© {{ date('Y') }} SAFETY PERMIT INKA MADIUN · SHE PT. INKA (Persero) Madiun. All rights reserved.</p>
            </div>
        </div>

        <div class="text-center md:text-right">
            <p class="text-[11px] text-gray-400">Versi Sistem 1.0.0</p>
            <a href="mailto:she@inka.co.id" class="text-[11px] text-gray-400 hover:text-white transition-colors">she@inka.co.id</a>
        </div>
    </div>
</footer>

<script>
    // FAQ toggle
    function toggleFaq(i) {
        const body = document.getElementById('faq-body-' + i);
        const icon = document.getElementById('faq-icon-' + i);
        body.classList.toggle('open');
        icon.classList.toggle('open');
    }

    // Scroll fade-in
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>

</body>
</html>
