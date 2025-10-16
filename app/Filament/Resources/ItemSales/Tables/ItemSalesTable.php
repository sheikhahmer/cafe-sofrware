<?php

namespace App\Filament\Resources\ItemSales\Tables;

use App\Models\OrderItem;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ItemSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => OrderItem::query()
                    ->selectRaw('MIN(order_id) as order_id, category_id as id, category_id, product_id, SUM(quantity) as total_quantity, SUM(total) as total_sales')
                    ->groupBy('category_id', 'product_id')

                    ->with(['category', 'product'])
            // Remove the default filter from here - show ALL data by default
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_quantity')
                    ->label('Total Quantity Sold')
                    ->sortable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('total_sales')
                    ->label('Total Sales')
                    ->money('PKR')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('today')
                    ->label('Today')
                    ->query(fn(Builder $query) => $query->whereDate('created_at', today())),

                Filter::make('yesterday')
                    ->label('Yesterday')
                    ->query(fn(Builder $query) => $query->whereDate('created_at', today()->subDay())),

                Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn(Builder $query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),

                Filter::make('this_month')
                    ->label('This Month')
                    ->query(fn(Builder $query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])),
            ])
            ->headerActions([
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $filters = $livewire->tableFilters ?? [];
                        $search = $livewire->tableSearch ?? '';

                        Log::info('=== PDF ACTION - FILTERS ===');
                        Log::info('Filters in action:', $filters);

                        // Extract the isActive value from nested structure
                        $filterData = [];
                        foreach (['today', 'yesterday', 'this_week', 'this_month'] as $filter) {
                            $isActive = isset($filters[$filter]['isActive']) && $filters[$filter]['isActive'] === true ? '1' : '0';
                            $filterData[$filter] = $isActive;
                            Log::info("Filter {$filter}: isActive = {$isActive}");
                        }

                        $downloadUrl = route('item-sales.pdf.download', [
                            'filters' => $filterData,
                            'search' => $search,
                            'time' => now()->timestamp
                        ]);

                        Log::info('Redirecting to: ' . $downloadUrl);
                        return redirect($downloadUrl);
                    }),

                Action::make('print')
                    ->label('Print Report')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(function ($livewire) {
                        $filters = $livewire->tableFilters ?? [];
                        $search = $livewire->tableSearch ?? '';

                        $filterData = [];
                        foreach (['today', 'yesterday', 'this_week', 'this_month'] as $filter) {
                            $isActive = isset($filters[$filter]['isActive']) && $filters[$filter]['isActive'] === true ? '1' : '0';
                            $filterData[$filter] = $isActive;
                        }

                        $printUrl = route('item-sales.print', [
                            'filters' => $filterData,
                            'search' => $search,
                            'time' => now()->timestamp
                        ]);

                        $livewire->js("window.open('{$printUrl}', '_blank')");
                    }),
            ])
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(50);
    }
}
