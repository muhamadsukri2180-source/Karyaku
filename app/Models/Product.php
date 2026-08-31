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
        'price', 'stock', 'file', 'thumbnail', 'images', 'video', 'status', 'rejection_note',
        'is_promoted', 'promoted_until', 'view_count', 'sold_count'
    ];

    protected $casts = [
        'is_promoted'     => 'boolean',
        'promoted_until'  => 'datetime',
        'price'           => 'float',
        'stock'           => 'integer',
        'images'          => 'array',
    ];

    public function getImagesListAttribute(): array
    {
        $list = [];
        if ($this->thumbnail) {
            $list[] = $this->thumbnail;
        }
        if (is_array($this->images)) {
            foreach ($this->images as $img) {
                if (!empty($img) && !in_array($img, $list)) {
                    $list[] = $img;
                }
            }
        }
        return array_slice($list, 0, 5);
    }

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
        if (array_key_exists('reviews_avg_rating', $this->attributes) && $this->attributes['reviews_avg_rating'] !== null) {
            return round((float) $this->attributes['reviews_avg_rating'], 1);
        }
        if ($this->relationLoaded('reviews')) {
            $avg = $this->reviews->avg('rating');
            return round((float) ($avg !== null ? $avg : 5.0), 1);
        }
        return round((float) ($this->reviews()->avg('rating') ?? 5.0), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        if (array_key_exists('reviews_count', $this->attributes) && $this->attributes['reviews_count'] !== null) {
            return (int) $this->attributes['reviews_count'];
        }
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }
        return $this->reviews()->count();
    }
}
