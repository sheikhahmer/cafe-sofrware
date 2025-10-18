<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Filament\Resources\Orders\Widgets\MonthlyOrdersChart;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'bill_no';

    public static function form(Schema $schema): Schema
    {
        // Uses your custom schema class
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // Uses your custom table schema
        return OrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here if needed later
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
    public static function getWidgets(): array
    {
        return [
            MonthlyOrdersChart::class,
        ];
    }
}
