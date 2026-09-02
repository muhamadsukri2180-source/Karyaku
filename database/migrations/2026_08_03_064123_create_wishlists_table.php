<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id('id_wishlist');

            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products', 'id_product')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            // Mencegah duplikasi produk yang sama di wishlist user
            $table->unique(['user_id', 'product_id']);

            // Index untuk mempercepat pencarian item wishlist milik user
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};