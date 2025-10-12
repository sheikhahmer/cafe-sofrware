<?php

namespace App\Filament\Resources\ItemSales\Tables;

use App\Models\OrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => OrderItem::query()
                    // alias category_id as id so Filament can use it as a record key
                    ->selectRaw('category_id as id, category_id, product_id, SUM(quantity) as total_quantity, SUM(total) as total_sales')
                    ->groupBy('category_id', 'product_id')
                    ->with(['category', 'product'])
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
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_sales')
                    ->label('Total Sales')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn (Builder $query) => $query->whereHas('order', fn ($q) => $q->whereDate('bill_date', today()))),

                Tables\Filters\Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn (Builder $query) => $query->whereHas('order', fn ($q) =>
                    $q->whereBetween('bill_date', [now()->startOfWeek(), now()->endOfWeek()])
                    )),
            ])
            ->paginated(false);
    }
}
