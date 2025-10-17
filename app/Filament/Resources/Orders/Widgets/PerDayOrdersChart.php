<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PerDayOrdersChart extends ChartWidget
{
    protected ?string $heading = 'Per Day Orders Chart';

    protected function getData(): array
    {
        // Get today's date
        $today = now()->toDateString();

        // Get today's orders
        $orders = Order::select('id', 'grand_total', 'created_at')
            ->whereDate('created_at', $today) // Only fetch orders for today
            ->get();

        $labels = [];
        $sales = [];

        // Loop through each order and prepare the data
        foreach ($orders as $order) {
            // For each order, use the order's ID or the time of creation as the label
            // You can customize this label (e.g., order number, or the time of order)
            $labels[] = $order->id; // Using the order ID as a unique label
            $sales[]  = (float) $order->grand_total; // The total sales for that order
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales Today (By Order)',
                    'data' => $sales,
                    'backgroundColor' => '#3b82f6', // You can change this color
                ],
            ],
            'labels' => $labels, // Each order's ID or label
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
