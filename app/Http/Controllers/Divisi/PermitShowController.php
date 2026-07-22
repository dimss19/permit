<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PermitShowController extends Controller
{
    public function show($id)
    {
        $userId = Auth::id();

        $permit = Permit::where('user_id', $userId)
                         ->where('id', $id)
                         ->firstOrFail();

        return view('divisi.permits.show', compact('permit'));
    }

    public function downloadPdf($id)
    {
        $userId = Auth::id();

        $permit = Permit::where('user_id', $userId)
                         ->where('id', $id)
                         ->firstOrFail();

        $pdf = Pdf::loadView('divisi.permits.pdf', compact('permit'))
            ->setPaper('A4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif']);

        // Menampilkan di browser atau unduh
        return $pdf->stream('Permit-' . str_replace('/', '-', $permit->no_permit) . '.pdf');
    }
}
