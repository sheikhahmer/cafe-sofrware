<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Role')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->sortable(),
            ])
            ->filters([]);
//            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
//            ])
//            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
//            ]);
    }
}
