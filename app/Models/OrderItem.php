<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'category_id',
        'quantity',
        'price',
        'total',
        'kitchen_printed',
        'receipt_printed',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            if ($item->product_id) {
                $item->category_id = \App\Models\Product::where('id', $item->product_id)->value('category_id');
            }
        });

        static::updating(function ($item) {
            if ($item->isDirty('product_id')) {
                $item->category_id = \App\Models\Product::where('id', $item->product_id)->value('category_id');
            }
        });

        static::created(function ($item) {
            $item->order?->recalculateTotals();
        });

        static::updated(function ($item) {
            $item->order?->recalculateTotals();
        });

        static::deleted(function ($item) {
            $item->order?->recalculateTotals();
        });
    }

}
