<x-app-layout>
    <x-slot name="title">Buat Permit Baru</x-slot>

    {{-- Step indicator --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 mb-6">
        <div class="flex items-center justify-between" id="step-indicator">
            @php
                $steps = [
                    1 => 'Klasifikasi & Info',
                    2 => 'Bahaya & Pencegahan',
                    3 => 'APD',
                    4 => 'Validasi Kerja',
                    5 => 'Review & Submit',
                ];
            @endphp
            @foreach($steps as $num => $label)
                <div class="flex items-center {{ $num < 5 ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div class="step-circle w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-200
                            {{ $num === 1 ? 'bg-inka-navy text-white border-inka-navy' : 'bg-white text-gray-400 border-gray-200' }}"
                            id="step-circle-{{ $num }}">
                            {{ $num }}
                        </div>
                        <span class="text-xs mt-1 font-medium text-center leading-tight
                            {{ $num === 1 ? 'text-inka-navy' : 'text-gray-400' }}"
                            id="step-label-{{ $num }}">
                            {{ $label }}
                        </span>
                    </div>
                    @if($num < 5)
                        <div class="flex-1 h-px mx-2 mt-[-12px] step-line bg-gray-200 transition-colors duration-200" id="step-line-{{ $num }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Form --}}
    <form action="/divisi/permits" method="POST" id="permit-form">
        @csrf

        {{-- Error Alert --}}
        <div id="form-error-alert" class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 p-4 rounded-xl transition-opacity duration-300 hidden">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div id="form-error-message" class="text-sm font-medium text-red-800">
                Mohon lengkapi semua form yang wajib diisi (bertanda *) pada langkah ini.
            </div>
        </div>

        {{-- ========================================================
             STEP 1 — A. KLASIFIKASI PEKERJAAN + B. INFORMASI PEKERJAAN
             ======================================================== --}}
        <div id="step-1" class="space-y-5">

            {{-- A. Klasifikasi Pekerjaan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">A</span>
                    <h3 class="text-base font-semibold text-gray-800 mt-0.5">Klasifikasi Pekerjaan</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Pilih satu atau lebih jenis pekerjaan berisiko tinggi</p>
                </div>
                <div class="px-6 py-5 grid grid-cols-2 md:grid-cols-3 gap-3">
                    @php
                        $klasifikasi = [
                            'panas'          => 'Pekerjaan Panas',
                            'ketinggian'     => 'Pekerjaan Ketinggian',
                            'ruang_terbatas' => 'Ruang Terbatas',
                            'galian'         => 'Pekerjaan Galian',
                            'tegangan_tinggi'=> 'Pekerjaan Tegangan Tinggi',
                            'radiasi'        => 'Radiasi',
                        ];
                    @endphp
                    @foreach($klasifikasi as $key => $label)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-inka-navy/30 hover:bg-blue-50/30 cursor-pointer transition-colors">
                            <input type="checkbox" name="klasifikasi_pekerjaan[]" value="{{ $key }}"
                                class="w-4 h-4 rounded text-inka-navy border-gray-300 focus:ring-inka-navy">
                            <span class="text-base text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- B. Informasi Pekerjaan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">B</span>
                    <h3 class="text-base font-semibold text-gray-800 mt-0.5">Informasi Pekerjaan</h3>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="form-label">Pekerjaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pekerjaan" class="form-input" placeholder="Contoh: Perbaikan Atap Gudang B" required>
                    </div>
                    <div>
                        <label class="form-label">Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi" class="form-input" placeholder="Contoh: Gudang B, Area Produksi" required>
                    </div>
                    <div>
                        <label class="form-label">Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="kontraktor" class="form-input" placeholder="Nama perusahaan kontraktor" required>
                    </div>
                    <div>
                        <label class="form-label">Manager / Penanggung Jawab</label>
                        <input type="text" name="penanggung_jawab" class="form-input" placeholder="Nama PIC">
                    </div>
                    <div>
                        <label class="form-label">No. Telpon</label>
                        <input type="text" name="telepon" class="form-input" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-input">
                    </div>
                </div>

                {{-- Daftar Pekerja --}}
                <div class="px-6 pb-5 border-t border-gray-100 pt-4">
                    <p class="text-sm font-semibold text-gray-600 mb-3">Daftar Pekerja</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @php
                            $pekerja = ['Engineer','Operator Alat Berat','Teknisi Listrik','Mekanik','Welder','Operator','Tukang Bangunan','Tukang Kayu','Helper'];
                        @endphp
                        @foreach($pekerja as $p)
                            @php $key = strtolower(str_replace(' ', '_', $p)); @endphp
                            <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2 border border-gray-100">
                                <span class="text-sm text-gray-600 flex-1">{{ $p }}</span>
                                <input type="number" name="daftar_pekerja[{{ $key }}]" min="0" value="0"
                                    class="w-16 text-center text-base font-semibold border border-gray-200 rounded-lg py-0.5 px-1 focus:ring-1 focus:ring-inka-navy focus:border-inka-navy">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Peralatan Kerja --}}
                <div class="px-6 pb-5 border-t border-gray-100 pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-600">Peralatan Kerja & Material</p>
                        <button type="button" onclick="addPeralatan()" class="text-sm font-semibold text-inka-navy hover:underline">+ Tambah Baris</button>
                    </div>
                    {{-- Header kolom --}}
                    <div class="grid grid-cols-4 gap-2 mb-1.5">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-1">Peralatan Kerja</span>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-1">Jumlah</span>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-1">Material</span>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-1">Jumlah</span>
                    </div>
                    {{-- Input rows --}}
                    <div id="peralatan-list" class="space-y-2">
                        <div class="peralatan-row grid grid-cols-4 gap-2 items-center">
                            <input type="text" name="peralatan_kerja[0][alat]" placeholder="mis. Gerinda" class="form-input text-base">
                            <input type="text" name="peralatan_kerja[0][jumlah_alat]" placeholder="1" class="form-input text-base">
                            <input type="text" name="peralatan_kerja[0][material]" placeholder="mis. Baja" class="form-input text-base">
                            <input type="text" name="peralatan_kerja[0][jumlah_material]" placeholder="5 kg" class="form-input text-base">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================
             STEP 2 — C. BAHAYA PEKERJAAN + D. TINDAKAN PENCEGAHAN
             ======================================================== --}}
        <div id="step-2" class="space-y-5 hidden">

            {{-- C. Bahaya Pekerjaan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">C</span>
                    <h3 class="text-base font-semibold text-gray-800 mt-0.5">Bahaya Pekerjaan</h3>
                </div>
                <div class="px-6 py-5 grid grid-cols-2 md:grid-cols-3 gap-3">
                    @php
                        $bahaya = [
                            'percikan_panas'       => 'Percikan Panas',
                            'bahaya_kebakaran'     => 'Bahaya Kebakaran',
                            'cidera_tulang'        => 'Cidera Tulang Belakang',
                            'pencemaran_lingk'     => 'Pencemaran Lingk.',
                            'terpukul_torbentur'   => 'Terpukul / Torbentur',
                            'penerangan_kurang'    => 'Penerangan Kurang',
                            'bahaya_makhluk_hidup' => 'Bahaya Makhluk Hidup',
                            'jatuh_ketinggian'     => 'Jatuh Dari Ketinggian',
                            'lantai_licin'         => 'Lantai Licin',
                            'tangga_penyangga'     => 'Tangga / Penyangga Tidak Kokoh / Patah',
                            'bising'               => 'Bising',
                            'menghasilkan_debu'    => 'Menghasilkan Debu',
                            'bahaya_angin'         => 'Bahaya Angin',
                            'keracunan_gas'        => 'Keracunan Gas',
                            'peledakan'            => 'Peledakan',
                            'bahaya_aliran_listrik'=> 'Bahaya Alat / Aliran Listrik',
                            'bahaya_getaran'       => 'Bahaya Getaran',
                            'bahaya_zat_kimia'     => 'Bahaya Zat Kimia',
                            'terpotong_tertusuk'   => 'Terpotong / Tertusuk',
                            'terperosok'           => 'Terperosok',
                            'tertimpa_tertimpa'    => 'Tertimbun / Tertimpa',
                            'mata_terkena'         => 'Mata Kemasukan Benda',
                            'tertabrak'            => 'Tertabrak / Tabrakan',
                            'limbah_b3'            => 'Limbah B3',
                            'bahaya_radiasi'       => 'Bahaya Radiasi',
                        ];
                    @endphp
                    @foreach($bahaya as $key => $label)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="bahaya_pekerjaan[]" value="{{ $key }}"
                                class="w-4 h-4 rounded text-inka-navy border-gray-300 focus:ring-inka-navy">
                            <span class="text-base text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                    <div class="col-span-full flex items-center gap-2 mt-1">
                        <span class="text-base text-gray-500 shrink-0">Lainnya:</span>
                        <input type="text" name="bahaya_lainnya" class="form-input text-base" placeholder="Sebutkan...">
                    </div>
                </div>
            </div>

            {{-- D. Tindakan Pencegahan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">D</span>
                    <h3 class="text-base font-semibold text-gray-800 mt-0.5">Tindakan Pencegahan Bahaya</h3>
                </div>
                <div class="px-6 py-5 grid grid-cols-2 md:grid-cols-3 gap-3">
                    @php
                        $pencegahan = [
                            'proteksi_dari_jatuh'   => 'Proteksi Dari Jatuh',
                            'media_penghalang_api'  => 'Media Penghambat Api / Percikan',
                            'pintu_masuk_keluar'    => 'Pintu Masuk / Keluar',
                            'safety_briefing'       => 'Safety Brifing',
                            'tangga_penyangga_kuat' => 'Tangga / Penyangga Yang Kokoh',
                            'rambu_rambu'           => 'Rambu-Rambu',
                            'jalur_evakuasi'        => 'Jalur Evakuasi',
                            'penyediaan_pemadam'    => 'Penyediaan Pemadam Api',
                            'barikade_polisi'       => 'Barikade / Pagar / Police Line',
                            'sertifikat_kompetensi' => 'Sertifikat Kompetensi',
                        ];
                    @endphp
                    @foreach($pencegahan as $key => $label)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="tindakan_pencegahan[]" value="{{ $key }}"
                                class="w-4 h-4 rounded text-inka-navy border-gray-300 focus:ring-inka-navy">
                            <span class="text-base text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                    <div class="col-span-full flex items-center gap-2 mt-1">
                        <span class="text-base text-gray-500 shrink-0">Lainnya:</span>
                        <input type="text" name="pencegahan_lainnya" class="form-input text-base" placeholder="Sebutkan...">
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================
             STEP 3 — E. ALAT PELINDUNG DIRI (APD)
             ======================================================== --}}
        <div id="step-3" class="hidden">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">E</span>
                    <h3 class="text-base font-semibold text-gray-800 mt-0.5">Alat Pelindung Diri (APD)</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Pilih APD yang akan digunakan selama pekerjaan berlangsung</p>
                </div>
                <div class="px-6 py-5 grid grid-cols-2 md:grid-cols-3 gap-3">
                    @php
                        $apd = [
                            'helm'               => 'Helm Keselamatan',
                            'kaca_mata'          => 'Kaca Mata Keselamatan',
                            'sarung_tangan'      => 'Sarung Tangan Kulit/Kaos/Karet',
                            'baju_pelindung'     => 'Baju Pelindung',
                            'sepatu'             => 'Sepatu Keselamatan',
                            'kaca_mata_debu'     => 'Kaca Mata Debu',
                            'rompi'              => 'Rompi Keselamatan',
                            'ear_plug'           => 'Ear Plug / Ear Muff',
                            'tali_sabuk'         => 'Tali / Sabuk Keselamatan',
                            'pelindung_muka'     => 'Pelindung Muka',
                            'masker'             => 'Masker / Respirator',
                        ];
                    @endphp
                    @foreach($apd as $key => $label)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-inka-navy/30 hover:bg-blue-50/30 cursor-pointer transition-colors">
                            <input type="checkbox" name="apd[]" value="{{ $key }}"
                                class="w-4 h-4 rounded text-inka-navy border-gray-300 focus:ring-inka-navy">
                            <span class="text-base text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                    <div class="col-span-full flex items-center gap-2 mt-1">
                        <span class="text-base text-gray-500 shrink-0">Lainnya:</span>
                        <input type="text" name="apd_lainnya" class="form-input text-base" placeholder="Sebutkan APD tambahan...">
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================
             STEP 4 — F. VALIDASI KERJA
             ======================================================== --}}
        <div id="step-4" class="hidden">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">F</span>
                    <h3 class="text-base font-semibold text-gray-800 mt-0.5">Validasi Kerja</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Izin diberikan sesuai pengajuan dan kondisi di atas</p>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="text-base font-semibold text-gray-700">Tanda Tangan Pemohon <span class="text-red-500">*</span></p>
                                <p class="text-sm text-gray-400">Tanda tangan pihak kontraktor. Wajib diisi.</p>
                            </div>
                            <button type="button" onclick="clearSignature()"
                                class="text-sm font-semibold text-gray-400 hover:text-red-500 border border-gray-200 hover:border-red-300 px-3 py-1.5 rounded-lg transition-colors">
                                ✕ Hapus
                            </button>
                        </div>

                        {{-- Canvas signature pad --}}
                        <div id="signature-container"
                            class="relative border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-white hover:border-inka-navy/40 transition-colors cursor-crosshair w-full max-w-[300px] aspect-square mx-auto">
                            <canvas id="signature-canvas"
                                class="block w-full h-full"
                                style="touch-action: none;"
                            ></canvas>
                            <span id="signature-placeholder"
                                class="absolute inset-0 flex items-center justify-center text-sm text-gray-300 pointer-events-none select-none">
                                Tanda tangan di sini dengan mouse atau jari
                            </span>
                        </div>

                        {{-- Hidden input yang akan menyimpan data PNG base64 --}}
                        <input type="hidden" name="tanda_tangan" id="tanda-tangan-input">
                        <p id="signature-error" class="text-sm text-red-500 mt-1.5 hidden">Tanda tangan wajib diisi sebelum melanjutkan.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================
             STEP 5 — REVIEW & SUBMIT
             ======================================================== --}}
        <div id="step-5" class="hidden">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-800">Review Pengajuan</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Periksa kembali seluruh data sebelum menyimpan atau mengajukan permit</p>
                </div>
                <div class="px-6 py-5">
                    <div id="review-content" class="space-y-4 text-base text-gray-600">
                        <p class="text-sm text-gray-400 italic">Data review akan ditampilkan di sini setelah Anda mengisi form sebelumnya.</p>
                    </div>
                </div>
            </div>

            {{-- Tombol aksi utama --}}
            <div class="mt-5 flex flex-col sm:flex-row gap-3 justify-end">
                <button type="submit" name="action" value="draft"
                    class="px-6 py-3 rounded-xl border border-inka-navy text-inka-navy text-base font-semibold hover:bg-inka-navy/5 transition-colors">
                    Simpan sebagai Draft
                </button>
                <button type="submit" name="action" value="submit"
                    class="px-6 py-3 rounded-xl bg-inka-navy text-white text-base font-semibold hover:opacity-90 transition-opacity">
                    Submit Permit
                </button>
            </div>
        </div>

        {{-- Navigasi Step --}}
        <div class="mt-6 flex items-center justify-between" id="step-nav">
            <button type="button" id="btn-prev" onclick="changeStep(-1)"
                class="hidden px-5 py-2.5 rounded-xl border border-gray-200 text-base font-semibold text-gray-600 hover:border-gray-300 transition-colors">
                Kembali
            </button>
            <div></div>
            <button type="button" id="btn-next" onclick="changeStep(1)"
                class="px-5 py-2.5 rounded-xl bg-inka-navy text-white text-base font-semibold hover:opacity-90 transition-opacity">
                Selanjutnya
            </button>
        </div>

    </form>

    <style>
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 10px;
            font-size: 1rem; color: #1f2937; background: white;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-input:focus { outline: none; border-color: #111d33; box-shadow: 0 0 0 3px rgba(17,29,51,0.08); }
        .form-input.error { border-color: #fca5a5; }
        .form-input.error:focus { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.15); }
    </style>

    <script>
        let currentStep = 1;
        const totalSteps = 5;

        function changeStep(direction) {
            const nextStep = currentStep + direction;
            if (nextStep < 1 || nextStep > totalSteps) return;

            // HTML5 Form Validation before moving to the next step
            if (direction > 0) {
                const stepEl = document.getElementById('step-' + currentStep);
                const inputs = stepEl.querySelectorAll('input, select, textarea');
                let valid = true;
                
                inputs.forEach(i => i.setCustomValidity(''));

                for (const input of inputs) {
                    if (!input.checkValidity()) {
                        if (input.hasAttribute('required') && !input.value.trim()) {
                            let labelName = 'bidang ini';
                            const parent = input.closest('div');
                            if (parent) {
                                const labelEl = parent.querySelector('.form-label');
                                if (labelEl) {
                                    labelName = labelEl.innerText.replace('*', '').trim();
                                }
                            }
                            input.setCustomValidity('Harap isi ' + labelName);
                        }
                        input.reportValidity(); // Show native browser tooltip
                        valid = false;
                        break;
                    }
                }
                if (!valid) {
                    const errorAlert = document.getElementById('form-error-alert');
                    errorAlert.classList.remove('hidden');
                    errorAlert.style.opacity = '1';

                    inputs.forEach(input => {
                        if (!input.checkValidity()) {
                            input.classList.add('error');
                        }
                        
                        input.addEventListener('input', function removeError() {
                            input.classList.remove('error');
                            input.setCustomValidity('');
                            errorAlert.style.opacity = '0';
                            setTimeout(() => errorAlert.classList.add('hidden'), 300);
                            input.removeEventListener('input', removeError);
                        });
                    });

                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }
            }

            // Validasi tanda tangan saat akan meninggalkan step 4 ke depan
            if (direction > 0 && currentStep === 4) {
                if (!window._signatureHasDrawn || !window._signatureHasDrawn()) {
                    document.getElementById('signature-error').classList.remove('hidden');
                    document.getElementById('signature-canvas').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
            }

            // Hide current
            document.getElementById('step-' + currentStep).classList.add('hidden');

            // Update step indicator
            updateStepIndicator(currentStep, nextStep);

            currentStep = nextStep;

            // Show next
            document.getElementById('step-' + currentStep).classList.remove('hidden');

            // Resize canvas if we just entered step 4
            if (currentStep === 4 && typeof window._resizeSignature === 'function') {
                window._resizeSignature();
            }

            // Update buttons
            document.getElementById('btn-prev').classList.toggle('hidden', currentStep === 1);
            document.getElementById('btn-next').classList.toggle('hidden', currentStep === totalSteps);

            // Build review on step 5
            if (currentStep === totalSteps) buildReview();

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateStepIndicator(from, to) {
            // Completed step
            document.getElementById('step-circle-' + from).className =
                'step-circle w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 bg-green-500 text-white border-green-500';
            document.getElementById('step-label-' + from).className =
                'text-xs mt-1 font-medium text-center leading-tight text-green-600';

            // Active next step
            document.getElementById('step-circle-' + to).className =
                'step-circle w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 bg-inka-navy text-white border-inka-navy';
            document.getElementById('step-label-' + to).className =
                'text-xs mt-1 font-medium text-center leading-tight text-inka-navy';

            // Line between steps
            if (from < to && document.getElementById('step-line-' + from)) {
                document.getElementById('step-line-' + from).classList.add('bg-green-400');
                document.getElementById('step-line-' + from).classList.remove('bg-gray-200');
            }
        }

        function buildReview() {
            const form = document.getElementById('permit-form');
            const fd = new FormData(form);
            let html = '';

            const namaP = fd.get('nama_pekerjaan') || '—';
            const lokasi = fd.get('lokasi') || '—';
            const kontra = fd.get('kontraktor') || '—';
            const pic    = fd.get('penanggung_jawab') || '—';
            const telp   = fd.get('telepon') || '—';
            const tgl1   = fd.get('tanggal_mulai') || '—';
            const tgl2   = fd.get('tanggal_selesai') || '—';

            html += `<div class="grid grid-cols-2 gap-x-6 gap-y-3 text-base">
                <div><p class="text-sm text-gray-400">Nama Pekerjaan</p><p class="font-medium text-gray-800">${namaP}</p></div>
                <div><p class="text-sm text-gray-400">Lokasi</p><p class="font-medium text-gray-800">${lokasi}</p></div>
                <div><p class="text-sm text-gray-400">Kontraktor</p><p class="font-medium text-gray-800">${kontra}</p></div>
                <div><p class="text-sm text-gray-400">Penanggung Jawab</p><p class="font-medium text-gray-800">${pic}</p></div>
                <div><p class="text-sm text-gray-400">Telepon</p><p class="font-medium text-gray-800">${telp}</p></div>
                <div><p class="text-sm text-gray-400">Tanggal</p><p class="font-medium text-gray-800">${tgl1} s/d ${tgl2}</p></div>
            </div>`;

            const bahaya = fd.getAll('bahaya_pekerjaan[]');
            if (bahaya.length) {
                html += `<div class="border-t border-gray-100 pt-4"><p class="text-sm text-gray-400 mb-1">Bahaya Pekerjaan (${bahaya.length} dipilih)</p>
                    <p class="font-medium text-gray-800">${bahaya.join(', ')}</p></div>`;
            }

            const apd = fd.getAll('apd[]');
            if (apd.length) {
                html += `<div class="border-t border-gray-100 pt-4"><p class="text-sm text-gray-400 mb-1">APD (${apd.length} dipilih)</p>
                    <p class="font-medium text-gray-800">${apd.join(', ')}</p></div>`;
            }

            document.getElementById('review-content').innerHTML = html;
        }

        // ===== TANDA TANGAN DIGITAL (CANVAS) =====
        (function () {
            const canvas = document.getElementById('signature-canvas');
            const placeholder = document.getElementById('signature-placeholder');
            const hiddenInput = document.getElementById('tanda-tangan-input');
            const container = document.getElementById('signature-container');

            const ctx = canvas.getContext('2d');

            // Sesuaikan resolusi canvas dengan ukuran tampilan
            function resizeCanvas() {
                const rect = container.getBoundingClientRect();
                if (rect.width === 0) return; // Prevent resizing if hidden

                const dpr = window.devicePixelRatio || 1;
                canvas.width  = rect.width  * dpr;
                canvas.height = rect.height * dpr;
                ctx.scale(dpr, dpr);
                
                // Reapply styles after scale resets them
                ctx.strokeStyle = '#111d33';
                ctx.lineWidth   = 2;
                ctx.lineCap     = 'round';
                ctx.lineJoin    = 'round';
            }
            window.addEventListener('resize', resizeCanvas);
            
            // Expose resize globally so we can trigger it when step 4 is shown
            window._resizeSignature = resizeCanvas;

            let drawing = false;
            let hasDrawn = false;

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                if (e.touches) {
                    return {
                        x: e.touches[0].clientX - rect.left,
                        y: e.touches[0].clientY - rect.top,
                    };
                }
                return { x: e.clientX - rect.left, y: e.clientY - rect.top };
            }

            function startDraw(e) {
                e.preventDefault();
                drawing = true;
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                if (!drawing) return;
                e.preventDefault();
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();

                if (!hasDrawn) {
                    hasDrawn = true;
                    placeholder.classList.add('hidden');
                    container.classList.remove('border-dashed', 'border-gray-300');
                    container.classList.add('border-solid', 'border-inka-navy/30');
                    document.getElementById('signature-error').classList.add('hidden');
                }
            }

            function stopDraw() {
                if (!drawing) return;
                drawing = false;
                ctx.beginPath();
                // Simpan hasil ke hidden input sebagai Base64 PNG
                hiddenInput.value = canvas.toDataURL('image/png');
            }

            canvas.addEventListener('mousedown',  startDraw);
            canvas.addEventListener('mousemove',  draw);
            canvas.addEventListener('mouseup',    stopDraw);
            canvas.addEventListener('mouseleave', stopDraw);
            canvas.addEventListener('touchstart', startDraw, { passive: false });
            canvas.addEventListener('touchmove',  draw,      { passive: false });
            canvas.addEventListener('touchend',   stopDraw);

            // Expose clear function
            window.clearSignature = function () {
                const dpr = window.devicePixelRatio || 1;
                ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
                hasDrawn = false;
                hiddenInput.value = '';
                placeholder.classList.remove('hidden');
                container.classList.add('border-dashed', 'border-gray-300');
                container.classList.remove('border-solid', 'border-inka-navy/30');
            };

            // Validasi wajib tanda tangan saat pindah ke step 5 atau submit
            window._signatureHasDrawn = function () { return hasDrawn; };
        })();

        // Tambah baris peralatan
        let peralatanCount = 1;
        function addPeralatan() {
            const list = document.getElementById('peralatan-list');
            const row = document.createElement('div');
            row.className = 'peralatan-row grid grid-cols-4 gap-2 items-center';
            row.innerHTML = `
                <input type="text" name="peralatan_kerja[${peralatanCount}][alat]" placeholder="mis. Gerinda" class="form-input text-base">
                <input type="text" name="peralatan_kerja[${peralatanCount}][jumlah_alat]" placeholder="1" class="form-input text-base">
                <input type="text" name="peralatan_kerja[${peralatanCount}][material]" placeholder="mis. Baja" class="form-input text-base">
                <input type="text" name="peralatan_kerja[${peralatanCount}][jumlah_material]" placeholder="5 kg" class="form-input text-base">
            `;
            list.appendChild(row);
            peralatanCount++;
        }
    </script>

</x-app-layout>
