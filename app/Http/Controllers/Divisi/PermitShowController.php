<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use App\Models\Permit;
use Illuminate\Support\Facades\Auth;

class PermitShowController extends Controller
{
    public function show($id)
    {
        $userId = Auth::id()
            ?? \App\Models\User::where('role', 'divisi')->value('id');

        $permit = Permit::where('user_id', $userId)
                         ->where('id', $id)
                         ->firstOrFail();

        return view('divisi.permits.show', compact('permit'));
    }
}
