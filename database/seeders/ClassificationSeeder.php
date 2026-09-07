<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $classifications = [
            [
                'name'        => 'Pekerjaan Panas',
                'code'        => 'panas',
                'description' => 'Pekerjaan yang menghasilkan percikan api atau panas tinggi seperti pengelasan, pemotongan api, dll.',
            ],
            [
                'name'        => 'Pekerjaan Ketinggian',
                'code'        => 'ketinggian',
                'description' => 'Pekerjaan di tempat kerja yang berada di atas 1.8 meter dari permukaan tanah.',
            ],
            [
                'name'        => 'Ruang Terbatas',
                'code'        => 'ruang_terbatas',
                'description' => 'Pekerjaan di dalam tangki, silo, bunker, atau bejana yang memiliki akses keluar masuk terbatas.',
            ],
            [
                'name'        => 'Pekerjaan Galian',
                'code'        => 'galian',
                'description' => 'Pekerjaan penggalian tanah atau pembongkaran struktur bawah tanah.',
            ],
            [
                'name'        => 'Pekerjaan Tegangan Tinggi',
                'code'        => 'tegangan_tinggi',
                'description' => 'Pekerjaan yang berhubungan langsung dengan instalasi listrik tegangan menengah/tinggi.',
            ],
            [
                'name'        => 'Radiasi',
                'code'        => 'radiasi',
                'description' => 'Pekerjaan yang berpotensi terpapar sinar radiasi pengion seperti NDT radiography.',
            ],
        ];

        foreach ($classifications as $item) {
            Classification::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
