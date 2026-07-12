<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // History untuk Staff melihat semua permit, atau bisa difilter
        $query = Permit::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_permit', 'like', "%{$q}%")
                    ->orWhere('nama_pekerjaan', 'like', "%{$q}%")
                    ->orWhere('kontraktor', 'like', "%{$q}%");
            });
        }

        $permits = $query->paginate(15)->withQueryString();

        return view('staff.history.index', compact('permits'));
    }
}
