<?php

namespace App\Filament\Resources\StaffAttendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StaffAttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('designation.name')
                    ->label('Designation')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attendance_date')
                    ->date()
                    ->sortable()
                    ->label('Date'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Present' => 'success',
                        'Absent' => 'danger',
                        'Late' => 'warning',
                        'Half Day' => 'info',
                        'Leave' => 'gray',
                        'Holiday' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('check_in_time')
                    ->label('Check In Time')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('status')
                    ->options([
                        'Present' => 'Present',
                        'Absent' => 'Absent',
                        'Late' => 'Late',
                        'Half Day' => 'Half Day',
                        'Leave' => 'Leave',
                        'Holiday' => 'Holiday',
                    ]),

                SelectFilter::make('designation_id')
                    ->label('Designation')
                    ->relationship('designation', 'name')
                    ->searchable(),
            ])
            ->defaultSort('attendance_date', 'desc')
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

