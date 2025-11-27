<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyOrdersChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Orders Sales Chart';

    protected function getData(): array
    {
        // Get orders from the last 12 months
        $startDate = now()->subMonths(11)->startOfMonth();
        
        $orders = Order::select(
            DB::raw("YEAR(created_at) as year"),
            DB::raw("MONTH(created_at) as month"),
            DB::raw('SUM(grand_total) as total_sales')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Create array with last 12 months
        $labels = [];
        $sales = [];
        $dataMap = [];

        // Map the data
        foreach ($orders as $order) {
            $key = $order->year . '-' . str_pad($order->month, 2, '0', STR_PAD_LEFT);
            $dataMap[$key] = (float) $order->total_sales;
        }

        // Generate last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $monthName = $date->format('M Y');
            
            $labels[] = $monthName;
            $sales[] = isset($dataMap[$key]) ? $dataMap[$key] : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales (Rs)',
                    'data' => $sales,
                    'backgroundColor' => '#F0A202',
                    'borderColor' => '#F0A202',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('Admin');
    }

//    public function getColumnSpan(): int|string|array
//    {
//        return 'full';
//    }
}
