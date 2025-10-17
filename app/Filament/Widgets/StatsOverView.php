<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rider;
use App\Models\Table;
use App\Models\Waiter;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverView extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Riders', Rider::count())
                ->description('Total Riders')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),

            Stat::make('Total Orders', Order::count())
                ->description('Total Orders')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Products', Product::count())
                ->description('Total Products')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Categories', Category::count())
                ->description('Total Categories')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Waiters', Waiter::count())
                ->description('Total Vendors')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Tables', Table::count())
                ->description('Total Tables')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
