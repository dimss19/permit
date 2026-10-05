<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Multi-site: Madiun & Banyuwangi.
     * - users.site: site home/kerja user (staff wajib terisi, manager/superadmin/divisi null = semua site).
     * - permits.site: site lokasi pekerjaan (wajib terisi, default 'Madiun' untuk data lama).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('site', 20)->nullable()->after('role');
        });

        Schema::table('permits', function (Blueprint $table) {
            $table->string('site', 20)->default('Madiun')->after('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn('site');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('site');
        });
    }
};
