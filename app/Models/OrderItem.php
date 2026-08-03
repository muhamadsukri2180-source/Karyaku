<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id_order_item';

    public $timestamps = false; // ERD tidak punya created_at/updated_at

    protected $fillable = [
        'order_id', 'product_id', 'price', 'quantity', 'subtotal',
    ];

    protected static function booted()
    {
        static::saving(function (OrderItem $item) {
            $item->subtotal = $item->price * $item->quantity;
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
