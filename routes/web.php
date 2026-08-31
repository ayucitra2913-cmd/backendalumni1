<?php

use App\Http\Controllers\Admin\AdminCrudController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// ===== AUTH ROUTES (guest only) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ===== ADMIN ROUTES (protected) =====
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/table/{table}', [DashboardController::class, 'showTable'])->name('table.show');

    // Live Search API
    Route::get('/api/live-search', [\App\Http\Controllers\SearchController::class, 'search'])->name('api.live-search');

    // Generic CRUD (Create, Update, Delete) untuk semua 13 tabel
    Route::post('/table/{table}', [AdminCrudController::class, 'store'])->name('table.store');
    Route::put('/table/{table}/{id}', [AdminCrudController::class, 'update'])->name('table.update');
    Route::delete('/table/{table}/{id}', [AdminCrudController::class, 'destroy'])->name('table.destroy');
});
