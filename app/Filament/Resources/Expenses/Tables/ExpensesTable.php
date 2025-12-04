<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('account_description')
                    ->searchable(),
                TextColumn::make('product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('debit')
                    ->searchable()
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
            ->recordUrl(null)
            ->modifyQueryUsing(function ($query) {
                $now = now();
                $startOfDay = $now->copy()->setTime(10, 0, 0);
                if ($now->lt($startOfDay)) {
                    $startOfDay->subDay();
                }
                $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            })
            ->headerActions([
                Action::make('download_pdf')
                    ->visible(fn () => auth()->user()->hasRole('Admin'))
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $search = $livewire->tableSearch ?? '';
                        $downloadUrl = route('expense.pdf.download', ['search' => $search]);
                        return redirect($downloadUrl);
                    }),

                Action::make('print')
                    ->visible(fn () => auth()->user()->hasRole('Admin'))
                    ->label('Print Report')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(function ($livewire) {
                        $search = $livewire->tableSearch ?? '';
                        $printUrl = route('expense.print', ['search' => $search]);
                        $livewire->js("window.open('{$printUrl}', '_blank')");
                    }),
            ])
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
