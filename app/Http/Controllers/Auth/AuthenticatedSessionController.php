<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Redirect ke dashboard sesuai role pengguna.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $role = Auth::user()->role;

        $redirectMap = [
            'superadmin'     => '/superadmin/dashboard',
            'divisi'         => '/divisi/dashboard',
            'staff'          => '/admin/dashboard',
            'manager'        => '/admin/dashboard',
            'senior-manager' => '/admin/dashboard',
        ];

        $destination = $redirectMap[$role] ?? '/dashboard';

        return redirect()->intended($destination);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
