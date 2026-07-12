<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'total'    => User::where('role', 'divisi')->count(),
            'aktif'    => User::where('role', 'divisi')->where('is_active', true)->count(),
            'nonaktif' => User::where('role', 'divisi')->where('is_active', false)->count(),
        ];

        $users = User::where('role', 'divisi')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('counts', 'users'));
    }
}
