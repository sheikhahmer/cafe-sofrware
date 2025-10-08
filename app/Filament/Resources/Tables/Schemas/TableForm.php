<?php

namespace App\Filament\Resources\Tables\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('no')
                    ->default(null),
                TextInput::make('serial_no')
                    ->default(null),
            ]);
    }
}
