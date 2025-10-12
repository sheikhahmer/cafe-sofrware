<?php

namespace App\Filament\Resources\ItemSales\Pages;

use App\Filament\Resources\ItemSales\ItemSaleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditItemSale extends EditRecord
{
    protected static string $resource = ItemSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
