<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function downloadPdf(Request $request)
    {
        // Build the base query
        $query = OrderItem::query()
            ->selectRaw('category_id as id, category_id, product_id, SUM(quantity) as total_quantity, SUM(total) as total_sales')
            ->groupBy('category_id', 'product_id')
            ->with(['category', 'product', 'order']);

        $filters = $request->get('filters', []);
        $search = $request->get('search', '');

        \Log::info('=== PDF DOWNLOAD DEBUG ===');
        \Log::info('Filters received:', $filters);
        \Log::info('Search received:', ['search' => $search]);

        // Apply filters - check isActive flag
        $activeFilter = 'all'; // Default to all data

        if (empty($filters)) {
            \Log::info('No filters received - showing all data');
            // No filters at all - show all data
        } else {
            // Check which filter is actually active (isActive = "1")
            if (isset($filters['today']['isActive']) && $filters['today']['isActive'] === "1") {
                $query->whereHas('order', fn($q) => $q->whereDate('created_at', today()));
                $activeFilter = 'today';
                \Log::info('Applied TODAY filter');
            }
            elseif (isset($filters['this_week']['isActive']) && $filters['this_week']['isActive'] === "1") {
                $query->whereHas('order', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]));
                $activeFilter = 'this_week';
                \Log::info('Applied THIS WEEK filter');
            }
            elseif (isset($filters['this_month']['isActive']) && $filters['this_month']['isActive'] === "1") {
                $query->whereHas('order', fn($q) => $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]));
                $activeFilter = 'this_month';
                \Log::info('Applied THIS MONTH filter');
            }
            elseif (isset($filters['all']['isActive']) && $filters['all']['isActive'] === "1") {
                $activeFilter = 'all';
                \Log::info('Applied ALL DATA filter - showing all data');
            }
            elseif (isset($filters['custom_date'])) {
                $customDate = $filters['custom_date'];
                if (isset($customDate['start_date']) && $customDate['start_date']) {
                    $query->whereHas('order', fn($q) => $q->whereDate('created_at', '>=', $customDate['start_date']));
                }
                if (isset($customDate['end_date']) && $customDate['end_date']) {
                    $query->whereHas('order', fn($q) => $q->whereDate('created_at', '<=', $customDate['end_date']));
                }
                $activeFilter = 'custom_date';
                \Log::info('Applied CUSTOM DATE filter', $customDate);
            }
            else {
                \Log::info('No active filters found in request - showing all data');
                // No active filters - show all data
            }
        }

        // Apply search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
            \Log::info('Applied SEARCH filter: ' . $search);
        }

        // Get the SQL and data
        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $data = $query->get();

        \Log::info('Query Results:', [
            'sql' => $sql,
            'bindings' => $bindings,
            'data_count' => $data->count(),
            'sample_record' => $data->first() ? [
                'category_id' => $data->first()->category_id,
                'product_id' => $data->first()->product_id,
                'total_quantity' => $data->first()->total_quantity,
                'total_sales' => $data->first()->total_sales,
                'has_category' => !is_null($data->first()->category),
                'has_product' => !is_null($data->first()->product),
                'category_name' => $data->first()->category?->name,
                'product_name' => $data->first()->product?->name,
            ] : 'No data'
        ]);

        // Generate filename
        $filename = 'item-sales-report-' . $activeFilter . '-' . now()->format('Y-m-d-H-i') . '.pdf';

        $pdf = Pdf::loadView('pdf.item-sales', [
            'data' => $data,
            'filters' => $filters,
            'search' => $search,
            'activeFilter' => $activeFilter
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
