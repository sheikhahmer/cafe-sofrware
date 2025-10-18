<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function downloadSalesOrderPdf(Request $request)
    {
        $search = $request->get('search', '');

        // Define 10 AM to next day 4 AM window
        $now = now();
        $startOfDay = $now->copy()->setTime(10, 0, 0);
        if ($now->lt($startOfDay)) {
            $startOfDay->subDay();
        }
        $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);

        // Query expenses for the day
        $expenses = Expense::query()
            ->when($search, fn($q) => $q->where('description', 'like', "%{$search}%"))
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.expense-report', [
            'expenses' => $expenses,
            'startOfDay' => $startOfDay,
            'endOfDay' => $endOfDay,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('daily-expense-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // ✅ Print View
    public function printSalesOrderView(Request $request)
    {
        $search = $request->get('search', '');

        $now = now();
        $startOfDay = $now->copy()->setTime(10, 0, 0);
        if ($now->lt($startOfDay)) {
            $startOfDay->subDay();
        }
        $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);

        $expenses = Expense::query()
            ->when($search, fn($q) => $q->where('description', 'like', "%{$search}%"))
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pdf.expense-print', [
            'expenses' => $expenses,
            'startOfDay' => $startOfDay,
            'endOfDay' => $endOfDay,
        ]);
    }
}
