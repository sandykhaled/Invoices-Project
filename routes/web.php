<?php

use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes(['register'=>false]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('invoices',InvoiceController::class);
Route::resource('sections',SectionController::class);
Route::resource('products',ProductController::class);
Route::resource('InvoiceAttachments',\App\Http\Controllers\InvoicesAttachmentsController::class);
Route::get('section/{id}',[App\Http\Controllers\InvoiceController::class,'getproducts']);
Route::get('invoiceDetails/{id}',[\App\Http\Controllers\InvoicesDetailsController::class,'edit'])->name('invoiceDetails');
Route::get('view_file/{invoice_number}/{file_name}',[\App\Http\Controllers\InvoicesDetailsController::class,'open_file'])->name('view_file');
Route::get('download_file/{invoice_number}/{file_name}',[\App\Http\Controllers\InvoicesDetailsController::class,'download_file'])->name('download_file');
Route::post('delete_file',[\App\Http\Controllers\InvoicesDetailsController::class,'destroy'])->name('delete_file');
Route::get('edit_invoice/{id}',[InvoiceController::class,'edit'])->name('edit_invoice');
Route::get('invoice_paid',[InvoiceController::class,'invoice_paid']);
Route::get('invoice_unpaid',[InvoiceController::class,'invoice_unpaid']);
Route::get('invoice_partial',[InvoiceController::class,'invoice_partial']);
Route::get('status_show/{id}',[InvoiceController::class,'show'])->name('status_show');
Route::post('/status_update/{id}', [InvoiceController::class,'status_update'])->name('status_update');
Route::resource('Archive',\App\Http\Controllers\InvoiceArchiveController::class);
Route::get('print_invoice/{id}',[InvoiceController::class,'print_invoice'])->name('print_invoice');
Route::get('export_invoices', [InvoiceController::class, 'export']);
Route::middleware('auth')->group(function (){
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
Route::get('invoices_reports',[InvoiceReportController::class,'index']);
Route::post('Search_invoices',[InvoiceReportController::class,'search_invoices']);
Route::get('customers_reports',[CustomerReportController::class,'index']);
Route::post('Search_customers',[CustomerReportController::class,'search']);
Route::get('markAsRead',[InvoiceController::class,'markAsRead'])->name('markAsRead');
Route::get('/{page}', [AdminController::class,'index']);

