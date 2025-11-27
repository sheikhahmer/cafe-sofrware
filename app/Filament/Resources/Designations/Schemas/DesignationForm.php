<?php

namespace App\Filament\Resources\Designations\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DesignationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('enter-navigation-script')
                    ->content(view('component.enter-navigation-js'))
                    ->hiddenLabel(),

                TextInput::make('name')
                    ->default(null),
                TextInput::make('contact_no')
                    ->default(null),
                TextInput::make('cnic')
                    ->numeric()
                    ->default(null),
                TextInput::make('salary')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
