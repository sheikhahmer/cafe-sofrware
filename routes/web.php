<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderPrintController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/orders/{order}/print/kitchen', [OrderPrintController::class, 'kitchen'])->name('orders.print.kitchen');
Route::get('/orders/{order}/print/receipt', [OrderPrintController::class, 'receipt'])->name('orders.print.receipt');
Route::get('/orders/{order}/print/paid', [OrderPrintController::class, 'paid'])->name('orders.print.paid');
Route::get('/item-sales/download-pdf', [ReportController::class, 'downloadPdf'])->name('item-sales.pdf.download');
Route::get('/item-sales/print', [ReportController::class, 'printView'])->name('item-sales.print');
