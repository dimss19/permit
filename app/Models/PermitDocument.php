<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitDocument extends Model
{
    protected $fillable = [
        'permit_id',
        'nama_dokumen',
        'deskripsi',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function permit()
    {
        return $this->belongsTo(Permit::class);
    }
}
