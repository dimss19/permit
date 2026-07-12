<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        
        $roleName = match($role) {
            'staff' => 'Staff',
            'manager' => 'Manager',
            'senior-manager' => 'Senior Manager',
            default => 'Admin'
        };

        $query = Permit::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_permit', 'like', "%{$q}%")
                    ->orWhere('nama_pekerjaan', 'like', "%{$q}%")
                    ->orWhere('kontraktor', 'like', "%{$q}%")
                    ->orWhereHas('user', function($userQ) use ($q) {
                        $userQ->where('name', 'like', "%{$q}%");
                    });
            });
        }

        // History shows newest first
        $permits = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.history.index', compact('permits', 'roleName'));
    }
}
