<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/company/index', [AdminController::class, 'index'])->name('company.index');
Route::get('/company/create', [AdminController::class, 'create'])->name('company.create');
Route::post('/company/store', [AdminController::class, 'store'])->name('company.store');
Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('company.edit');
Route::put('/admin/{id}', [AdminController::class, 'update'])->name('company.update');
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('company.delete');

// NEW: delete a single logo (company_logo or an accreditor logo) via AJAX POST
Route::post('/company/{id}/logo-delete', [AdminController::class, 'deleteLogo'])->name('company.logo.delete');

Route::get('/', function () {
    return view('index');
});
