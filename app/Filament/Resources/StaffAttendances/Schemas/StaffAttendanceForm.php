<?php

namespace App\Filament\Resources\StaffAttendances\Schemas;

use App\Models\Designation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('enter-navigation-script')
                    ->content(view('component.enter-navigation-js'))
                    ->hiddenLabel(),

                Section::make('Staff Attendance Details')
                    ->schema([
                        Select::make('designation_id')
                            ->label('Designation')
                            ->options(Designation::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(2),

                        DatePicker::make('attendance_date')
                            ->label('Attendance Date')
                            ->default(now())
                            ->required()
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Present' => 'Present',
                                'Absent' => 'Absent',
                                'Late' => 'Late',
                                'Half Day' => 'Half Day',
                                'Leave' => 'Leave',
                                'Holiday' => 'Holiday',
                            ])
                            ->default('Present')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('check_in_time')
                            ->label('Check In Time')
                            ->type('time')
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}

