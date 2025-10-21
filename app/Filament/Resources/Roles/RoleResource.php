<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use Spatie\Permission\Models\Role;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    // ✅ For Filament 4, this must be: UnitEnum|string|null
    protected static string|null|\UnitEnum $navigationGroup = 'User Management';

    protected static string|null $navigationLabel = 'Roles';

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view roles');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create roles');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit roles');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete roles');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view roles');
    }

}
