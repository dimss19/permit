<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Permit::with('user');

        if ($request->date === 'today') {
            $query->whereDate('updated_at', today())->where('status', 'Review Manager');
        } elseif ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'Review Manager');
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_permit', 'like', "%{$q}%")
                    ->orWhere('nama_pekerjaan', 'like', "%{$q}%")
                    ->orWhere('kontraktor', 'like', "%{$q}%");
            });
        }

        $permits = $query->orderBy('updated_at', 'asc')->paginate(15)->withQueryString();

        return view('manager.approvals.index', compact('permits'));
    }

    public function show($id)
    {
        $permit = Permit::with('user')->findOrFail($id);
        
        $canReview = $permit->status === 'Review Manager';

        return view('manager.approvals.show', compact('permit', 'canReview'));
    }

    public function update(Request $request, $id)
    {
        $permit = Permit::findOrFail($id);

        if ($permit->status !== 'Review Manager') {
            return redirect('/manager/approvals')->with('error', 'Permit tidak valid untuk direview.');
        }

        $action = $request->input('action');

        if ($action === 'approve') {
            $permit->update([
                'status' => 'Review Senior Manager',
                'catatan_revisi' => null
            ]);
            $message = 'Permit berhasil disetujui dan diteruskan ke Senior Manager.';
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

        return redirect('/manager/approvals')->with('success', $message);
    }
}
