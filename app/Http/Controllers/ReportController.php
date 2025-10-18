<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function downloadPdf(Request $request)
    {
        // Get filters from request
        $filters = $request->get('filters', []);
        $search = $request->get('search', '');

        \Log::info('=== PDF CONTROLLER - START ===');
        \Log::info('Received filters:', $filters);
        \Log::info('Received search:', ['search' => $search]);

        // Start with base query
        $query = OrderItem::query();

        // Apply date filters - check for '1' (string) since we converted them
        $activeFilter = 'all';

        if (isset($filters['today']) && $filters['today'] === '1') {
            $query->whereDate('created_at', today());
            $activeFilter = 'today';
            \Log::info('✅ Applied TODAY filter');
        }
        elseif (isset($filters['yesterday']) && $filters['yesterday'] === '1') {
            $query->whereDate('created_at', today()->subDay());
            $activeFilter = 'yesterday';
            \Log::info('✅ Applied YESTERDAY filter');
        }
        elseif (isset($filters['this_week']) && $filters['this_week'] === '1') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $activeFilter = 'this_week';
            \Log::info('✅ Applied THIS WEEK filter');
        }
        elseif (isset($filters['this_month']) && $filters['this_month'] === '1') {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
            $activeFilter = 'this_month';
            \Log::info('✅ Applied THIS MONTH filter');
        }
        else {
            \Log::info('🔄 No date filter applied - showing ALL data');
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
            \Log::info('🔍 Applied search: ' . $search);
        }

        // Now apply grouping and get the data
        $data = $query
            ->select('order_id', 'category_id', 'product_id')
            ->selectRaw('SUM(quantity) as total_quantity, SUM(total) as total_sales')
            ->groupBy('order_id', 'category_id', 'product_id')
            ->with(['category', 'product'])
            ->orderBy('order_id')
            ->get();


        \Log::info('=== PDF CONTROLLER - RESULTS ===');
        \Log::info('Active filter: ' . $activeFilter);
        \Log::info('Data count: ' . $data->count());
        \Log::info('Sample data:', $data->take(2)->map(function($item) {
            return [
                'category' => $item->category->name ?? 'N/A',
                'product' => $item->product->name ?? 'N/A',
                'quantity' => $item->total_quantity,
                'sales' => $item->total_sales,
            ];
        })->toArray());

        $filename = 'item-sales-report-' . $activeFilter . '-' . now()->format('Y-m-d-H-i') . '.pdf';

        $pdf = Pdf::loadView('pdf.item-sales', [
            'data' => $data,
            'activeFilter' => $activeFilter
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
    public function printView(Request $request)
    {
        $filters = $request->get('filters', []);
        $search = $request->get('search', '');

        \Log::info('=== PRINT VIEW - START ===');
        \Log::info('Received filters:', $filters);
        \Log::info('Received search:', ['search' => $search]);

        $query = OrderItem::query()
            ->selectRaw('order_id, category_id, product_id, SUM(quantity) as total_quantity, SUM(total) as total_sales')
            ->groupBy('order_id', 'category_id', 'product_id')
            ->with(['category', 'product'])
            ->orderBy('order_id');

        $activeFilter = 'all';

        // 🔍 Apply filters - check for '1' (string) like in PDF download
        if (isset($filters['today']) && $filters['today'] === '1') {
            $query->whereDate('created_at', today());
            $activeFilter = 'today';
            \Log::info('✅ Applied TODAY filter for print');
        }
        elseif (isset($filters['yesterday']) && $filters['yesterday'] === '1') {
            $query->whereDate('created_at', today()->subDay());
            $activeFilter = 'yesterday';
            \Log::info('✅ Applied YESTERDAY filter for print');
        }
        elseif (isset($filters['this_week']) && $filters['this_week'] === '1') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $activeFilter = 'this_week';
            \Log::info('✅ Applied THIS WEEK filter for print');
        }
        elseif (isset($filters['this_month']) && $filters['this_month'] === '1') {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
            $activeFilter = 'this_month';
            \Log::info('✅ Applied THIS MONTH filter for print');
        }
        else {
            \Log::info('🔄 No date filter applied for print - showing ALL data');
        }

        // 🔍 Apply search if provided
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
            \Log::info('🔍 Applied search for print: ' . $search);
        }

        $data = $query->get();

        \Log::info('=== PRINT VIEW - RESULTS ===');
        \Log::info('Active filter: ' . $activeFilter);
        \Log::info('Data count: ' . $data->count());

        return view('pdf.item-sales-print', [
            'data' => $data,
            'activeFilter' => $activeFilter
        ]);
    }

//    for sales order
    public function downloadSalesOrderPdf(Request $request)
    {
        \Log::info('=== SALES ORDER PDF CONTROLLER - START ===');

        // Business-day range: 10 AM today → 4 AM next day
        $now = now();
        $startOfDay = $now->copy()->setTime(10, 0, 0);
        if ($now->lt($startOfDay)) {
            $startOfDay->subDay();
        }
        $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);

        \Log::info('📅 Date range:', [
            'start' => $startOfDay->toDateTimeString(),
            'end' => $endOfDay->toDateTimeString(),
        ]);

        $query = Order::query()
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->orderBy('created_at', 'desc');

        // Optional search
        $search = $request->get('search', '');
        if (!empty($search)) {
            $query->where('customer_name', 'like', "%{$search}%");
        }

        $orders = $query->get();

        \Log::info('Orders count: ' . $orders->count());

        $filename = 'sales-orders-report-' . now()->format('Y-m-d-H-i') . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sales-orders', [
            'orders' => $orders,
            'startOfDay' => $startOfDay,
            'endOfDay' => $endOfDay,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function printSalesOrderView(Request $request)
    {
        \Log::info('=== SALES ORDER PRINT VIEW - START ===');

        // Business-day range: 10 AM today → 4 AM next day
        $now = now();
        $startOfDay = $now->copy()->setTime(10, 0, 0);
        if ($now->lt($startOfDay)) {
            $startOfDay->subDay();
        }
        $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);

        $query = Order::query()
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->orderBy('created_at', 'desc');

        // Optional search
        $search = $request->get('search', '');
        if (!empty($search)) {
            $query->where('customer_name', 'like', "%{$search}%");
        }

        $orders = $query->get();

        return view('pdf.sales-orders-print', [
            'orders' => $orders,
            'startOfDay' => $startOfDay,
            'endOfDay' => $endOfDay,
        ]);
    }

}
