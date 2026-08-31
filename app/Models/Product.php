<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id_product';

    protected $fillable = [
        'seller_id', 'category_id', 'title', 'description',
        'price', 'stock', 'file', 'thumbnail', 'status', 'rejection_note',
        'is_promoted', 'promoted_until', 'view_count', 'sold_count'
    ];

    protected $casts = [
        'is_promoted'     => 'boolean',
        'promoted_until'  => 'datetime',
        'price'           => 'float',
        'stock'           => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id_user');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id_user');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id_product');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id', 'id_product');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id', 'id_product');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(Verification::class, 'product_id', 'id_product');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'product_id', 'id_product');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id', 'id_product');
    }

    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 5.0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}
