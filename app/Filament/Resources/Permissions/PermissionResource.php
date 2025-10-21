<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Spatie\Permission\Models\Permission;
use Filament\Support\Icons\Heroicon;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|null|\UnitEnum $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Permissions';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedKey;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            \Filament\Forms\Components\TextInput::make('name')
                ->label('Permission Name')
                ->required()
                ->unique(ignoreRecord: true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('guard_name'),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }
}
