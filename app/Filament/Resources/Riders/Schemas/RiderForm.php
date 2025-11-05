<?php

namespace App\Filament\Resources\Riders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RiderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('enter-navigation-script')
                    ->content(view('component.enter-navigation-js'))
                    ->hiddenLabel(),
                Section::make('Add Rider Details')->schema([
                    TextInput::make('name')
                        ->default(null),
                    TextInput::make('contact')
                        ->numeric()
                        ->default(null),
                ])->columnSpanFull()->columns(2)
            ]);
    }
}
