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

Route::get('/reports/item-sales-category/download', [ReportController::class, 'downloadCategoryReport'])
    ->name('reports.item_sales.category.download');

Route::get('/reports/item-sales-category/print', [ReportController::class, 'printCategoryReport'])
    ->name('reports.item_sales.category.print');
