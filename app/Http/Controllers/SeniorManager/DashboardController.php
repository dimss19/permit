<?php

namespace App\Http\Controllers\SeniorManager;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'pending' => Permit::where('status', 'Review Senior Manager')->count(),
            'active_today' => Permit::where('status', 'Active')->whereDate('updated_at', today())->count(),
        ];

        $permits = Permit::with('user')
            ->where('status', 'Review Senior Manager')
            ->orderBy('updated_at', 'asc') // Paling lama menunggu di atas
            ->take(10)
            ->get();

        return view('senior_manager.dashboard', compact('counts', 'permits'));
    }
}
