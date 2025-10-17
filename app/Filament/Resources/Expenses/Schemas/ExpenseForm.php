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
                Section::make('Add Daily Expense Details')
                    ->schema([
                        TextInput::make('product')
                            ->label('Details')
                            ->default(null)
                            ->columnSpan(3),

                        TextInput::make('debit')
                            ->default(null)
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
            ]);
    }
    }
