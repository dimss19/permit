<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Permit::with('user');

        // Untuk tab "Review Permit", staff default melihat status 'Review Staff'
        // Jika ada filter date=today
        if ($request->date === 'today') {
            $query->whereDate('updated_at', today())->where('status', 'Review Staff');
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default show only those waiting for staff
            $query->where('status', 'Review Staff');
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_permit', 'like', "%{$q}%")
                    ->orWhere('nama_pekerjaan', 'like', "%{$q}%")
                    ->orWhere('kontraktor', 'like', "%{$q}%");
            });
        }

        $permits = $query->orderBy('submitted_at', 'asc')->paginate(15)->withQueryString();

        return view('staff.approvals.index', compact('permits'));
    }

    public function show($id)
    {
        $permit = Permit::with('user')->findOrFail($id);
        
        // Hanya bisa mereview jika statusnya Review Staff
        $canReview = $permit->status === 'Review Staff';

        return view('staff.approvals.show', compact('permit', 'canReview'));
    }

    public function update(Request $request, $id)
    {
        $permit = Permit::findOrFail($id);

        if ($permit->status !== 'Review Staff') {
            return redirect('/staff/approvals')->with('error', 'Permit tidak valid untuk direview.');
        }

        $action = $request->input('action'); // 'approve' atau 'revise'

        if ($action === 'approve') {
            $permit->update([
                'status' => 'Review Manager',
                'catatan_revisi' => null // Clear catatan revisi jika sebelumnya ada
            ]);
            $message = 'Permit berhasil disetujui dan diteruskan ke Manager.';
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

        return redirect('/staff/approvals')->with('success', $message);
    }
}
