<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_permit',
        'user_id',
        'nama_pekerjaan',
        'kontraktor',
        'lokasi',
        'penanggung_jawab',
        'telepon',
        'perusahaan',
        'tanggal_mulai',
        'tanggal_selesai',
        'klasifikasi_pekerjaan',
        'daftar_pekerja',
        'peralatan_kerja',
        'bahaya_pekerjaan',
        'bahaya_lainnya',
        'tindakan_pencegahan',
        'pencegahan_lainnya',
        'apd',
        'apd_lainnya',
        'status',
        'submitted_at',
        'closed_at',
        'catatan_revisi',
    ];

    protected $casts = [
        'tanggal_mulai'         => 'date',
        'tanggal_selesai'       => 'date',
        'submitted_at'          => 'datetime',
        'closed_at'             => 'datetime',
        'klasifikasi_pekerjaan' => 'array',
        'daftar_pekerja'        => 'array',
        'peralatan_kerja'       => 'array',
        'bahaya_pekerjaan'      => 'array',
        'tindakan_pencegahan'   => 'array',
        'apd'                   => 'array',
    ];

    /** Relasi ke User (pemilik / divisi) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Scope: permit milik user tertentu */
    public function scopeByDivisi($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
