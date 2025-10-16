<?php

namespace App\Filament\Resources\Waiters\Pages;

use App\Filament\Resources\Waiters\WaiterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWaiter extends CreateRecord
{
    protected static string $resource = WaiterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
