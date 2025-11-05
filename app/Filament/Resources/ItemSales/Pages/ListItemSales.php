<?php

namespace App\Filament\Resources\ItemSales\Pages;

use App\Filament\Resources\ItemSales\ItemSaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItemSales extends ListRecords
{
    protected static string $resource = ItemSaleResource::class;

//    protected function getHeaderActions(): array
//    {
//        return [
//            CreateAction::make(),
//        ];
//    }
}
