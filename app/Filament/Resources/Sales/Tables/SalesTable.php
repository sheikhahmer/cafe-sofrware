<?php

namespace App\Filament\Resources\Sales\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order No')->sortable(),
                TextColumn::make('customer_name')->label('Customer')->searchable(),
                TextColumn::make('order_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'dine_in' => 'Dine In',
                        'take_away' => 'Take Away',
                        'delivery' => 'Delivery',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    }),
                TextColumn::make('grand_total')->label('Total')->numeric(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'printed',
                        'success' => 'paid',
                    ]),
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('d M Y h:i A')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('order_type')
                    ->label('Order Type')
                    ->options([
                        'dine_in' => 'Dine In',
                        'take_away' => 'Take Away',
                        'delivery' => 'Delivery',
                    ])
                    ->searchable(),
            ])

            // ✅ Filter by business-day range (10 AM → 4 AM next day)
            ->modifyQueryUsing(function ($query) {
                $now = now();

                // Start from today's 10 AM
                $startOfDay = $now->copy()->setTime(10, 0, 0);

                // If it's before 10 AM, we’re still in the previous day’s sales window
                if ($now->lt($startOfDay)) {
                    $startOfDay->subDay();
                }

                // End = next day 4 AM
                $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);

                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            })
            ->headerActions([
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $search = $livewire->tableSearch ?? '';
                        $downloadUrl = route('sales-orders.pdf.download', ['search' => $search]);
                        return redirect($downloadUrl);
                    }),

                Action::make('print')
                    ->label('Print Report')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(function ($livewire) {
                        $search = $livewire->tableSearch ?? '';
                        $printUrl = route('sales-orders.print', ['search' => $search]);
                        $livewire->js("window.open('{$printUrl}', '_blank')");
                    }),
            ])


            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);

    }
}
