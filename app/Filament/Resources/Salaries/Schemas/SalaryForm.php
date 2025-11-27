<?php

namespace App\Filament\Resources\Salaries\Schemas;

use App\Models\Designation;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('enter-navigation-script')
                    ->content(view('component.enter-navigation-js'))
                    ->hiddenLabel(),

                Section::make('Add Salary Payment')
                    ->schema([
                        Select::make('designation_id')
                            ->label('Designation')
                            ->options(Designation::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->default(fn () => request()->get('designation_id'))
                            ->live()
                            ->afterStateUpdated(function (Get $get, $set) {
                                $designationId = $get('designation_id');
                                
                                if ($designationId) {
                                    $designation = Designation::find($designationId);
                                    if ($designation) {
                                        $set('designation_salary', $designation->salary);
                                        $set('remaining_salary', $designation->remainingThisMonth());
                                    }
                                } else {
                                    $set('designation_salary', null);
                                    $set('remaining_salary', null);
                                }
                            })
                            ->columnSpan(2),

                        TextInput::make('designation_salary')
                            ->label('Salary')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(function (Get $get) {
                                $designationId = $get('designation_id');
                                if ($designationId) {
                                    $designation = Designation::find($designationId);
                                    return $designation?->salary;
                                }
                                return null;
                            })
                            ->columnSpan(1),

                        TextInput::make('remaining_salary')
                            ->label('Remaining This Month')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(function (Get $get) {
                                $designationId = $get('designation_id');
                                if ($designationId) {
                                    $designation = Designation::find($designationId);
                                    return $designation?->remainingThisMonth();
                                }
                                return null;
                            })
                            ->columnSpan(1),

                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->rules([
                                function (Get $get) {
                                    return function (string $attribute, $value, Closure $fail) use ($get): void {
                                        $designationId = $get('designation_id');

                                        if (! $designationId) {
                                            return;
                                        }

                                        $designation = Designation::find($designationId);

                                        if (! $designation) {
                                            return;
                                        }

                                        $remaining = $designation->remainingThisMonth();

                                        if ($value > $remaining) {
                                            $fail("Amount cannot be greater than remaining salary for this month ({$remaining}).");
                                        }
                                    };
                                },
                            ])
                            ->columnSpan(1),

                        DatePicker::make('paid_at')
                            ->label('Paid Date')
                            ->default(now())
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('note')
                            ->label('Note')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}

