<?php

namespace App\Filament\Resources\ItemSales\Pages;

use App\Filament\Resources\ItemSales\ItemSaleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewItemSale extends ViewRecord
{
    protected static string $resource = ItemSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
