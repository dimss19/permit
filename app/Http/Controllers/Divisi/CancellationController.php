<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CancellationController extends Controller
{
    /**
     * Menampilkan daftar permit Active milik Divisi yang dapat dibatalkan.
     */
    public function index()
    {
        $permits = Permit::byDivisi(Auth::id())
            ->whereNotIn('status', ['Closed', 'Cancelled'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('divisi.cancellations.index', compact('permits'));
    }

    /**
     * Menampilkan form pembatalan untuk permit tertentu.
     */
    public function show($id)
    {
        $permit = Permit::byDivisi(Auth::id())->findOrFail($id);

        if (in_array($permit->status, ['Closed', 'Cancelled'])) {
            return redirect('/divisi/cancellations')->with('error', 'Permit yang sudah ditutup atau dibatalkan tidak dapat dibatalkan lagi.');
        }

        return view('divisi.cancellations.show', compact('permit'));
    }

    /**
     * Memproses pembatalan permit.
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
            'tanda_tangan' => 'required|string',
        ]);

        $permit = Permit::byDivisi(Auth::id())->findOrFail($id);

        if (in_array($permit->status, ['Closed', 'Cancelled'])) {
            return redirect('/divisi/cancellations')->with('error', 'Permit yang sudah ditutup atau dibatalkan tidak dapat dibatalkan lagi.');
        }

        $nama = Auth::user()->name;

        // Simpan tanda tangan pemohon yang membatalkan
        $signatures = $permit->cancellation_signatures ?? [];
        $signatures[] = [
            'role' => 'Pemohon (Divisi)',
            'name' => $nama,
            'signature' => $request->tanda_tangan,
            'date' => now()->toDateTimeString(),
        ];

        // Langsung batalkan permit
        $permit->update([
            'status' => 'Cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->cancellation_reason,
            'cancellation_signatures' => $signatures,
        ]);

        return redirect('/divisi/history')->with('success', 'Izin kerja berhasil dibatalkan secara langsung.');
    }
}
