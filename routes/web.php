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
        'staff'          => '/admin/dashboard',
        'manager'        => '/admin/dashboard',
        'senior-manager' => '/admin/dashboard',
    ];
    return redirect($map[Auth::user()->role] ?? '/');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== SUPER ADMIN =====
Route::get('/superadmin/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index']);
Route::get('/superadmin/users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'index']);
Route::post('/superadmin/users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'store']);
Route::patch('/superadmin/users/{id}/status', [\App\Http\Controllers\SuperAdmin\UserController::class, 'updateStatus']);
Route::patch('/superadmin/users/{id}/reset-password', [\App\Http\Controllers\SuperAdmin\UserController::class, 'resetPassword']);

// ===== DIVISI (menggunakan controller + data nyata) =====
Route::get('/divisi/dashboard', [DivisiDashboard::class, 'index']);
Route::get('/divisi/history', [\App\Http\Controllers\Divisi\HistoryController::class, 'index']);
Route::get('/divisi/permits/create', [\App\Http\Controllers\Divisi\PermitController::class, 'create']);
Route::post('/divisi/permits', [\App\Http\Controllers\Divisi\PermitController::class, 'store']);
Route::get('/divisi/permits/{id}', [\App\Http\Controllers\Divisi\PermitShowController::class, 'show']);
Route::get('/divisi/permits/{id}/edit', [\App\Http\Controllers\Divisi\PermitController::class, 'edit']);
Route::put('/divisi/permits/{id}', [\App\Http\Controllers\Divisi\PermitController::class, 'update']);

// ===== ADMIN (Staff, Manager, Senior Manager) =====
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/admin/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index']);
    Route::get('/admin/approvals/{id}', [\App\Http\Controllers\Admin\ApprovalController::class, 'show']);
    Route::put('/admin/approvals/{id}', [\App\Http\Controllers\Admin\ApprovalController::class, 'update']);
    Route::get('/admin/history', [\App\Http\Controllers\Admin\HistoryController::class, 'index']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
