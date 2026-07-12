<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permit;
use App\Models\User;
use Carbon\Carbon;

class PermitSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user divisi
        $divisi = User::where('role', 'divisi')->first();

        if (! $divisi) {
            $this->command->warn('User dengan role "divisi" tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $permits = [
            [
                'no_permit'        => 'WP-2026-001',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Perbaikan Atap Gudang B',
                'kontraktor'       => 'PT Maju Mundur Konstruksi',
                'lokasi'           => 'Gudang B, Area Produksi',
                'penanggung_jawab' => 'Budi Santoso',
                'telepon'          => '081234567890',
                'tanggal_mulai'    => Carbon::now()->addDays(1),
                'tanggal_selesai'  => Carbon::now()->addDays(5),
                'status'           => 'Active',
                'submitted_at'     => Carbon::now()->subDays(3),
            ],
            [
                'no_permit'        => 'WP-2026-002',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Instalasi Panel Listrik Unit 3',
                'kontraktor'       => 'CV Terang Abadi',
                'lokasi'           => 'Ruang Panel, Gedung Utama',
                'penanggung_jawab' => 'Agus Setiawan',
                'telepon'          => '082345678901',
                'tanggal_mulai'    => Carbon::now()->addDays(2),
                'tanggal_selesai'  => Carbon::now()->addDays(4),
                'status'           => 'Submitted',
                'submitted_at'     => Carbon::now()->subDays(1),
            ],
            [
                'no_permit'        => 'WP-2026-003',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Pengelasan Pipa Boiler',
                'kontraktor'       => 'PT Sarana Teknik Jaya',
                'lokasi'           => 'Ruang Boiler, Lantai 2',
                'penanggung_jawab' => 'Cahyo Prasetyo',
                'telepon'          => '083456789012',
                'tanggal_mulai'    => Carbon::now()->subDays(10),
                'tanggal_selesai'  => Carbon::now()->subDays(5),
                'status'           => 'Closed',
                'submitted_at'     => Carbon::now()->subDays(12),
                'closed_at'        => Carbon::now()->subDays(5),
            ],
            [
                'no_permit'        => 'WP-2026-004',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Pembersihan Tangki Air',
                'kontraktor'       => 'UD Bersih Sejahtera',
                'lokasi'           => 'Area Utilitas Barat',
                'penanggung_jawab' => 'Dewi Kurniawati',
                'telepon'          => '084567890123',
                'tanggal_mulai'    => null,
                'tanggal_selesai'  => null,
                'status'           => 'Draft',
                'submitted_at'     => null,
            ],
            [
                'no_permit'        => 'WP-2026-005',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Pemasangan Scaffolding Area Korosi',
                'kontraktor'       => 'PT Rangka Makmur',
                'lokasi'           => 'Koridor C, Lantai 3',
                'penanggung_jawab' => 'Eko Firmansyah',
                'telepon'          => '085678901234',
                'tanggal_mulai'    => Carbon::now()->addDays(3),
                'tanggal_selesai'  => Carbon::now()->addDays(7),
                'status'           => 'Revision',
                'submitted_at'     => Carbon::now()->subDays(2),
                'catatan_revisi'   => 'Dokumen JSA belum dilampirkan. Mohon dilengkapi sebelum diajukan kembali.',
            ],
            [
                'no_permit'        => 'WP-2026-006',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Pengecatan Gedung A',
                'kontraktor'       => 'CV Warna Warni',
                'lokasi'           => 'Eksterior Gedung A',
                'penanggung_jawab' => 'Fajar Sidik',
                'telepon'          => '086789012345',
                'tanggal_mulai'    => Carbon::now()->addDays(2),
                'tanggal_selesai'  => Carbon::now()->addDays(8),
                'status'           => 'Review Staff',
                'submitted_at'     => Carbon::now()->subHours(5),
            ],
            [
                'no_permit'        => 'WP-2026-007',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Service AC Sentral',
                'kontraktor'       => 'PT Dingin Sejuk',
                'lokasi'           => 'Lantai 1-3',
                'penanggung_jawab' => 'Gilang Pratama',
                'telepon'          => '087890123456',
                'tanggal_mulai'    => Carbon::now()->addDays(1),
                'tanggal_selesai'  => Carbon::now()->addDays(2),
                'status'           => 'Review Manager',
                'submitted_at'     => Carbon::now()->subDays(1),
                'approval_signatures' => [
                    [
                        'role' => 'Safety Officer',
                        'name' => 'Staff Name',
                        'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=',
                        'date' => Carbon::now()->subHours(10)->toDateTimeString()
                    ]
                ]
            ],
            [
                'no_permit'        => 'WP-2026-008',
                'user_id'          => $divisi->id,
                'nama_pekerjaan'   => 'Pemasangan Kabel Fiber Optik',
                'kontraktor'       => 'PT Cepat Net',
                'lokasi'           => 'Jalur Utama, Area IT',
                'penanggung_jawab' => 'Hadi Sucipto',
                'telepon'          => '088901234567',
                'tanggal_mulai'    => Carbon::now()->addDays(4),
                'tanggal_selesai'  => Carbon::now()->addDays(6),
                'status'           => 'Review Senior Manager',
                'submitted_at'     => Carbon::now()->subDays(2),
                'approval_signatures' => [
                    [
                        'role' => 'Safety Officer',
                        'name' => 'Staff Name',
                        'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=',
                        'date' => Carbon::now()->subDays(1)->toDateTimeString()
                    ],
                    [
                        'role' => 'Manager',
                        'name' => 'Manager Name',
                        'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=',
                        'date' => Carbon::now()->subHours(5)->toDateTimeString()
                    ]
                ]
            ],
        ];

        foreach ($permits as $permit) {
            Permit::updateOrCreate(
                ['no_permit' => $permit['no_permit']],
                $permit
            );
        }

        $this->command->info('PermitSeeder: ' . count($permits) . ' data permit berhasil dibuat.');
    }
}
