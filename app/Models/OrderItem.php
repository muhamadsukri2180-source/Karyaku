<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id_order_item';

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    // Tidak ada kolom seller_id terpisah -> ambil dari relasi product->seller
    public function getSellerNameAttribute(): string
    {
        return $this->product?->seller?->name ?? '-';
    }

    public function getProductNameAttribute(): string
    {
        return $this->product?->title ?? 'Produk telah dihapus';
    }
}