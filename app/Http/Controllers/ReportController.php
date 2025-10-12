<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrderItem;
use App\Models\Category;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Generate/download a category-product grouped report.
     * Accepts optional `filter` query param: today | week | month | all
     */
    public function categorySalesReport(Request $request, $mode = 'download')
    {
        $filter = $request->query('filter', 'today');

        $dateQuery = function ($query) use ($filter) {
            if ($filter === 'today') {
                $query->whereDate('bill_date', today());
            } elseif ($filter === 'week') {
                $query->whereBetween('bill_date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter === 'month') {
                $query->whereMonth('bill_date', now()->month)->whereYear('bill_date', now()->year);
            } // 'all' -> no date filter
        };

        // join with orders via whereHas to apply date filter on order bills
        $itemsQuery = OrderItem::query()
            ->selectRaw('category_id, product_id, SUM(quantity) as total_quantity, SUM(total) as total_sales')
            ->whereHas('order', $dateQuery)
            ->groupBy('category_id', 'product_id')
            ->with(['category', 'product']);

        $items = $itemsQuery->get()->groupBy('category_id');

        $categoryIds = $items->keys()->toArray();
        $categories = Category::whereIn('id', $categoryIds)->get()->keyBy('id');

        $today = Carbon::today();

        $pdf = Pdf::loadView('reports.item-sales-category', compact('items', 'categories', 'today', 'filter'))
            ->setPaper('A4', 'portrait');

        if ($mode === 'stream') {
            return $pdf->stream('item_sales_' . $filter . '_' . $today->format('Y_m_d') . '.pdf');
        }

        return $pdf->download('item_sales_' . $filter . '_' . $today->format('Y_m_d') . '.pdf');
    }

    // convenience wrappers
    public function downloadCategoryReport(Request $request)
    {
        return $this->categorySalesReport($request, 'download');
    }

    public function printCategoryReport(Request $request)
    {
        return $this->categorySalesReport($request, 'stream');
    }
}
