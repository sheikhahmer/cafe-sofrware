<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Role Name')
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('permissions')
                ->label('Assign Permissions')
                ->multiple()
                ->relationship('permissions', 'name')
                ->preload(),
        ]);
    }
}
