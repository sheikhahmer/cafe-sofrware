<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema {
        return $schema->components([

            Placeholder::make('enter-navigation-script')
                ->content(view('component.enter-navigation-js'))
                ->hiddenLabel(),

            Placeholder::make('add-item-shortcut-script')
                ->content(view('component.add-item-shortcut-js'))
                ->hiddenLabel(),

            Placeholder::make('add-new-expense-shortcut')
                ->content(view('component.table-js'))
                ->hiddenLabel(),

            // ============================
            // CREATE PAGE — SHOW REPEATER
            // ============================
            Section::make('Add Daily Expense Details')
                ->schema([
                    Repeater::make('items')
                        ->label('Add Item')
                        ->schema([
                            TextInput::make('product')
                                ->label('Details')
                                ->required()
                                ->columnSpan(3),

                            TextInput::make('debit')
                                ->numeric()
                                ->required()
                                ->columnSpan(1),
                        ])
                        ->addActionLabel('Add Item')
                        ->columns(4)
                        ->defaultItems(1)
                        ->minItems(1),
                ])
                ->visible(fn ($livewire) =>
                    $livewire instanceof \Filament\Resources\Pages\CreateRecord
                )
                ->columns(1)
                ->columnSpanFull(),

            // ============================
// EDIT PAGE — SINGLE FIELDS
// ============================
            Section::make('Edit Expense Details')
                ->schema([
                    TextInput::make('product')
                        ->label('Details')
                        ->required()
                        ->columnSpan(3),

                    TextInput::make('debit')
                        ->numeric()
                        ->required()
                        ->columnSpan(1),
                ])
                ->columns(4)
                ->visible(fn ($livewire) =>
                    $livewire instanceof \Filament\Resources\Pages\EditRecord
                )
                ->columnSpanFull(),

        ]);
    }

    }
