<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'pending' => Permit::where('status', 'Review Manager')->count(),
            'today'   => Permit::where('status', 'Review Manager')->whereDate('updated_at', today())->count(),
        ];

        $permits = Permit::with('user')
            ->where('status', 'Review Manager')
            ->orderBy('updated_at', 'asc') // Paling lama menunggu di atas
            ->take(10)
            ->get();

        return view('manager.dashboard', compact('counts', 'permits'));
    }
}
