<?php

namespace App\Filament\Resources\Waiters;

use App\Filament\Resources\Waiters\Pages\CreateWaiter;
use App\Filament\Resources\Waiters\Pages\EditWaiter;
use App\Filament\Resources\Waiters\Pages\ListWaiters;
use App\Filament\Resources\Waiters\Schemas\WaiterForm;
use App\Filament\Resources\Waiters\Tables\WaitersTable;
use App\Models\Waiter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WaiterResource extends Resource
{
    protected static ?string $model = Waiter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'Waiter';

    public static function form(Schema $schema): Schema
    {
        return WaiterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WaitersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWaiters::route('/'),
            'create' => CreateWaiter::route('/create'),
            'edit' => EditWaiter::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view waiters');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create waiters');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit waiters');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete waiters');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view waiters');
    }
}
