<?php

namespace App\Filament\Resources\StaffAttendances;

use App\Filament\Resources\StaffAttendances\Pages\CreateStaffAttendance;
use App\Filament\Resources\StaffAttendances\Pages\EditStaffAttendance;
use App\Filament\Resources\StaffAttendances\Pages\ListStaffAttendances;
use App\Filament\Resources\StaffAttendances\Schemas\StaffAttendanceForm;
use App\Filament\Resources\StaffAttendances\Tables\StaffAttendancesTable;
use App\Models\StaffAttendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StaffAttendanceResource extends Resource
{
    protected static ?string $model = StaffAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'Staff Attendance';

    protected static string|UnitEnum|null $navigationGroup = 'HR';

    public static function form(Schema $schema): Schema
    {
        return StaffAttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffAttendancesTable::configure($table);
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
            'index' => ListStaffAttendances::route('/'),
            'create' => CreateStaffAttendance::route('/create'),
            'edit' => EditStaffAttendance::route('/{record}/edit'),
        ];
    }
}

