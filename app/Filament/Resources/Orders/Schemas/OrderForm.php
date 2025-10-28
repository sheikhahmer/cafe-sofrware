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
            Section::make('Add Order Details')
                ->schema([
                    TextInput::make('customer_name')->label('Customer Name')->disabled(fn(Get $get) => $get('id') !== null),
                    TextInput::make('contact')->label('Contact')->disabled(fn(Get $get) => $get('id') !== null),
                    Textarea::make('address')->label('Address')->disabled(fn(Get $get) => $get('id') !== null),

                    // 🧾 Order Details
                    Select::make('order_type')
                        ->options([
                            'dine_in' => 'Dine in',
                            'takeaway' => 'Takeaway',
                            'delivery' => 'Delivery',
                        ])
                        ->required()
                        ->reactive()
                        ->disabled(fn(Get $get) => $get('id') !== null)
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
                        ->visible(fn(Get $get) => $get('order_type') === 'dine_in'),

                    Select::make('waiter_id')
                        ->relationship('waiter', 'name')
                        ->label('Waiter')
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->visible(fn(Get $get) => $get('order_type') === 'dine_in'),

                    Select::make('rider_id')
                        ->label('Rider')
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->options(\App\Models\Rider::pluck('name', 'id'))
                        ->searchable()
                        ->visible(fn(Get $get) => $get('order_type') === 'delivery')
                        ->required(fn(Get $get) => $get('order_type') === 'delivery'),

                    // 🧾 Order Items
                    Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->options(Product::pluck('name', 'id','description'))
                                ->reactive()
                                ->searchable()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $product = Product::find($state);
                                    $set('price', $product?->price ?? 0);
                                })
                                ->required(),

                            TextInput::make('quantity')
                                ->numeric()
                                ->reactive()
                                ->debounce(1000)
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    $set('total', ($get('price') ?? 0) * ($state ?? 0));
                                    static::updateGrandTotal($get, $set);
                                })
                                ->required(),

                            TextInput::make('price')->numeric()->required()->disabled()->dehydrated(),
                            TextInput::make('total')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(),
                        ])
                        ->columns(4)
                        ->columnSpanFull()
                        ->createItemButtonLabel('Add Item')
                        ->reactive()
                        ->afterStateUpdated(fn(Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                    // 💰 Auto-calculated 7% service charge for dine-in
                    TextInput::make('service_charges')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->reactive(),

                    // 👇 Delivery charges (only visible for delivery)
                    TextInput::make('delivery_charges')
                        ->numeric()
                        ->visible(fn(Get $get) => $get('order_type') === 'delivery')
                        ->reactive()
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->afterStateUpdated(fn($state, Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                    // Discounts and Total
                    // Discounts and Total
                    TextInput::make('discount_percentage')
                        ->numeric()
                        ->label('Discount Percentage %')
                        ->reactive()
                        ->debounce(1000)
                        // Disable on edit page
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->afterStateUpdated(function($state, Get $get, Set $set) {
                            // Disable manual discount if discount percentage is filled
                            if ($state) {
                                $set('manual_discount', null);  // Clear manual discount
                            }
                            static::updateGrandTotal($get, $set);
                        }),

                    TextInput::make('manual_discount')
                        ->numeric()
                        ->reactive()
                        ->debounce(1000)
                        // Disable on edit page
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        // Disable if discount percentage is set during create
                        ->afterStateUpdated(function($state, Get $get, Set $set) {
                            // Disable discount percentage if manual discount is filled
                            if ($state) {
                                $set('discount_percentage', null);  // Clear discount percentage
                            }
                            static::updateGrandTotal($get, $set);
                        }),



                    TextInput::make('GST Tax')
                        ->numeric()
                        ->reactive()
                        ->debounce(1000)
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->afterStateUpdated(fn($state, Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                    TextInput::make('grand_total')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                ])
                ->columnSpanFull()
                ->columns(2)
        ]);
    }

    // 🧮 Central total calculation
    protected static function updateGrandTotal(Get $get, Set $set): void
    {
        $itemsTotal = collect($get('items') ?? [])
            ->sum(fn($item) => ((float)($item['price'] ?? 0)) * ((float)($item['quantity'] ?? 0)));

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

        $gst = (float)($get('GST Tax') ?? 0);

        // ✅ Step 4: Calculate final grand total
        $grandTotal = $subtotalAfterDiscounts + $serviceCharge + ($orderType === 'delivery' ? $delivery : 0) + $gst;

        $set('grand_total', $grandTotal);
    }

}
