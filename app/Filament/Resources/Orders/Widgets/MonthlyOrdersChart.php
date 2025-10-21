<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyOrdersChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Orders Sales Chart';

    protected function getData(): array
    {
        $now = now();

        // We'll shift times by -10 hours to align the 10 AM–4 AM window to midnight
        $orders = Order::select(
            DB::raw("MONTH(DATE_SUB(created_at, INTERVAL 10 HOUR)) as month"),
            DB::raw('SUM(grand_total) as total_sales')
        )
            ->whereYear('created_at', $now->year)
            ->groupBy('month')
            ->pluck('total_sales', 'month')
            ->toArray();

        $labels = [];
        $sales = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = date("F", mktime(0, 0, 0, $m, 1));
            $sales[]  = isset($orders[$m]) ? (float) $orders[$m] : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $sales,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }

//    public function getColumnSpan(): int|string|array
//    {
//        return 'full';
//    }
}
