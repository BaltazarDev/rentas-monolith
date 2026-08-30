<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ImportController;

// Guest Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    Route::get('/database/backup', [DatabaseBackupController::class, 'download'])->name('database.backup');
    
    Route::get('/import', [ImportController::class, 'show'])->name('import.show');
    Route::post('/import', [ImportController::class, 'upload'])->name('import.upload');
    Route::get('/import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');
    
    Route::resource('houses', HouseController::class);
    Route::resource('units', UnitController::class)->except(['index', 'destroy']);
    Route::resource('tenants', TenantController::class);
});

