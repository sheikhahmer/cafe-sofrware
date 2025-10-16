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

}
