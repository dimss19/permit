<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            // A. Klasifikasi Pekerjaan (JSON array of selected types)
            $table->json('klasifikasi_pekerjaan')->nullable()->after('no_permit');

            // B. Informasi Pekerjaan tambahan
            $table->string('perusahaan')->nullable()->after('telepon');
            $table->json('daftar_pekerja')->nullable()->after('perusahaan');   // {engineer:0, operator_alat_berat:0, ...}
            $table->json('peralatan_kerja')->nullable()->after('daftar_pekerja'); // [{alat:'', jumlah:'', material:'', jml_material:''}]

            // C. Bahaya Pekerjaan
            $table->json('bahaya_pekerjaan')->nullable()->after('peralatan_kerja');
            $table->string('bahaya_lainnya')->nullable()->after('bahaya_pekerjaan');

            // D. Tindakan Pencegahan
            $table->json('tindakan_pencegahan')->nullable()->after('bahaya_lainnya');
            $table->string('pencegahan_lainnya')->nullable()->after('tindakan_pencegahan');

            // E. APD
            $table->json('apd')->nullable()->after('pencegahan_lainnya');
            $table->string('apd_lainnya')->nullable()->after('apd');
        });
    }

    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn([
                'klasifikasi_pekerjaan',
                'perusahaan',
                'daftar_pekerja',
                'peralatan_kerja',
                'bahaya_pekerjaan',
                'bahaya_lainnya',
                'tindakan_pencegahan',
                'pencegahan_lainnya',
                'apd',
                'apd_lainnya',
            ]);
        });
    }
};
