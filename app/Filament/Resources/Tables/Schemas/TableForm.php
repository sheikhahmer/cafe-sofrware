<?php

namespace App\Filament\Resources\Tables\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('enter-navigation-script')
                    ->content(view('component.enter-navigation-js'))
                    ->hiddenLabel(),
                Section::make('Add Table Detais')->schema([
                    TextInput::make('no')
                        ->default(null),
                    TextInput::make('serial_no')
                        ->default(null),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
