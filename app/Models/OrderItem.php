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
        'product_title', // snapshot judul, terisi otomatis saat order dibuat
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
    // Tetap aman dipakai walau produk sudah dihapus (product_id null),
    // karena product_title sudah tersimpan sebagai snapshot.
    public function getSellerNameAttribute(): string
    {
        return $this->product?->seller?->name ?? '-';
    }

    public function getProductNameAttribute(): string
    {
        return $this->product_title ?? ($this->product?->title ?? 'Produk telah dihapus');
    }
}