<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderPrintController;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/orders/{order}/print/kitchen', [OrderPrintController::class, 'kitchen'])->name('orders.print.kitchen');
Route::get('/orders/{order}/print/receipt', [OrderPrintController::class, 'receipt'])->name('orders.print.receipt');
Route::get('/orders/{order}/print/paid', [OrderPrintController::class, 'paid'])->name('orders.print.paid');
// 🧾 item Sale PDF & Print routes
Route::get('/item-sales/download-pdf', [ReportController::class, 'downloadPdf'])->name('item-sales.pdf.download');
Route::get('/item-sales/print', [ReportController::class, 'printView'])->name('item-sales.print');
// 🧾 Sales Order PDF & Print routes
Route::get('/sales-orders/download-pdf', [ReportController::class, 'downloadSalesOrderPdf'])->name('sales-orders.pdf.download');
Route::get('/sales-orders/print', [ReportController::class, 'printSalesOrderView'])->name('sales-orders.print');
// 🧾 Expense PDF & Print routes
Route::get('/expense/download-pdf', [ExpenseController::class, 'downloadSalesOrderPdf'])->name('expense.pdf.download');
Route::get('/expense/print', [ExpenseController::class, 'printSalesOrderView'])->name('expense.print');
