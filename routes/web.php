<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Divisi\DashboardController as DivisiDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }
    $map = [
        'superadmin'     => '/superadmin/dashboard',
        'divisi'         => '/divisi/dashboard',
        'staff'          => '/staff/dashboard',
        'manager'        => '/manager/dashboard',
        'senior-manager' => '/senior-manager/dashboard',
    ];
    return redirect($map[Auth::user()->role] ?? '/');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== SUPERADMIN =====
Route::get('/superadmin/dashboard', function () {
    return view('superadmin.dashboard');
});
Route::get('/superadmin/users', function () {
    return view('superadmin.dashboard'); // placeholder
});

// ===== DIVISI (menggunakan controller + data nyata) =====
Route::get('/divisi/dashboard', [DivisiDashboard::class, 'index']);
Route::get('/divisi/history', [\App\Http\Controllers\Divisi\HistoryController::class, 'index']);
Route::get('/divisi/permits/create', [\App\Http\Controllers\Divisi\PermitController::class, 'create']);
Route::post('/divisi/permits', [\App\Http\Controllers\Divisi\PermitController::class, 'store']);
Route::get('/divisi/permits/{id}', [\App\Http\Controllers\Divisi\PermitShowController::class, 'show']);
Route::get('/divisi/permits/{id}/edit', [\App\Http\Controllers\Divisi\PermitController::class, 'edit']);
Route::put('/divisi/permits/{id}', [\App\Http\Controllers\Divisi\PermitController::class, 'update']);

// ===== STAFF =====
Route::get('/staff/dashboard', function () {
    return view('staff.dashboard');
});
Route::get('/staff/approvals', function () {
    return view('staff.dashboard'); // placeholder
});
Route::get('/staff/history', function () {
    return view('staff.dashboard'); // placeholder
});

// ===== MANAGER =====
Route::get('/manager/dashboard', function () {
    return view('manager.dashboard');
});
Route::get('/manager/approvals', function () {
    return view('manager.dashboard'); // placeholder
});
Route::get('/manager/history', function () {
    return view('manager.dashboard'); // placeholder
});

// ===== SENIOR MANAGER =====
Route::get('/senior-manager/dashboard', function () {
    return view('senior_manager.dashboard');
});
Route::get('/senior-manager/approvals', function () {
    return view('senior_manager.dashboard'); // placeholder
});
Route::get('/senior-manager/history', function () {
    return view('senior_manager.dashboard'); // placeholder
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
