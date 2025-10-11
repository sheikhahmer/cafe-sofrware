<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
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

                TextColumn::make('bill_no')->label('Bill No'),
                TextColumn::make('grand_total')->label('Total')->numeric(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'printed',
                        'success' => 'paid',
                    ]),
                TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
            ])

            ->recordUrl(fn ($record) =>
                // disable click-to-edit when paid
            $record->status === 'paid'
                ? null
                : route('filament.admin.resources.orders.edit', $record)
            )

            ->recordActions([
                // ✏️ Edit — disabled if paid
                EditAction::make()
                    ->disabled(fn ($record) => $record->status === 'paid'),

                // 🍳 Kitchen Print — enabled until paid
                Action::make('kitchen_print')
                    ->label('Kitchen Print')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn ($record) => route('orders.print.kitchen', $record))
                    ->openUrlInNewTab()
                    ->disabled(fn ($record) => $record->status === 'paid'),

                // 🧾 Print Only — enabled until paid
                Action::make('print_only')
                    ->label('Print Only')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('orders.print.receipt', $record))
                    ->openUrlInNewTab()
                    ->requiresConfirmation()
                    ->disabled(fn ($record) => $record->status === 'paid'),

                // 💵 Paid Print — marks order as paid, disables all after
                Action::make('paid_print')
                    ->label('Paid Print')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
                        $record->update(['status' => 'paid']);
                        $livewire->dispatch('$refresh');
                        $url = route('orders.print.paid', $record);
                        $livewire->js("window.open('{$url}', '_blank');");
                    })
                    ->disabled(fn ($record) => $record->status === 'paid'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
