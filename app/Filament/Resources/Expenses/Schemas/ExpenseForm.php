<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Add Daily Expense Details')->schema([
                    TextInput::make('account_description')
                        ->disabled()
                        ->default('DAILY EXPENSES'),
                    TextInput::make('product')->label('Details')
                        ->default(null),
                    TextInput::make('debit')
                        ->default(null),
                    TextInput::make('credit')
                        ->default(null),
                ])->columnSpanFull()->columns(2)
            ]);
    }
}
