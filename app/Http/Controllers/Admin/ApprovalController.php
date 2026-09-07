<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use App\Models\PermitDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    private function getRoleConfig()
    {
        $role = Auth::user()->role;

        if ($role === 'staff') {
            return [
                'roleName' => 'Staff',
                'expectedStatus' => 'Review Staff',
                'nextStatus' => 'Review Manager',
                'nextRoleName' => 'Manager',
                'sortCol' => 'submitted_at',
                'dateColumnLabel' => 'Tgl. Submit'
            ];
        } elseif ($role === 'manager') {
            return [
                'roleName' => 'Manager',
                'expectedStatus' => 'Review Manager',
                'nextStatus' => 'Review Senior Manager',
                'nextRoleName' => 'Senior Manager',
                'sortCol' => 'updated_at',
                'dateColumnLabel' => 'Tgl. Masuk Manager'
            ];
        } elseif ($role === 'senior-manager') {
            return [
                'roleName' => 'Senior Manager',
                'expectedStatus' => 'Review Senior Manager',
                'nextStatus' => 'Active',
                'nextRoleName' => 'Aktif',
                'sortCol' => 'updated_at',
                'dateColumnLabel' => 'Tgl. Masuk Senior Manager'
            ];
        }

        abort(403);
    }

    public function index(Request $request)
    {
        $config = $this->getRoleConfig();
        $query = Permit::with(['user', 'classifications']);

        if ($request->date === 'today') {
            $query->whereDate('updated_at', today())->where('status', $config['expectedStatus']);
        } elseif ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        } else {
            $query->where('status', $config['expectedStatus']);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_permit', 'like', "%{$q}%")
                    ->orWhere('nama_pekerjaan', 'like', "%{$q}%")
                    ->orWhere('kontraktor', 'like', "%{$q}%");
            });
        }

        $permits = $query->orderBy($config['sortCol'], 'asc')->paginate(15)->withQueryString();

        return view('admin.approvals.index', compact('permits', 'config'));
    }

    public function show($id)
    {
        $config = $this->getRoleConfig();
        $permit = Permit::with(['user', 'documents'])->findOrFail($id);
        
        $canReview = $permit->status === $config['expectedStatus'];

        return view('admin.approvals.show', compact('permit', 'canReview', 'config'));
    }

    public function update(Request $request, $id)
    {
        $config = $this->getRoleConfig();
        $permit = Permit::findOrFail($id);

        if ($permit->status !== $config['expectedStatus']) {
            return redirect('/admin/approvals')->with('error', 'Permit tidak valid untuk direview.');
        }

        $action = $request->input('action'); 

        if ($action === 'approve') {
            $request->validate([
                'tanda_tangan' => 'required|string',
            ]);

            $signatures = $permit->approval_signatures ?? [];
            $signatures[] = [
                'role' => $config['roleName'],
                'name' => Auth::user()->name,
                'signature' => $request->tanda_tangan,
                'date' => now()->toDateTimeString(),
            ];

            $permit->forceFill([
                'status' => $config['nextStatus'],
                'catatan_revisi' => null,
                'approval_signatures' => $signatures,
            ])->save();

            if ($config['nextStatus'] === 'Active') {
                $message = 'Permit berhasil disetujui dan kini berstatus ACTIVE.';
            } else {
                $message = 'Permit berhasil disetujui dan diteruskan ke ' . $config['nextRoleName'] . '.';
            }

        } elseif ($action === 'revise') {
            $request->validate([
                'catatan_revisi' => 'required|string|max:500'
            ]);
            $permit->forceFill([
                'status' => 'Revision',
                'catatan_revisi' => $request->catatan_revisi,
            ])->save();
            $message = 'Permit dikembalikan ke Divisi untuk direvisi.';
        } else {
            return redirect('/admin/approvals')->with('error', 'Aksi tidak valid.');
        }

        return redirect('/admin/approvals')->with('success', $message);
    }

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

    public function downloadDocument($permitId, $documentId)
    {
        $config = $this->getRoleConfig();
        $permit = Permit::findOrFail($permitId);

        // Hanya izinkan download dari permit yang sedang dalam status review
        $allowedStatuses = ['Review Staff', 'Review Manager', 'Review Senior Manager', 'Revision', 'Active', 'Closed'];
        if (!in_array($permit->status, $allowedStatuses)) {
            abort(403, 'Permit tidak tersedia untuk diakses.');
        }

        $doc = PermitDocument::where('permit_id', $permit->id)->where('id', $documentId)->firstOrFail();

        $path = $doc->file_path;
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download($path, $doc->nama_dokumen . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
    }
}
