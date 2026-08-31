<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id_category';

    protected $fillable = ['name', 'description', 'status', 'icon'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id_category');
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Product::class, 'category_id', 'product_id', 'id_category', 'id_product');
    }

    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->name);
    }
}