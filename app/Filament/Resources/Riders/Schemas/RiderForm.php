<?php

namespace App\Filament\Resources\Riders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RiderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->default(null),
                TextInput::make('contact')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
