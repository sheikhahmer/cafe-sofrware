<?php

namespace App\Filament\Resources\Tables\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Add Table Detais')->schema([
                    TextInput::make('no')
                        ->default(null),
                    TextInput::make('serial_no')
                        ->default(null),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
