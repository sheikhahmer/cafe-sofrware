<?php

namespace App\Filament\Resources\Orders\Tables;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order No')->sortable(),
                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->getStateUsing(fn($record) => $record->customer_name ?: '—'),

                TextColumn::make('gst_tax')
                    ->label('GST TAX')
                    ->searchable()
                    ->getStateUsing(fn($record) => $record->gst_tax ?: '—'),


                TextColumn::make('order_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'dine_in' => 'Dine In',
                        'takeaway' => 'Take Away',
                        'delivery' => 'Delivery',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->colors([
                        'warning' => 'dine_in',
                        'danger' => 'takeaway',
                        'success' => 'delivery',
                    ]),

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
                    ->visible(fn () => auth()->user()->hasRole('Admin')),
//                    ->disabled(fn ($record) => $record->status === 'paid'),

                // 🍳 Kitchen Print — enabled only if there are unprinted items
                Action::make('kitchenPrint')
                    ->label('Kitchen Print')
                    ->icon('heroicon-o-printer')
                    ->color(fn ($record) =>
                    $record->items()->where('kitchen_printed', false)->exists() ? 'warning' : 'success'
                    )
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
                Action::make('mark_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->action(function ($record, $livewire) {
                        $record->update(['status' => 'paid']);
                        $livewire->dispatch('$refresh'); // refresh table or form
                    })
                    ->disabled(fn ($record) => $record->status === 'paid')
                    ->tooltip(fn ($record) =>
                    $record->status === 'paid'
                        ? 'Already marked as paid'
                        : 'Mark this order as paid'
                    ),

//                Action::make('paid_print')
//                    ->label('Paid Print')
//                    ->icon('heroicon-o-currency-dollar')
//                    ->color('success')
//                    ->action(function ($record, $livewire) {
//                        $record->update(['status' => 'paid']);
//                        $livewire->dispatch('$refresh');
//                        $url = route('orders.print.paid', $record);
//                        $livewire->js("window.open('{$url}', '_blank');");
//                    })
//                    ->disabled(fn ($record) => $record->status === 'paid')
//                    ->tooltip(fn ($record) =>
//                    $record->where('status' ,'pending')->exists()
//                        ? 'Click to print'
//                        : 'Already printed'
//                    ),

                Action::make('addItem')
                    ->label('Add Item')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->visible(fn () => auth()->user()->hasRole('User'))
                    ->disabled(fn ($record) => $record->status === 'paid')
                    ->modalHeading('Add Item to Order')
                    ->form([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(\App\Models\Product::pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = \App\Models\Product::find($state);
                                $set('price', $product?->price ?? 0);
                            })
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->debounce(300)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('total', ($get('price') ?? 0) * ($state ?? 0));
                            })
                            ->required(),

                        TextInput::make('price')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->action(function (array $data, $record, $livewire) {

                        // 🟢 1. Create new item
                        $record->items()->create([
                            'product_id' => $data['product_id'],
                            'price'      => $data['price'],
                            'quantity'   => $data['quantity'],
                            'total'      => $data['total'],
                        ]);

                        // 🟢 2. Recalculate totals
                        $itemsTotal = $record->items()->sum('total');
                        $serviceCharges = $record->service_charges ?? 0;

                        // grand_total = items total + service charge
                        $grandTotal = $itemsTotal + $serviceCharges;

                        // 🟢 3. Update the order record
                        $record->update([
                            'grand_total' => $grandTotal,
                        ]);

                        // 🟢 4. Refresh and notify
                        $livewire->dispatch('$refresh');

                        \Filament\Notifications\Notification::make()
                            ->title('Item added successfully!')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->exists && auth()->user()->hasRole('User'))
                    ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->modifyQueryUsing(function ($query) {

                if (auth()->user()->hasRole('User')) {
                    $query->where('status', '!=', 'paid');
                }

                $now = now();
                $startOfDay = $now->copy()->setTime(10, 0, 0);
                if ($now->lt($startOfDay)) {
                    $startOfDay->subDay();
                }

                $endOfDay = $startOfDay->copy()->addDay()->setTime(4, 0, 0);
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            });

    }
}
