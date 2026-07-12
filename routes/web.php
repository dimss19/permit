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

// ===== STAFF =====
Route::get('/staff/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index']);
Route::get('/staff/approvals', [\App\Http\Controllers\Staff\ApprovalController::class, 'index']);
Route::get('/staff/approvals/{id}', [\App\Http\Controllers\Staff\ApprovalController::class, 'show']);
Route::put('/staff/approvals/{id}', [\App\Http\Controllers\Staff\ApprovalController::class, 'update']);
Route::get('/staff/history', [\App\Http\Controllers\Staff\HistoryController::class, 'index']);

// ===== MANAGER =====
Route::get('/manager/dashboard', [\App\Http\Controllers\Manager\DashboardController::class, 'index']);
Route::get('/manager/approvals', [\App\Http\Controllers\Manager\ApprovalController::class, 'index']);
Route::get('/manager/approvals/{id}', [\App\Http\Controllers\Manager\ApprovalController::class, 'show']);
Route::put('/manager/approvals/{id}', [\App\Http\Controllers\Manager\ApprovalController::class, 'update']);
Route::get('/manager/history', [\App\Http\Controllers\Manager\HistoryController::class, 'index']);

// ===== SENIOR MANAGER =====
Route::get('/senior-manager/dashboard', [\App\Http\Controllers\SeniorManager\DashboardController::class, 'index']);
Route::get('/senior-manager/approvals', [\App\Http\Controllers\SeniorManager\ApprovalController::class, 'index']);
Route::get('/senior-manager/approvals/{id}', [\App\Http\Controllers\SeniorManager\ApprovalController::class, 'show']);
Route::put('/senior-manager/approvals/{id}', [\App\Http\Controllers\SeniorManager\ApprovalController::class, 'update']);
Route::get('/senior-manager/history', [\App\Http\Controllers\SeniorManager\HistoryController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
