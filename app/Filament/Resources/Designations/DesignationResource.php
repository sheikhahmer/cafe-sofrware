<?php

namespace App\Filament\Resources\Designations;

use App\Filament\Resources\Designations\Pages\CreateDesignation;
use App\Filament\Resources\Designations\Pages\EditDesignation;
use App\Filament\Resources\Designations\Pages\ListDesignations;
use App\Filament\Resources\Designations\Schemas\DesignationForm;
use App\Filament\Resources\Designations\Tables\DesignationsTable;
use App\Models\Designation;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DesignationResource extends Resource
{
    protected static ?string $model = Designation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Designation';

    protected static string|UnitEnum|null $navigationGroup = 'HR';

    public static function form(Schema $schema): Schema
    {
        return DesignationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DesignationsTable::configure($table);
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
            'index' => ListDesignations::route('/'),
            'create' => CreateDesignation::route('/create'),
            'edit' => EditDesignation::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view designations');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create designations');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit designations');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete designations');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view designations');
    }
}
