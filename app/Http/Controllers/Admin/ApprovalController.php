<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $query = Permit::with('user');

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
        $permit = Permit::with('user')->findOrFail($id);
        
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

            $updateData = [
                'status' => $config['nextStatus'],
                'catatan_revisi' => null,
                'approval_signatures' => $signatures,
            ];

            // Jika Senior Manager approve, berarti permit Active, set tanggal aktif.
            if ($config['nextStatus'] === 'Active') {
                $updateData['active_at'] = now();
                $message = 'Permit berhasil disetujui dan kini berstatus ACTIVE.';
            } else {
                $message = 'Permit berhasil disetujui dan diteruskan ke ' . $config['nextRoleName'] . '.';
            }

            $permit->update($updateData);
            
        } elseif ($action === 'revise') {
            $request->validate([
                'catatan_revisi' => 'required|string|max:500'
            ]);
            $permit->update([
                'status' => 'Revision',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            $message = 'Permit dikembalikan ke Divisi untuk direvisi.';
        }

        return redirect('/admin/approvals')->with('success', $message);
    }
    public function showCancelForm($id)
    {
        $permit = Permit::findOrFail($id);
        
        // Hanya permit Active yang bisa dibatalkan
        if ($permit->status !== 'Active') {
            return redirect('/admin/history')->with('error', 'Hanya Permit berstatus Active yang dapat dibatalkan.');
        }

        return view('admin.approvals.cancel', compact('permit'));
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
            'tanda_tangan' => 'required|string',
        ]);

        $permit = Permit::findOrFail($id);

        if ($permit->status !== 'Active') {
            return redirect('/admin/history')->with('error', 'Hanya Permit berstatus Active yang dapat dibatalkan.');
        }

        $role = Auth::user()->role;
        $roleName = $role === 'staff' ? 'Safety Officer' : ($role === 'senior-manager' ? 'SM QM & SHE' : 'Manager');
        $nama = Auth::user()->name;

        // Ambil array tanda tangan lama jika ada (untuk masa depan jika butuh 2 ttd)
        $signatures = $permit->cancellation_signatures ?? [];
        
        $signatures[] = [
            'role' => $roleName,
            'name' => $nama,
            'signature' => $request->tanda_tangan,
            'date' => now()->toDateTimeString(),
        ];

        $permit->update([
            'status' => 'Cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->cancellation_reason,
            'cancellation_signatures' => $signatures,
        ]);

        return redirect('/admin/history')->with('success', 'Izin kerja berhasil dibatalkan.');
    }
}
