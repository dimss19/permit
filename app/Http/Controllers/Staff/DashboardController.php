<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'pending'  => Permit::where('status', 'Review Staff')->count(),
            'revision' => Permit::where('status', 'Revision')->count(),
            'today'    => Permit::where('status', 'Review Staff')->whereDate('updated_at', today())->count(),
        ];

        $permits = Permit::with('user')
            ->where('status', 'Review Staff')
            ->orderBy('submitted_at', 'asc')
            ->take(10)
            ->get();

        return view('staff.dashboard', compact('counts', 'permits'));
    }
}
