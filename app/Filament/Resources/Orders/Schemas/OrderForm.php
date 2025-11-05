<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Placeholder::make('enter-navigation-script')
                ->content(view('component.enter-navigation-js'))
                ->hiddenLabel(),

            Placeholder::make('add-item-shortcut-script')
                ->content(view('component.add-item-shortcut-js'))
                ->hiddenLabel(),

            Section::make('Add Order Details')
                ->reactive()
                ->schema([
                    TextInput::make('customer_name')->label('Customer Name')->disabled(fn(Get $get) => $get('id') !== null),
                    TextInput::make('contact')->label('Contact')->disabled(fn(Get $get) => $get('id') !== null),
                    TextInput::make('address')->label('Address')->disabled(fn(Get $get) => $get('id') !== null),

                    // 🧾 Order Details
                    Select::make('order_type')
                        ->options([
                            'dine_in' => 'Dine in',
                            'takeaway' => 'Takeaway',
                            'delivery' => 'Delivery',
                        ])
                        ->default('dine_in')
                        ->required()
                        ->live()

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
                        ->disabled(fn(Get $get) => $get('id') !== null)
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
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->options(Product::pluck('name', 'id')->toArray())
                                ->live()
                                ->columnSpan(5)
                                ->searchable()
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    $product = \App\Models\Product::find($state);
                                    $price = $product?->price ?? 0;
                                    $set('price', $price);
                                    $set('quantity', 1);
                                    $set('total', $price);
                                    static::updateGrandTotal($get, $set);
                                })
                                ->required(),

                            TextInput::make('quantity')
                                ->numeric()
                                ->live(onBlur: false)
                                ->default(1)
                                ->columnSpan(1)
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    $price = (float) ($get('price') ?? 0);
                                    $qty   = (float) ($state ?? 0);
                                    $set('total', $price * $qty);
                                    static::updateGrandTotal($get, $set);
                                })
                                ->required(),

                            TextInput::make('price')
                                ->numeric()
                                ->disabled()      // keep disabled if you don’t want edits
                                ->dehydrated()
                                ->columnSpan(1)
                                ->required(),

                            TextInput::make('total')
                                ->numeric()
                                ->readOnly()
                                ->dehydrated()
                                ->columnSpan(1)
                                ->required(),
                        ])
                        ->reactive()
                        ->live(onBlur: false)
                        ->columns(8)
                        ->columnSpanFull()
                        ->createItemButtonLabel('Add Item')
                        ->afterStateUpdated(fn(Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                    // 👇 Delivery charges (only visible for delivery)
                    TextInput::make('delivery_charges')
                        ->numeric()
                        ->visible(fn(Get $get) => $get('order_type') === 'delivery')
                        ->live()
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->afterStateUpdated(fn($state, Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                    // Discounts and Total
                    TextInput::make('discount_percentage')
                        ->numeric()

                        ->label('Discount Percentage %')
                        ->live()
                        ->debounce(1000)
                        // Disable on edit page
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->afterStateUpdated(function($state, Get $get, Set $set) {
                            // Disable manual discount if discount percentage is filled
                            if ($state) {
                                $set('discount', null);  // Clear manual discount
                            }
                            static::updateGrandTotal($get, $set);
                        }),

                    TextInput::make('discount')
                        ->numeric()
                        ->live()
                        ->debounce(300)
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
                        ->live()
                        ->debounce(1000)
                        ->disabled(fn(Get $get) => $get('id') !== null)
                        ->afterStateUpdated(fn($state, Get $get, Set $set) => static::updateGrandTotal($get, $set)),

                    TextInput::make('service_charges')
                        ->numeric()
                        ->hidden()      // hide the input visually (optional)
                        ->dehydrated()  // ensure it will be saved, updateGrandTotal() sets it
                        ->reactive(),

                    TextInput::make('grand_total')
                        ->numeric()
                        ->hidden()
                        ->dehydrated()
                        ->reactive(),

                    // 💰 Auto-calculated 7% service charge for dine-in
                    Placeholder::make('service_charges_display')
                        ->label('Service Charges')
                        ->content(function (Get $get) {
                            $items = collect($get('items') ?? []);
                            $itemsTotal = $items->sum(function ($item) {
                                $price = (float) ($item['price'] ?? 0);
                                $qty   = (float) ($item['quantity'] ?? 0);
                                return $price * $qty;
                            });

                            $serviceCharge = $get('order_type') === 'dine_in'
                                ? round($itemsTotal * 0.07, 2)
                                : 0;

                            return number_format($serviceCharge, 2);
                        })
                        ->reactive(),

                    Placeholder::make('grand_total_display')
                        ->label('Grand Total')
                        ->content(function (Get $get) {
                            $items = collect($get('items') ?? []);
                            $itemsTotal = $items->sum(function ($item) {
                                $price = (float) ($item['price'] ?? 0);
                                $qty   = (float) ($item['quantity'] ?? 0);
                                return $price * $qty;
                            });

                            $discountPercent = (float) ($get('discount_percentage') ?? 0);
                            $manualDiscount  = (float) ($get('discount') ?? 0);
                            $gst             = (float) ($get('GST Tax') ?? 0);
                            $delivery        = (float) ($get('delivery_charges') ?? 0);
                            $orderType       = $get('order_type');

                            $serviceCharge = $orderType === 'dine_in' ? round($itemsTotal * 0.07, 2) : 0;
                            $discountFromPercentage = ($itemsTotal * $discountPercent) / 100;
                            $subtotalAfterDiscounts = $itemsTotal - $discountFromPercentage - $manualDiscount;
                            if ($subtotalAfterDiscounts < 0) {
                                $subtotalAfterDiscounts = 0;
                            }

                            $grandTotal = $subtotalAfterDiscounts + $serviceCharge + $gst;
                            if ($orderType === 'delivery') {
                                $grandTotal += $delivery;
                            }

                            return number_format(round($grandTotal, 2), 2);
                        })
                        ->reactive(),
                ])
                ->columnSpanFull()
                ->columns(2)
        ]);
    }

    // 🧮 Central total calculation
    protected static function updateGrandTotal(Get $get, Set $set): void
    {
        // --- Collect all items safely ---
        $items = collect($get('items') ?? []);

        // --- Step 1: Base subtotal ---
        $itemsTotal = $items->sum(function ($item) {
            $price = (float) ($item['price'] ?? 0);
            $qty   = (float) ($item['quantity'] ?? 0);
            return $price * $qty;
        });

        // --- Step 2: Extract form values ---
        $discountPercent = (float) ($get('discount_percentage') ?? 0);
        $manualDiscount  = (float) ($get('discount') ?? 0);
        $delivery        = (float) ($get('delivery_charges') ?? 0);
        $gst             = (float) ($get('GST Tax') ?? 0);
        $orderType       = $get('order_type');

        // --- Step 3: Service charge (7% only for dine-in) ---
        $serviceCharge = $orderType === 'dine_in'
            ? round($itemsTotal * 0.07, 2)
            : 0;

        // Update service charge in UI
        $set('service_charges', $serviceCharge);

        // --- Step 4: Discounts ---
        $discountFromPercentage = ($itemsTotal * $discountPercent) / 100;
        $subtotalAfterDiscounts = $itemsTotal - $discountFromPercentage - $manualDiscount;

        if ($subtotalAfterDiscounts < 0) {
            $subtotalAfterDiscounts = 0; // prevent negative totals
        }

        // --- Step 5: Apply other charges ---
        $grandTotal = $subtotalAfterDiscounts + $serviceCharge + $gst;

        if ($orderType === 'delivery') {
            $grandTotal += $delivery;
        }

        // --- Step 6: Final formatting ---
        $set('grand_total', round($grandTotal, 2));
    }


}
