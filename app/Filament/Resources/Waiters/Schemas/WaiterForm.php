<?php

namespace App\Filament\Resources\Waiters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WaiterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->default(null),
                TextInput::make('contact')
                    ->default(null),
            ]);
    }
}
