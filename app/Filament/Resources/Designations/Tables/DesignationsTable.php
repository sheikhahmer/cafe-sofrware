<?php

namespace App\Filament\Resources\Designations\Tables;

use App\Models\Designation;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DesignationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('contact_no')
                    ->searchable(),
                TextColumn::make('cnic')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('salary')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('paid_this_month')
                    ->label('Paid This Month')
                    ->state(function (Designation $record): float {
                        return $record->paidThisMonth();
                    })
                    ->numeric()
                    ->sortable(),
                TextColumn::make('remaining_this_month')
                    ->label('Remaining This Month')
                    ->state(function (Designation $record): float {
                        return $record->remainingThisMonth();
                    })
                    ->numeric()
                    ->sortable(),
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
                Action::make('addSalary')
                    ->label('Add Salary')
                    ->icon('heroicon-o-currency-dollar')
                    ->url(function (Designation $record): string {
                        return \App\Filament\Resources\Salaries\SalaryResource::getUrl('create', [
                            'designation_id' => $record->id,
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
