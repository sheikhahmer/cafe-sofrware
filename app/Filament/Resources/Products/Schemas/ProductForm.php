<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Category;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Add Product Details')->schema([
                    Select::make('category_id')
                        ->label('Category')
                        ->options(Category::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('name')
                        ->label('Product Name')
                        ->required(),

                    TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->required(),

                    TextInput::make('description')
                        ->label('Description')
                        ->required(),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
