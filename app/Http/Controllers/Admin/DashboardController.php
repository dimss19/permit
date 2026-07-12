<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        $today = now()->startOfDay();

        $counts = [];

        if ($role === 'staff') {
            $counts = [
                'pending' => Permit::where('status', 'Review Staff')->count(),
                'revision' => Permit::where('status', 'Revision')->count(),
                'today' => Permit::where('status', 'Review Staff')->whereDate('updated_at', $today)->count(),
            ];
            $permits = Permit::with('user')
                ->where('status', 'Review Staff')
                ->orderBy('submitted_at', 'asc')
                ->get();
        } 
        elseif ($role === 'manager') {
            $counts = [
                'pending' => Permit::where('status', 'Review Manager')->count(),
                'today' => Permit::where('status', 'Review Manager')->whereDate('updated_at', $today)->count(),
            ];
            $permits = Permit::with('user')
                ->where('status', 'Review Manager')
                ->orderBy('updated_at', 'asc')
                ->get();
        } 
        elseif ($role === 'senior-manager') {
            $counts = [
                'pending' => Permit::where('status', 'Review Senior Manager')->count(),
                'active_today' => Permit::where('status', 'Active')->whereDate('updated_at', $today)->count(),
            ];
            $permits = Permit::with('user')
                ->where('status', 'Review Senior Manager')
                ->orderBy('updated_at', 'asc')
                ->get();
        }

        return view('admin.dashboard', compact('counts', 'permits', 'role'));
    }
}
