<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dummy routes for previewing the newly created dashboards
Route::get('/superadmin/dashboard', function () {
    return view('superadmin.dashboard');
});

Route::get('/divisi/dashboard', function () {
    return view('divisi.dashboard');
});

Route::get('/staff/dashboard', function () {
    return view('staff.dashboard');
});

Route::get('/manager/dashboard', function () {
    return view('manager.dashboard');
});

Route::get('/senior-manager/dashboard', function () {
    return view('senior_manager.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
