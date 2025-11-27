<?php

namespace App\Filament\Resources\Salaries\Tables;

use App\Models\DesignationSalaryPayment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('designation.name')
                    ->label('Designation')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(function (DesignationSalaryPayment $record): string {
                        $designation = $record->designation;
                        if ($designation && $designation->remainingThisMonth() <= 0) {
                            return 'Full Paid';
                        }
                        return 'Partial';
                    })
                    ->color(function (DesignationSalaryPayment $record): string {
                        $designation = $record->designation;
                        if ($designation && $designation->remainingThisMonth() <= 0) {
                            return 'success';
                        }
                        return 'warning';
                    }),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Note')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}


