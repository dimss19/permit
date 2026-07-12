<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id()
            ?? \App\Models\User::where('role', 'divisi')->value('id');

        $query = Permit::where('user_id', $userId)
                        ->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_permit',      'like', "%{$q}%")
                    ->orWhere('nama_pekerjaan','like', "%{$q}%")
                    ->orWhere('kontraktor',    'like', "%{$q}%");
            });
        }

        $permits = $query->paginate(15)->withQueryString();

        return view('divisi.history', compact('permits'));
    }
}
