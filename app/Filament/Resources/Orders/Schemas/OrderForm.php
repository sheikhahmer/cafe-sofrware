<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('order')
            ->schema([
                TextInput::make('customer_name')->label('Customer Name'),
                TextInput::make('contact')->label('Contact'),
                Textarea::make('address')->label('Address'),

                // 🧾 Order Details
                Select::make('order_type')
                    ->options([
                        'dine_in' => 'Dine in',
                        'takeaway' => 'Takeaway',
                        'delivery' => 'Delivery',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        // Reset or recalc when order type changes
                        if ($state === 'dine_in') {
                            static::updateGrandTotal($get, $set);
                        } elseif ($state === 'delivery') {
                            $set('service_charges', 0);
                            static::updateGrandTotal($get, $set);
                        } else {
                            $set('service_charges', 0);
                            $set('delivery_charges', 0);
                            static::updateGrandTotal($get, $set);
                        }
                    }),

                Select::make('table_id')
                    ->relationship('table', 'no')
                    ->label('Table')
                    ->visible(fn (Get $get) => $get('order_type') === 'dine_in'),

                Select::make('waiter_id')
                    ->relationship('waiter', 'name')
                    ->label('Waiter')
                    ->visible(fn (Get $get) => $get('order_type') === 'dine_in'),

                // 🧾 Order Items
                Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(Product::pluck('name', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $product = Product::find($state);
                                $set('price', $product?->price ?? 0);
                            })
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $set('total', ($get('price') ?? 0) * ($state ?? 0));
                                static::updateGrandTotal($get, $set);
                            })
                            ->required(),

                        TextInput::make('price')->numeric()->required(),
                        TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()

                    ])
                    ->columns(4) // keep internal fields arranged in 4 columns for nice layout
                    ->columnSpanFull() // ⬅️ makes the entire repeater full-width on the form
                    ->createItemButtonLabel('Add Item')
                    ->reactive()
                    ->afterStateUpdated(fn (Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                // 💰 Auto-calculated 7% service charge for dine-in
                TextInput::make('service_charges')
                    ->numeric()
                    ->disabled() // user can’t edit it
                    ->dehydrated() // still saved to DB
                    ->reactive(),

                // 👇 Delivery charges (only visible for delivery)
                TextInput::make('delivery_charges')
                    ->numeric()
                    ->visible(fn (Get $get) => $get('order_type') === 'delivery')
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                // Discounts and Total
                TextInput::make('discount_percentage')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                TextInput::make('manual_discount')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                TextInput::make('grand_total')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

            ])->columnSpanFull()->columns(2)
        ]);
    }

    // 🧮 Central total calculation
    protected static function updateGrandTotal(Get $get, Set $set): void
    {
        $itemsTotal = collect($get('items') ?? [])
            ->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));

        $discountPercent = $get('discount_percentage') ?? 0;
        $manualDiscount = $get('manual_discount') ?? 0;
        $delivery = $get('delivery_charges') ?? 0;
        $orderType = $get('order_type');

        // 🧾 Step 1: Base subtotal (no discounts yet)
        $subtotalBeforeDiscounts = $itemsTotal;

        // ✅ Step 2: Service charge — based on full subtotal, before any discounts
        $serviceCharge = 0;
        if ($orderType === 'dine_in') {
            $serviceCharge = round($subtotalBeforeDiscounts * 0.07, 2);
        }
        $set('service_charges', $serviceCharge);

        // 🧮 Step 3: Apply both discounts AFTER service charge is determined
        $discountAmount = ($itemsTotal * $discountPercent) / 100;
        $subtotalAfterDiscounts = $subtotalBeforeDiscounts - $discountAmount - $manualDiscount;

        // ✅ Step 4: Calculate final grand total
        $grandTotal = $subtotalAfterDiscounts + $serviceCharge + ($orderType === 'delivery' ? $delivery : 0);

        $set('grand_total', $grandTotal);
    }

}
