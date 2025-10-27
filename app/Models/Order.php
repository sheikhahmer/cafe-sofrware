<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'contact',
        'address',
        'order_type',
        'table_id',
        'waiter_id',
        'service_charges',
        'discount_percentage',
        'manual_discount',
        'grand_total',
        'gst_amount',
        'delivery_charges',
        'status',
        'rider_id',
        'gst_tax',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function waiter()
    {
        return $this->belongsTo(Waiter::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }


    public function recalculateTotals()
    {
        // 1️⃣ Total of all items
        $itemsTotal = $this->items()->sum(\DB::raw('price * quantity'));

        // 2️⃣ Calculate service charge (only for dine-in)
        $serviceCharge = 0;
        if ($this->order_type === 'dine_in') {
            $serviceCharge = round($itemsTotal * 0.07, 2);
        }

        // 3️⃣ Calculate discounts
        $discountPercent = $this->discount_percentage ?? 0;
        $manualDiscount = $this->manual_discount ?? 0;
        $discountAmount = ($itemsTotal * $discountPercent / 100) + $manualDiscount;

        // 4️⃣ Calculate grand total
        $delivery = $this->delivery_charges ?? 0;
        $grandTotal = $itemsTotal - $discountAmount + $serviceCharge + $delivery;

        // 5️⃣ Save everything
        $this->update([
            'service_charges' => $serviceCharge,
            'grand_total' => $grandTotal,
        ]);
    }

}
