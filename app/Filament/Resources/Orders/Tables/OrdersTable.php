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
                TextColumn::make('id')->label('Order No')->sortable(),
                TextColumn::make('customer_name')->label('Customer')->searchable(),
//                TextColumn::make('rider.name')
//                    ->label('Rider')
//                    ->sortable()
//                    ->searchable(),

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
                    ->date()
                    ->sortable(),

            ])

            ->recordUrl(fn ($record) =>
            $record->status === 'paid'
                ? null
                : route('filament.admin.resources.orders.edit', $record)
            )

            ->recordActions([
                EditAction::make()
                    ->disabled(fn ($record) => $record->status === 'paid'),

                // 🍳 Kitchen Print — enabled only if there are unprinted items
                Action::make('kitchenPrint')
                    ->label('Kitchen Print')
                    ->icon('heroicon-o-printer')
                    ->color(fn ($record) =>
                    $record->items()->where('kitchen_printed', false)->exists() ? 'warning' : 'success'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
                        // Get only unprinted items
                        $unprintedItems = $record->items()->where('kitchen_printed', false)->get();

                        if ($unprintedItems->isNotEmpty()) {
                            // Mark them as printed
                            $record->items()->whereIn('id', $unprintedItems->pluck('id'))->update(['kitchen_printed' => true]);

                            // Build print URL with item IDs
                            $itemIds = implode(',', $unprintedItems->pluck('id')->toArray());
                            $url = route('orders.print.kitchen', ['order' => $record->id, 'item_ids' => $itemIds]);

                            // Refresh the table instantly
                            $livewire->dispatch('$refresh');

                            // Open print window
                            $livewire->js("window.open('{$url}', '_blank');");
                        }
                    })
                    ->disabled(fn ($record) =>
                    !$record->items()->where('kitchen_printed', false)->exists()
                    )
                    ->tooltip(fn ($record) =>
                    $record->items()->where('kitchen_printed', false)->exists()
                        ? 'Click to print new items'
                        : 'Already printed all items'
                    ),



        // 🧾 Print Only — enabled if there are unprinted receipt items
                Action::make('print_only')
                    ->label('Print Only')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
                        // Mark items as receipt printed
                        $record->items()->update(['receipt_printed' => true]);

                        // Refresh to disable button immediately
                        $livewire->dispatch('$refresh');

                        // Open print in new tab AFTER updating the database
                        $url = route('orders.print.receipt', $record);
                        $livewire->js("window.open('{$url}', '_blank');");
                    })
                    ->disabled(function ($record) {
                        return !$record->items()->where('receipt_printed', false)->exists();
                    })
                    ->tooltip(fn ($record) =>
                    $record->items()->where('receipt_printed', false)->exists()
                        ? 'Click to print'
                        : 'Already printed'
                    ),

                // 💵 Paid Print — marks order as paid
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
                    ->disabled(fn ($record) => $record->status === 'paid')
                    ->tooltip(fn ($record) =>
                    $record->where('status' ,'pending')->exists()
                        ? 'Click to print'
                        : 'Already printed'
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
