<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\Pages\CreateSales;
use App\Filament\Resources\Sales\Pages\EditSales;
use App\Filament\Resources\Sales\Pages\ListSales;
use App\Filament\Resources\Sales\Schemas\SalesForm;
use App\Filament\Resources\Sales\Tables\SalesTable;
use App\Models\Order;
use App\Models\Sales;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesResource extends Resource
{
    protected static ?string $model = \App\Models\Order::class;

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Order Sales';
    protected static ?string $slug = 'Order Sales';
    protected static ?string $modelLabel = 'Order Sales';
    protected static ?string $pluralModelLabel = 'Order Sales';
    protected static string|null|\UnitEnum $navigationGroup = 'Reports';

//    public static function form(Schema $schema): Schema
//    {
//        return SalesForm::configure($schema);
//    }

    public static function table(Table $table): Table
    {
        return SalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSales::route('/'),
//            'create' => CreateSales::route('/create'),
//            'edit' => EditSales::route('/{record}/edit'),
        ];
    }
}
