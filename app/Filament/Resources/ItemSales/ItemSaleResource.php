<?php

namespace App\Filament\Resources\ItemSales;

use App\Filament\Resources\ItemSales\Pages\ListItemSales;
use App\Filament\Resources\ItemSales\Tables\ItemSalesTable;
use App\Models\OrderItem;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class ItemSaleResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Item Sales';
    protected static ?string $slug = 'item-sales';
    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        // Delegate table configuration to your custom class
        return ItemSalesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItemSales::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view item sales');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create item sales');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit item sales');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete item sales');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view item sales');
    }
}
