<?php

namespace App\Filament\Resources\Waiters\Pages;

use App\Filament\Resources\Waiters\WaiterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWaiter extends EditRecord
{
    protected static string $resource = WaiterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
