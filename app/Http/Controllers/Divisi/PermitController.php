<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use App\Models\PermitDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'tipe'             => 'required|in:Internal,Eksternal',
            'nama_pekerjaan'   => 'required|string|max:255',
            'kontraktor'       => 'required|string|max:255',
            'lokasi'           => 'required|string|max:255',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'tanda_tangan'     => $request->input('action') === 'submit' ? 'required|string' : 'nullable|string',
        ]);

        // Validasi dokumen untuk tipe Eksternal
        if ($request->input('tipe') === 'Eksternal') {
            $request->validate([
                'dokumen'               => 'required|array|min:1',
                'dokumen.*.nama'        => 'required|string|max:255',
                'dokumen.*.deskripsi'   => 'nullable|string|max:500',
                'dokumen.*.file'        => 'required|file|max:10240',
            ]);
        }

        $user = Auth::user();

        // Auto-generate nomor permit
        $noPermit = 'WP-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        $status = $request->input('action') === 'submit' ? 'Review Staff' : 'Draft';

        $permit = Permit::create([
            'no_permit'             => $noPermit,
            'user_id'               => $user->id,
            'tipe'                  => $request->input('tipe', 'Internal'),
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
            'tanda_tangan'          => $request->input('tanda_tangan'),
            'status'                => $status,
            'submitted_at'          => $status === 'Review Staff' ? now() : null,
        ]);

        // Simpan dokumen pendukung untuk tipe Eksternal
        if ($request->input('tipe') === 'Eksternal' && $request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $doc) {
                $file = $doc['file'];
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                $path = $file->storeAs('permits/' . $permit->id, $filename);

                PermitDocument::create([
                    'permit_id'    => $permit->id,
                    'nama_dokumen' => $doc['nama'],
                    'deskripsi'    => $doc['deskripsi'] ?? null,
                    'file_path'    => $path,
                    'file_type'    => $ext,
                    'file_size'    => $file->getSize(),
                ]);
            }
        }

        $message = $status === 'Draft'
            ? 'Permit berhasil disimpan sebagai Draft.'
            : 'Permit berhasil diajukan.';

        return redirect('/divisi/dashboard')->with('success', $message);
    }

    /** Tampilkan form edit (hanya untuk Draft/Revision) */
    public function edit($id)
    {
        $userId = Auth::id();
        $permit = Permit::with('documents')->where('user_id', $userId)->where('id', $id)->firstOrFail();

        if (!in_array($permit->status, ['Draft', 'Revision'])) {
            return redirect('/divisi/dashboard')->with('error', 'Permit tidak dapat diedit.');
        }

        return view('divisi.permits.edit', compact('permit'));
    }

    /** Simpan update permit */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $permit = Permit::with('documents')->where('user_id', $userId)->where('id', $id)->firstOrFail();

        if (!in_array($permit->status, ['Draft', 'Revision'])) {
            return redirect('/divisi/dashboard')->with('error', 'Permit tidak dapat diedit.');
        }

        $request->validate([
            'tipe'             => 'required|in:Internal,Eksternal',
            'nama_pekerjaan'   => 'required|string|max:255',
            'kontraktor'       => 'required|string|max:255',
            'lokasi'           => 'required|string|max:255',
            'tanggal_mulai'    => 'nullable|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'tanda_tangan'     => $request->input('action') === 'submit' ? 'required|string' : 'nullable|string',
        ]);

        // Validasi dokumen untuk tipe Eksternal
        if ($request->input('tipe') === 'Eksternal') {
            $existingCount = $permit->documents()->count();
            $newDocs = $request->input('dokumen', []);
            $hasNewDocs = $request->hasFile('dokumen');

            if ($existingCount === 0 && !$hasNewDocs) {
                return back()->withErrors(['tipe' => 'Minimal upload 1 dokumen pendukung untuk permit eksternal.']);
            }

            if ($hasNewDocs) {
                $request->validate([
                    'dokumen.*.nama'      => 'required|string|max:255',
                    'dokumen.*.deskripsi' => 'nullable|string|max:500',
                    'dokumen.*.file'      => 'required|file|max:10240',
                ]);
            }
        }

        $status = $request->input('action') === 'submit' ? 'Review Staff' : 'Draft';

        // Handle perubahan tipe
        $oldTipe = $permit->tipe;
        $newTipe = $request->input('tipe');

        // Jika tipe berubah dari Eksternal ke Internal, hapus semua dokumen
        if ($oldTipe === 'Eksternal' && $newTipe === 'Internal') {
            foreach ($permit->documents as $doc) {
                Storage::disk('local')->delete($doc->file_path);
            }
            $permit->documents()->delete();
        }

        $permit->update([
            'tipe'                  => $newTipe,
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
            'tanda_tangan'          => $request->input('tanda_tangan') ?? $permit->tanda_tangan,
            'status'                => $status,
            'submitted_at'          => $status === 'Review Staff' ? now() : $permit->submitted_at,
        ]);

        // Simpan dokumen baru untuk tipe Eksternal
        if ($newTipe === 'Eksternal' && $request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $doc) {
                $file = $doc['file'];
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                $path = $file->storeAs('permits/' . $permit->id, $filename);

                PermitDocument::create([
                    'permit_id'    => $permit->id,
                    'nama_dokumen' => $doc['nama'],
                    'deskripsi'    => $doc['deskripsi'] ?? null,
                    'file_path'    => $path,
                    'file_type'    => $ext,
                    'file_size'    => $file->getSize(),
                ]);
            }
        }

        // Handle hapus dokumen individual
        if ($request->has('hapus_dokumen')) {
            foreach ($request->input('hapus_dokumen') as $docId) {
                $doc = PermitDocument::where('permit_id', $permit->id)->find($docId);
                if ($doc) {
                    Storage::disk('local')->delete($doc->file_path);
                    $doc->delete();
                }
            }
        }

        $message = $status === 'Draft'
            ? 'Perubahan permit berhasil disimpan sebagai Draft.'
            : 'Permit berhasil diajukan.';

        return redirect('/divisi/dashboard')->with('success', $message);
    }

    public function downloadDocument($permitId, $documentId)
    {
        $userId = Auth::id();
        $permit = Permit::where('user_id', $userId)->where('id', $permitId)->firstOrFail();
        $doc = PermitDocument::where('permit_id', $permit->id)->where('id', $documentId)->firstOrFail();

        $path = $doc->file_path;
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download($path, $doc->nama_dokumen . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
    }
}
