<?php

namespace App\Filament\Resources\ItemSales\Tables;

use App\Models\OrderItem;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class ItemSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => OrderItem::query()
                    ->selectRaw('category_id as id, category_id, product_id, SUM(quantity) as total_quantity, SUM(total) as total_sales')
                    ->groupBy('category_id', 'product_id')
                    ->with(['category', 'product'])
            // Remove the default filter from here - show ALL data by default
            )
            ->columns([
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
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('all')
                    ->label('All Data')
                    ->default() // This should make it active by default
                    ->query(fn (Builder $query) => $query), // No filtering

                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn (Builder $query) =>
                    $query->whereHas('order', fn ($q) =>
                    $q->whereDate('created_at', today())
                    )
                    ),

                Tables\Filters\Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn (Builder $query) =>
                    $query->whereHas('order', fn ($q) =>
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    )
                    ),

                Tables\Filters\Filter::make('this_month')
                    ->label('This Month')
                    ->query(fn (Builder $query) =>
                    $query->whereHas('order', fn ($q) =>
                    $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                    )
                    ),

                Tables\Filters\Filter::make('custom_date')
                    ->label('Custom Date Range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Start Date'),
                        DatePicker::make('end_date')
                            ->label('End Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereHas('order', fn ($q) =>
                                $q->whereDate('created_at', '>=', $date)
                                )
                            )
                            ->when(
                                $data['end_date'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereHas('order', fn ($q) =>
                                $q->whereDate('created_at', '<=', $date)
                                )
                            );
                    }),
            ])
            ->headerActions([
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $filters = $livewire->tableFilters ?? [];
                        $search = $livewire->tableSearch ?? '';

                        $downloadUrl = route('item-sales.pdf.download', [
                            'filters' => $filters,
                            'search' => $search,
                            'time' => now()->timestamp
                        ]);

                        return redirect($downloadUrl);
                    }),

                Action::make('print')
                    ->label('Print Report')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(function () {
                        return <<<HTML
                            <script>
                                setTimeout(() => {
                                    const style = document.createElement('style');
                                    style.innerHTML = `
                                        @media print {
                                            .filament-header,
                                            .filament-sidebar,
                                            .filament-main-ctn > div:first-child,
                                            .filament-tables-header-container,
                                            .filament-tables-pagination-container,
                                            .filament-tables-table > div:first-child,
                                            [data-filament-component="header"] {
                                                display: none !important;
                                            }
                                            .filament-tables-container {
                                                box-shadow: none !important;
                                                border: none !important;
                                            }
                                            .filament-tables-table table {
                                                width: 100% !important;
                                                border-collapse: collapse !important;
                                            }
                                            .filament-tables-table th,
                                            .filament-tables-table td {
                                                border: 1px solid #ddd !important;
                                                padding: 8px !important;
                                            }
                                            body {
                                                padding: 20px !important;
                                                font-size: 14px !important;
                                            }
                                            .print-header {
                                                text-align: center;
                                                margin-bottom: 20px;
                                                border-bottom: 2px solid #333;
                                                padding-bottom: 10px;
                                            }
                                        }
                                    `;
                                    document.head.appendChild(style);

                                    // Add print header
                                    const tableContainer = document.querySelector('.filament-tables-table');
                                    const printHeader = document.createElement('div');
                                    printHeader.className = 'print-header';
                                    printHeader.innerHTML = '<h1>Item Sales Report</h1><p>Generated on: ' + new Date().toLocaleString() + '</p>';
                                    tableContainer.parentNode.insertBefore(printHeader, tableContainer);

                                    window.print();

                                    setTimeout(() => {
                                        document.head.removeChild(style);
                                        printHeader.remove();
                                    }, 1000);
                                }, 100);
                            </script>
                        HTML;
                    }),
            ])
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(50);
    }
}
