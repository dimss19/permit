<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Barryvdh\DomPDF\Facade\Pdf;

class PermitController extends Controller
{
    public function downloadPdf($id)
    {
        $permit = Permit::findOrFail($id);

        $allowedStatuses = ['Review Staff', 'Review Manager', 'Review Senior Manager', 'Revision', 'Active', 'Closed'];
        if (!in_array($permit->status, $allowedStatuses)) {
            abort(403, 'Permit tidak tersedia untuk diakses.');
        }

        $pdf = Pdf::loadView('divisi.permits.pdf', compact('permit'))
            ->setPaper('A4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('Permit-' . str_replace('/', '-', $permit->no_permit) . '.pdf');
    }
}
