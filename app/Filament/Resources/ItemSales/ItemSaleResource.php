<?php

namespace App\Filament\Resources\ItemSales;

use App\Filament\Resources\ItemSales\Pages\ListItemSales;
use App\Filament\Resources\ItemSales\Tables\ItemSalesTable;
use App\Models\OrderItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use UnitEnum;

class ItemSaleResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Item Sales';
    protected static ?string $slug = 'item-sales';
    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return ItemSalesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItemSales::route('/'),
        ];
    }
}
