<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->string('no_permit')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // pemilik (divisi)
            $table->string('nama_pekerjaan');
            $table->string('kontraktor');
            $table->string('lokasi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('telepon')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', [
                'Draft',
                'Submitted',
                'Review Staff',
                'Review Manager',
                'Review Senior Manager',
                'Revision',
                'Active',
                'Closed',
            ])->default('Draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
