<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    /** Tampilkan form wizard buat permit */
    public function create()
    {
        return view('divisi.permits.create');
    }

    /** Simpan permit (draft atau submit) */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pekerjaan'  => 'required|string|max:255',
            'kontraktor'      => 'required|string|max:255',
            'lokasi'          => 'required|string|max:255',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tanda_tangan'    => $request->input('action') === 'submit' ? 'required|string' : 'nullable|string',
        ]);

        $user = Auth::user();

        // Auto-generate nomor permit
        $noPermit = 'WP-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        $status = $request->input('action') === 'submit' ? 'Review Staff' : 'Draft';

        Permit::create([
            'no_permit'             => $noPermit,
            'user_id'               => $user->id,
            'nama_pekerjaan'        => $request->nama_pekerjaan,
            'kontraktor'            => $request->kontraktor,
            'lokasi'                => $request->lokasi,
            'penanggung_jawab'      => $request->penanggung_jawab,
            'telepon'               => $request->telepon,
            'tanggal_mulai'         => $request->tanggal_mulai,
            'tanggal_selesai'       => $request->tanggal_selesai,
            'klasifikasi_pekerjaan' => $request->input('klasifikasi_pekerjaan', []),
            'daftar_pekerja'        => $request->input('daftar_pekerja', []),
            'peralatan_kerja'       => $request->input('peralatan_kerja', []),
            'bahaya_pekerjaan'      => $request->input('bahaya_pekerjaan', []),
            'bahaya_lainnya'        => $request->bahaya_lainnya,
            'tindakan_pencegahan'   => $request->input('tindakan_pencegahan', []),
            'pencegahan_lainnya'    => $request->pencegahan_lainnya,
            'apd'                   => $request->input('apd', []),
            'apd_lainnya'           => $request->apd_lainnya,
            'status'                => $status,
            'submitted_at'          => $status === 'Review Staff' ? now() : null,
        ]);

        $message = $status === 'Draft'
            ? 'Permit berhasil disimpan sebagai Draft.'
            : 'Permit berhasil diajukan.';

        return redirect('/divisi/dashboard')->with('success', $message);
    }

    /** Tampilkan form edit (hanya untuk Draft/Revision) */
    public function edit($id)
    {
        $userId = Auth::id();
        $permit = Permit::where('user_id', $userId)->where('id', $id)->firstOrFail();

        if (!in_array($permit->status, ['Draft', 'Revision'])) {
            return redirect('/divisi/dashboard')->with('error', 'Permit tidak dapat diedit.');
        }

        return view('divisi.permits.edit', compact('permit'));
    }

    /** Simpan update permit */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $permit = Permit::where('user_id', $userId)->where('id', $id)->firstOrFail();

        if (!in_array($permit->status, ['Draft', 'Revision'])) {
            return redirect('/divisi/dashboard')->with('error', 'Permit tidak dapat diedit.');
        }

        $request->validate([
            'nama_pekerjaan'  => 'required|string|max:255',
            'kontraktor'      => 'required|string|max:255',
            'lokasi'          => 'required|string|max:255',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tanda_tangan'    => $request->input('action') === 'submit' ? 'required|string' : 'nullable|string',
        ]);

        $status = $request->input('action') === 'submit' ? 'Review Staff' : 'Draft';

        $permit->update([
            'nama_pekerjaan'        => $request->nama_pekerjaan,
            'kontraktor'            => $request->kontraktor,
            'lokasi'                => $request->lokasi,
            'penanggung_jawab'      => $request->penanggung_jawab,
            'telepon'               => $request->telepon,
            'tanggal_mulai'         => $request->tanggal_mulai,
            'tanggal_selesai'       => $request->tanggal_selesai,
            'klasifikasi_pekerjaan' => $request->input('klasifikasi_pekerjaan', []),
            'daftar_pekerja'        => $request->input('daftar_pekerja', []),
            'peralatan_kerja'       => $request->input('peralatan_kerja', []),
            'bahaya_pekerjaan'      => $request->input('bahaya_pekerjaan', []),
            'bahaya_lainnya'        => $request->bahaya_lainnya,
            'tindakan_pencegahan'   => $request->input('tindakan_pencegahan', []),
            'pencegahan_lainnya'    => $request->pencegahan_lainnya,
            'apd'                   => $request->input('apd', []),
            'apd_lainnya'           => $request->apd_lainnya,
            'status'                => $status,
            'submitted_at'          => $status === 'Review Staff' ? now() : $permit->submitted_at,
        ]);

        $message = $status === 'Draft'
            ? 'Perubahan permit berhasil disimpan sebagai Draft.'
            : 'Permit berhasil diajukan.';

        return redirect('/divisi/dashboard')->with('success', $message);
    }
}
