<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PerDayOrdersChart extends ChartWidget
{
    protected ?string $heading = 'Per Day Orders Sales Chart';

    protected function getData(): array
    {
        $now = now();

        // Start of business day → today at 10 AM
        $startOfDay = $now->copy()->setTime(10, 0, 0);

        // If current time is before 10 AM, move window to previous day
        if ($now->lt($startOfDay)) {
            $startOfDay->subDay();
        }

        // End of business day → next day 4 AM
        $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);

        // 🟢 Fetch all orders within that 10 AM → 4 AM window
        $orders = Order::select('id', 'grand_total', 'created_at')
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->orderBy('created_at')
            ->get();

        $labels = [];
        $sales = [];

        foreach ($orders as $order) {
            // Example label: 10:30 AM or you can use $order->id
            $labels[] = $order->created_at->format('h:i A');
            $sales[]  = (float) $order->grand_total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales (10 AM – 4 AM Window)',
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
