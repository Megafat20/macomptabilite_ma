<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvoiceController;

Auth::routes();

use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::put('factures/{id}/status', [InvoiceController::class, 'updateStatus'])->name('factures.update-status');
    Route::resource('factures', InvoiceController::class);
    
    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
