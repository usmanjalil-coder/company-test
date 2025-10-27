<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ClientCareController;
use App\Http\Controllers\VisaDetailController;
use App\Http\Controllers\ClosingLetterController;
use App\Http\Controllers\OutcomeLetterController;
use App\Http\Controllers\AttendanceNoteController;
use App\Http\Controllers\CoveringLetterController;
use App\Http\Controllers\FollowupLetterController;
use App\Http\Controllers\InitialContactController;
use App\Http\Controllers\AuthorityLetterController;
use App\Http\Controllers\LedgerStatementController;
use App\Http\Controllers\BalanceStatementController;

Route::get('/company/index', [AdminController::class, 'index'])->name('company.index');
Route::get('/company/create', [AdminController::class, 'create'])->name('company.create');
Route::post('/company/store', [AdminController::class, 'store'])->name('company.store');
Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('company.edit');
Route::put('/admin/{id}', [AdminController::class, 'update'])->name('company.update');
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('company.delete');

Route::resource('companies', CompanyController::class);
Route::resource('authority-letters', AuthorityLetterController::class);
Route::resource('client-care', ClientCareController::class);
Route::resource('initial-contacts', InitialContactController::class);
Route::resource('covering-letters', CoveringLetterController::class);
Route::resource('documents', DocumentController::class);
Route::resource('attendance-notes', AttendanceNoteController::class);
Route::resource('followup-letters', FollowupLetterController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('receipts', ReceiptController::class);
Route::resource('outcome-letters', OutcomeLetterController::class);
Route::resource('closing-letters', ClosingLetterController::class);
Route::resource('ledger-statements', LedgerStatementController::class);
Route::resource('balance-statements', BalanceStatementController::class);
Route::resource('visa-details', VisaDetailController::class);

Route::get('/', function () {
    return view('index');
});
