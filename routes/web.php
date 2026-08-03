<?php

use App\Http\Controllers\ProfileController;
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

// ===== SUPER ADMIN (role:superadmin) =====
Route::middleware(['auth', 'prevent-back-history', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index']);
    Route::resource('/superadmin/divisions', \App\Http\Controllers\SuperAdmin\DivisionController::class)->except(['show']);
    Route::get('/superadmin/users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'index']);
    Route::get('/superadmin/users/create', [\App\Http\Controllers\SuperAdmin\UserController::class, 'create']);
    Route::post('/superadmin/users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'store']);
    Route::get('/superadmin/users/{id}/edit', [\App\Http\Controllers\SuperAdmin\UserController::class, 'edit']);
    Route::put('/superadmin/users/{id}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'update']);
    Route::delete('/superadmin/users/{id}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'destroy']);
    Route::patch('/superadmin/users/{id}/status', [\App\Http\Controllers\SuperAdmin\UserController::class, 'updateStatus']);
    Route::patch('/superadmin/users/{id}/reset-password', [\App\Http\Controllers\SuperAdmin\UserController::class, 'resetPassword']);
    Route::get('/superadmin/permits/{id}/pdf', [\App\Http\Controllers\SuperAdmin\PermitController::class, 'downloadPdf']);
});

// ===== DIVISI (role:divisi) =====
Route::middleware(['auth', 'prevent-back-history', 'role:divisi'])->group(function () {
    Route::get('/divisi/dashboard', [\App\Http\Controllers\Divisi\DashboardController::class, 'index']);
    Route::get('/divisi/cancellations', [\App\Http\Controllers\Divisi\CancellationController::class, 'index']);
    Route::get('/divisi/cancellations/{id}', [\App\Http\Controllers\Divisi\CancellationController::class, 'show']);
    Route::post('/divisi/cancellations/{id}', [\App\Http\Controllers\Divisi\CancellationController::class, 'cancel']);
    Route::get('/divisi/history', [\App\Http\Controllers\Divisi\HistoryController::class, 'index']);
    Route::get('/divisi/permits/create', [\App\Http\Controllers\Divisi\PermitController::class, 'create']);
    Route::post('/divisi/permits', [\App\Http\Controllers\Divisi\PermitController::class, 'store']);
    Route::get('/divisi/permits/{id}', [\App\Http\Controllers\Divisi\PermitShowController::class, 'show']);
    Route::get('/divisi/permits/{id}/pdf', [\App\Http\Controllers\Divisi\PermitShowController::class, 'downloadPdf']);
    Route::get('/divisi/permits/{id}/edit', [\App\Http\Controllers\Divisi\PermitController::class, 'edit']);
    Route::put('/divisi/permits/{id}', [\App\Http\Controllers\Divisi\PermitController::class, 'update']);
    Route::get('/divisi/permits/{permitId}/documents/{documentId}/download', [\App\Http\Controllers\Divisi\PermitController::class, 'downloadDocument'])->name('permits.documents.download');
});

// ===== ADMIN (role:staff, manager, senior-manager) =====
Route::middleware(['auth', 'prevent-back-history', 'role:staff,manager,senior-manager'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/admin/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index']);
    Route::get('/admin/approvals/{id}', [\App\Http\Controllers\Admin\ApprovalController::class, 'show']);
    Route::put('/admin/approvals/{id}', [\App\Http\Controllers\Admin\ApprovalController::class, 'update']);
    Route::get('/admin/approvals/{permitId}/documents/{documentId}/download', [\App\Http\Controllers\Admin\ApprovalController::class, 'downloadDocument'])->name('admin.permits.documents.download');
    Route::get('/admin/history', [\App\Http\Controllers\Admin\HistoryController::class, 'index']);
    Route::get('/admin/permits/{id}/pdf', [\App\Http\Controllers\Admin\ApprovalController::class, 'downloadPdf']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
